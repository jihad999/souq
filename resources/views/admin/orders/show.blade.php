@extends('layouts.admin')
@section('title', 'تفاصيل الطلب - سوق')
@section('page-title', 'الطلب: ' . $order->order_number)

@section('content')

<div class="mb-6">
    <a href="{{ route('admin.orders.index') }}" class="cursor-pointer text-sm text-gray-500 hover:text-accent">← الرجوع لقائمة الطلبات</a>
</div>

<div class="grid md:grid-cols-3 gap-6">

    <div class="md:col-span-2 space-y-6">

        {{-- المنتجات --}}
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="p-6 border-b">
                <h2 class="font-bold text-primary">المنتجات المطلوبة</h2>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr class="text-right text-gray-500">
                        <th class="p-4">المنتج</th>
                        <th class="p-4">الكمية</th>
                        <th class="p-4">سعر الوحدة</th>
                        <th class="p-4">الإجمالي</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr class="border-t">
                        <td class="p-4">
                            <p class="font-medium text-primary">{{ $item->product_name }}</p>
                            @if($item->variant_label)
                                <p class="text-xs text-gray-400">{{ $item->variant_label }}</p>
                            @endif
                        </td>
                        <td class="p-4">{{ $item->quantity }}</td>
                        <td class="p-4">{{ number_format($item->unit_price, 2) }} ₪</td>
                        <td class="p-4 font-medium">{{ number_format($item->total_price, 2) }} ₪</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-4 border-t bg-gray-50 space-y-1">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">المجموع الفرعي</span>
                    <span>{{ number_format($order->subtotal, 2) }} ₪</span>
                </div>
                @if($order->discount_amount > 0)
                <div class="flex justify-between text-sm text-success">
                    <span>الخصم @if($order->promoCode)({{ $order->promoCode->code }})@endif</span>
                    <span>- {{ number_format($order->discount_amount, 2) }} ₪</span>
                </div>
                @endif
                <div class="flex justify-between font-bold text-primary text-base border-t pt-2">
                    <span>الإجمالي</span>
                    <span>{{ number_format($order->total, 2) }} ₪</span>
                </div>
            </div>
        </div>

        {{-- بيانات العميل --}}
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="font-bold text-primary mb-4">بيانات العميل</h2>
            <div class="grid md:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500 mb-1">الاسم</p>
                    <p class="font-medium">{{ $order->customer_name }}</p>
                </div>
                <div>
                    <p class="text-gray-500 mb-1">البريد الإلكتروني</p>
                    <p class="font-medium" dir="ltr">{{ $order->customer_email }}</p>
                </div>
                <div>
                    <p class="text-gray-500 mb-1">الهاتف</p>
                    <p class="font-medium" dir="ltr">{{ $order->customer_phone }}</p>
                </div>
                <div>
                    <p class="text-gray-500 mb-1">العنوان</p>
                    <p class="font-medium">{{ $order->shipping_address }}</p>
                </div>
            </div>

            @if($order->latitude && $order->longitude)
                <a href="https://www.google.com/maps?q={{ $order->latitude }},{{ $order->longitude }}" target="_blank"
                   class="cursor-pointer text-accent text-sm hover:underline block mt-4">
                    عرض موقع التوصيل على الخريطة
                </a>
            @endif
        </div>

        {{-- سجل المعاملات --}}
        @if($order->transactions->isNotEmpty())
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="font-bold text-primary mb-4">سجل المعاملات</h2>
            <div class="space-y-3">
                @foreach($order->transactions as $transaction)
                <div class="flex items-center justify-between text-sm border-b last:border-0 pb-3 last:pb-0">
                    <div>
                        <p class="font-medium">{{ $transaction->gateway }}</p>
                        <p class="text-gray-400 text-xs">{{ $transaction->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                    <span class="text-xs px-2 py-1 rounded {{ $transaction->status === 'success' ? 'bg-green-50 text-success' : 'bg-red-50 text-sale' }}">
                        {{ $transaction->status }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- الحالة والإجراءات --}}
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="font-bold text-primary mb-4">حالة الطلب</h2>
            <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="mb-4">
                @csrf
                @method('PATCH')
                <select name="status" onchange="this.form.submit()"
                        class="cursor-pointer w-full border rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-accent focus:outline-none">
                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>قيد المعالجة</option>
                    <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>تم الشحن</option>
                    <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>تم التسليم</option>
                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                </select>
            </form>

            <h2 class="font-bold text-primary mb-4">حالة الدفع</h2>
            <form action="{{ route('admin.orders.update-payment-status', $order) }}" method="POST">
                @csrf
                @method('PATCH')
                <select name="payment_status" onchange="this.form.submit()"
                        class="cursor-pointer w-full border rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-accent focus:outline-none">
                    <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>معلّق</option>
                    <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>مدفوع</option>
                    <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>فشل</option>
                    <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>مسترجع بالكامل</option>
                    <option value="partially_refunded" {{ $order->payment_status == 'partially_refunded' ? 'selected' : '' }}>مسترجع جزئيًا</option>
                </select>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="font-bold text-primary mb-3">معلومات إضافية</h2>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">رقم الطلب</span>
                    <span class="font-medium">{{ $order->order_number }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">تاريخ الطلب</span>
                    <span class="font-medium">{{ $order->created_at->format('Y-m-d H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">نوع العميل</span>
                    <span class="font-medium">{{ $order->user ? 'مسجل' : 'زائر' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection