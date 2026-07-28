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
            return $this->respond($request, false, 'هذا المنتج غير متوفر حاليًا.');
        }

        $this->cartService->addProduct($product, $request->get('quantity', 1));

        return $this->respond($request, true, 'تمت إضافة المنتج للسلة.');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $request->validate(['quantity' => ['required', 'integer', 'min:0']]);

        $this->cartService->updateQuantity($cartItem->id, $request->quantity);

        return $this->respond($request, true, 'تم تحديث السلة.');
    }

    public function remove(Request $request, CartItem $cartItem)
    {
        $this->cartService->removeItem($cartItem->id);

        return $this->respond($request, true, 'تم حذف المنتج من السلة.');
    }

    public function applyPromo(Request $request)
    {
        $request->validate(['code' => ['required', 'string']]);

        $promoCode = PromoCode::where('code', $request->code)->first();
        $cart = $this->cartService->getCurrentCart();

        if (! $promoCode || ! $promoCode->isValid($cart->subtotal)) {
            return $this->respond($request, false, 'كود الخصم غير صالح أو منتهي الصلاحية.');
        }

        session()->put('promo_code_id', $promoCode->id);

        return $this->respond($request, true, 'تم تفعيل كود الخصم بنجاح.');
    }

    public function removePromo(Request $request)
    {
        session()->forget('promo_code_id');

        return $this->respond($request, true, 'تم إلغاء كود الخصم.');
    }

    /**
     * يرجع JSON لو الطلب AJAX، أو redirect عادي لو fallback بدون JS
     */
    private function respond(Request $request, bool $success, string $message)
    {
        if ($request->wantsJson() || $request->ajax()) {
            $cart = $this->cartService->getCurrentCart();
            $cart->load('items.product');

            return response()->json([
                'success' => $success,
                'message' => $message,
                'cartItemsCount' => $this->cartService->getItemsCount(),
                'cartSubtotal' => (float) $cart->subtotal,
                'cartItems' => $cart->items->map(fn ($item) => [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'name' => $item->product->name,
                    'image' => $item->product->main_image ? asset('storage/' . $item->product->main_image) : null,
                    'quantity' => (int) $item->quantity,
                    'price' => (float) $item->product->final_price,
                    'lineTotal' => (float) ($item->product->final_price * $item->quantity),
                ]),
            ]);
        }

        return back()->with($success ? 'success' : 'error', $message);
    }

    public function clear(Request $request)
    {
        $this->cartService->clearCart();

        return $this->respond($request, true, 'تم إفراغ السلة بنجاح.');
    }
}