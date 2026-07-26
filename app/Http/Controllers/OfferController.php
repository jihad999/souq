<?php

namespace App\Http\Controllers;

use App\Models\Product;

class OfferController extends Controller
{
    public function index()
    {
        $products = Product::onSale()
            ->where('is_active', true)
            ->paginate(12);

        return view('offers', compact('products'));
    }
}