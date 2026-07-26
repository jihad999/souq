<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\PromoCode;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(protected CartService $cartService) {}

    public function index()
    {
        $cart = $this->cartService->getCurrentCart();
        $cart->load('items.product');

        $promoCode = null;
        $discount = 0;

        if (session()->has('promo_code_id')) {
            $promoCode = PromoCode::find(session('promo_code_id'));

            if ($promoCode && $promoCode->isValid($cart->subtotal)) {
                $discount = $promoCode->calculateDiscount($cart->subtotal);
            } else {
                session()->forget(['promo_code_id']);
                $promoCode = null;
            }
        }

        $total = $cart->subtotal - $discount;

        return view('cart.index', compact('cart', 'promoCode', 'discount', 'total'));
    }

    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1', 'max:' . max($product->stock, 1)],
        ]);

        if ($product->stock <= 0) {
            return back()->with('error', 'هذا المنتج غير متوفر حاليًا.');
        }

        $this->cartService->addProduct($product, $request->get('quantity', 1));

        return back()->with('success', 'تمت إضافة المنتج للسلة.');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $request->validate(['quantity' => ['required', 'integer', 'min:0']]);

        $this->cartService->updateQuantity($cartItem->id, $request->quantity);

        return back()->with('success', 'تم تحديث السلة.');
    }

    public function remove(CartItem $cartItem)
    {
        $this->cartService->removeItem($cartItem->id);

        return back()->with('success', 'تم حذف المنتج من السلة.');
    }

    public function applyPromo(Request $request)
    {
        $request->validate(['code' => ['required', 'string']]);

        $promoCode = PromoCode::where('code', $request->code)->first();
        $cart = $this->cartService->getCurrentCart();

        if (! $promoCode || ! $promoCode->isValid($cart->subtotal)) {
            return back()->with('error', 'كود الخصم غير صالح أو منتهي الصلاحية.');
        }

        session()->put('promo_code_id', $promoCode->id);

        return back()->with('success', 'تم تفعيل كود الخصم بنجاح.');
    }

    public function removePromo()
    {
        session()->forget('promo_code_id');

        return back()->with('success', 'تم إلغاء كود الخصم.');
    }
}