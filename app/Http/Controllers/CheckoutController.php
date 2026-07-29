<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCheckoutRequest;
use App\Models\Order;
use App\Models\PromoCode;
use App\Services\CartService;
use App\Services\OrderService;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected OrderService $orderService,
    ) {}

    public function index()
    {
        $cart = $this->cartService->getCurrentCart();
        $cart->load('items.product');

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'سلتك فارغة، أضف منتجات أولاً.');
        }

        $promoCode = null;
        $discount = 0;

        if (session()->has('promo_code_id')) {
            $promoCode = PromoCode::find(session('promo_code_id'));
            if ($promoCode && $promoCode->isValid($cart->subtotal)) {
                $discount = $promoCode->calculateDiscount($cart->subtotal);
            }
        }

        $total = $cart->subtotal - $discount;

        return view('checkout.index', compact('cart', 'promoCode', 'discount', 'total'));
    }

    public function store(StoreCheckoutRequest $request)
    {
        $cart = $this->cartService->getCurrentCart();
        $cart->load('items.product');

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'سلتك فارغة.');
        }

        foreach ($cart->items as $item) {
            if ($item->quantity > $item->product->stock) {
                return back()->with('error', "الكمية المطلوبة من \"{$item->product->name}\" غير متوفرة بالمخزون.");
            }
        }

        $order = $this->orderService->createFromCart(
            cart: $cart,
            customerData: $request->validated(),
        );

        $this->cartService->clearCart();
        session()->forget('promo_code_id');
        session()->put('last_order_number', $order->order_number);

        if ($order->payment_method === 'cod') {
            $this->orderService->sendOrderEmails($order);
            return redirect()->route('checkout.success', $order->order_number);
        }

        return redirect()->route('payment.process', $order->order_number);
    }

    public function success(Order $order)
    {
        abort_if(session('last_order_number') !== $order->order_number, 403);

        return view('checkout.success', compact('order'));
    }
}