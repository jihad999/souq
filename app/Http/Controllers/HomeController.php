<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::query()
            ->where('is_active', true)
            ->withCount('variants')
            ->latest()
            ->take(8)
            ->get();

        $onSaleProducts = Product::onSale()
            ->where('is_active', true)
            ->withCount('variants')
            ->take(4)
            ->get();

        $categories = Category::where('is_active', true)->get();

        return view('home', compact('featuredProducts', 'onSaleProducts', 'categories'));
    }
}
