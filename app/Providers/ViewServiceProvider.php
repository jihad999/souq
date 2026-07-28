<?php

namespace App\Providers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            $cartService = app(CartService::class);

            $view->with('showOffersLink', Product::onSale()->where('is_active', true)->exists());
            $view->with('cartItemsCount', $cartService->getItemsCount());
            $view->with('cartItems', $cartService->getCartItems());
            $view->with('cartSubtotal', $cartService->getSubtotal());
        });

        View::composer('components.product-card', function ($view) {
            $view->with('cartProductIds', app(CartService::class)->getCartItems()->pluck('product_id'));
        });
    }
}