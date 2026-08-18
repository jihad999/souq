<?php

namespace App\Services;

use App\Mail\NewOrderAdminNotification;
use App\Mail\OrderInvoiceMail;
use App\Models\Cart;
use App\Models\Order;
use App\Models\PromoCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderService
{
    public function createFromCart(Cart $cart, array $customerData): Order
    {
        return DB::transaction(function () use ($cart, $customerData) {

            $promoCode = null;
            $discount = 0;

            if (session()->has('promo_code_id')) {
                $promoCode = PromoCode::find(session('promo_code_id'));
                if ($promoCode && $promoCode->isValid($cart->subtotal)) {
                    $discount = $promoCode->calculateDiscount($cart->subtotal);
                } else {
                    $promoCode = null;
                }
            }

            $subtotal = $cart->subtotal;
            $total = $subtotal - $discount;

            $order = Order::create([
                'user_id' => Auth::id(),
                'promo_code_id' => $promoCode?->id,
                'customer_name' => $customerData['customer_name'],
                'customer_email' => $customerData['customer_email'],
                'customer_phone' => $customerData['customer_phone'],
                'shipping_address' => $customerData['shipping_address'],
                'latitude' => $customerData['payment_method'] === 'cod' ? ($customerData['latitude'] ?? null) : null,
                'longitude' => $customerData['payment_method'] === 'cod' ? ($customerData['longitude'] ?? null) : null,
                'subtotal' => $subtotal,
                'discount_amount' => $discount,
                'total' => $total,
                'payment_method' => $customerData['payment_method'],
                'payment_status' => 'pending',
                'status' => 'pending',
            ]);

            foreach ($cart->items as $item) {
                $variant = $item->variant;
                $price = $variant ? $variant->final_price : $item->product->final_price;

                $order->items()->create([
                    'product_id' => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'product_name' => $item->product->name,
                    'variant_label' => $variant?->label,
                    'quantity' => $item->quantity,
                    'unit_price' => $price,
                    'total_price' => $price * $item->quantity,
                ]);

                if ($variant) {
                    if ($variant->stock < $item->quantity) {
                        throw new \Exception(
                            'المنتج "' . $item->product->name .
                            '" لا تتوفر منه الكمية المطلوبة. المطلوبة: ' . $item->quantity .
                            '، المتوفرة: ' . $variant->stock
                        );
                    }

                    $variant->decrement('stock', $item->quantity);
                } else {
                    $item->product->decrement('stock', $item->quantity);
                }
            }

            if ($promoCode) {
                $promoCode->increment('used_count');
            }

            return $order;
        });
    }

    /**
     * يرسل الفاتورة للعميل + إشعار للشركة، بمكان واحد مركزي
     * يستدعى من CheckoutController (لـ COD) ومن PaymentController (بعد نجاح الدفع الإلكتروني)
     */
    public function sendOrderEmails(Order $order): void
    {
        try {
            Mail::to($order->customer_email)->send(new OrderInvoiceMail($order));
            Mail::to(config('mail.company_email'))->send(new NewOrderAdminNotification($order));
        } catch (\Exception $e) {
            \Log::error('Failed to send order emails: ' . $e->getMessage());
        }
    }
}