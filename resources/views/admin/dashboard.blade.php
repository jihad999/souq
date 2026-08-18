@extends('layouts.admin')
@section('title', 'لوحة التحكم - سوق')
@section('page-title', 'نظرة عامة')

@section('content')

<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-xl shadow p-5">
        <p class="text-sm text-gray-500 mb-1">إجمالي الطلبات</p>
        <p class="text-2xl font-bold text-primary">{{ $stats['total_orders'] }}</p>
    </div>
    <div class="bg-white rounded-xl shadow p-5">
        <p class="text-sm text-gray-500 mb-1">طلبات معلّقة</p>
        <p class="text-2xl font-bold text-accent">{{ $stats['pending_orders'] }}</p>
    </div>
    <div class="bg-white rounded-xl shadow p-5">
        <p class="text-sm text-gray-500 mb-1">إجمالي الإيرادات</p>
        <p class="text-2xl font-bold text-success">{{ number_format($stats['total_revenue'], 2) }} ₪</p>
    </div>
    <div class="bg-white rounded-xl shadow p-5">
        <p class="text-sm text-gray-500 mb-1">عدد المنتجات</p>
        <p class="text-2xl font-bold text-primary">{{ $stats['total_products'] }}</p>
    </div>
    <div class="bg-white rounded-xl shadow p-5">
        <p class="text-sm text-gray-500 mb-1">مخزون منخفض</p>
        <p class="text-2xl font-bold text-sale">{{ $stats['low_stock_products'] }}</p>
    </div>
    <div class="bg-white rounded-xl shadow p-5">
        <p class="text-sm text-gray-500 mb-1">طلبات شراكة معلّقة</p>
        <p class="text-2xl font-bold text-accent">{{ $stats['pending_partners'] }}</p>
    </div>
    <div class="bg-white rounded-xl shadow p-5">
        <p class="text-sm text-gray-500 mb-1">رسائل غير مقروءة</p>
        <p class="text-2xl font-bold text-accent">{{ $stats['unread_messages'] }}</p>
    </div>
</div>

<div class="bg-white rounded-xl shadow p-6">
    <h2 class="text-lg font-bold text-primary mb-4">أحدث الطلبات</h2>

    @if($recentOrders->isEmpty())
        <p class="text-gray-500 text-sm">لا يوجد طلبات بعد.</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b text-right text-gray-500">
                        <th class="pb-3">رقم الطلب</th>
                        <th class="pb-3">العميل</th>
                        <th class="pb-3">الإجمالي</th>
                        <th class="pb-3">الحالة</th>
                        <th class="pb-3">التاريخ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                    <tr class="border-b last:border-0">
                        <td class="py-3 font-medium text-primary">{{ $order->order_number }}</td>
                        <td class="py-3">{{ $order->customer_name }}</td>
                        <td class="py-3">{{ number_format($order->total, 2) }} ₪</td>
                        <td class="py-3">
                            <span class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $order->status }}</span>
                        </td>
                        <td class="py-3 text-gray-500">{{ $order->created_at->format('Y-m-d') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection