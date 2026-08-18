<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CartService
{
    public function getCurrentCart(): Cart
    {
        if (Auth::check()) {
            return Cart::firstOrCreate(['user_id' => Auth::id()]);
        }

        $sessionId = session()->get('cart_session_id');

        if (! $sessionId) {
            $sessionId = Str::uuid();
            session()->put('cart_session_id', $sessionId);
        }

        return Cart::firstOrCreate(['session_id' => $sessionId]);
    }

    public function addProduct(Product $product, int $quantity = 1, ?int $variantId = null): void
    {
        $cart = $this->getCurrentCart();

        $item = $cart->items()
            ->where('product_id', $product->id)
            ->where('product_variant_id', $variantId)
            ->first();

        if ($item) {
            $item->increment('quantity', $quantity);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'product_variant_id' => $variantId,
                'quantity' => $quantity,
            ]);
        }
    }

    public function updateQuantity(int $cartItemId, int $quantity): void
    {
        $cart = $this->getCurrentCart();
        $item = $cart->items()->findOrFail($cartItemId);

        if ($quantity <= 0) {
            $item->delete();
            return;
        }

        $item->update(['quantity' => $quantity]);
    }

    public function removeItem(int $cartItemId): void
    {
        $cart = $this->getCurrentCart();
        $cart->items()->findOrFail($cartItemId)->delete();
    }

    public function getItemsCount(): int
    {
        return $this->getCurrentCart()->items()->sum('quantity');
    }

    public function getCartItems()
    {
        return $this->getCurrentCart()->items()->with('product')->get();
    }

    public function hasProduct(int $productId): bool
    {
        return $this->getCurrentCart()->items()->where('product_id', $productId)->exists();
    }

    public function getSubtotal(): float
    {
        return $this->getCurrentCart()->subtotal;
    }

    public function clearCart(): void
    {
        $this->getCurrentCart()->items()->delete();
    }
}