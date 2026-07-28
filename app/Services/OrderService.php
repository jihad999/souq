<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\PromoCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
                'subtotal' => $subtotal,
                'discount_amount' => $discount,
                'total' => $total,
                'payment_method' => $customerData['payment_method'],
                'payment_status' => 'pending',
                'status' => 'pending',
            ]);

            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->product->final_price,
                    'total_price' => $item->product->final_price * $item->quantity,
                ]);

                // خصم الكمية من المخزون
                $item->product->decrement('stock', $item->quantity);
            }

            if ($promoCode) {
                $promoCode->increment('used_count');
            }

            return $order;
        });
    }
}