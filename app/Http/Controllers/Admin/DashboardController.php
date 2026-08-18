<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Order;
use App\Models\Partner;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total'),
            'total_products' => Product::count(),
            'low_stock_products' => Product::where('is_active', true)
                ->where('stock', '<=', 5)
                ->whereDoesntHave('variants')
                ->count(),
            'pending_partners' => Partner::where('status', 'pending')->count(),
            'unread_messages' => ContactMessage::where('is_read', false)->count(),
        ];

        $recentOrders = Order::with('items')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }
}