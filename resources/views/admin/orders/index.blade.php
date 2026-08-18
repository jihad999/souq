@extends('layouts.admin')
@section('title', 'إدارة الطلبات - سوق')
@section('page-title', 'الطلبات')

@section('content')

{{-- الفلاتر --}}
<div class="bg-white rounded-xl shadow p-4 mb-6">
    <form method="GET" action="{{ route('admin.orders.index') }}" class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث برقم الطلب أو اسم العميل..."
               class="flex-1 min-w-[200px] border rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-accent focus:outline-none">

        <select name="status" class="border rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-accent focus:outline-none">
            <option value="">كل الحالات</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>قيد المعالجة</option>
            <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>تم الشحن</option>
            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>تم التسليم</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
        </select>

        <select name="payment_status" class="border rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-accent focus:outline-none">
            <option value="">كل حالات الدفع</option>
            <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>معلّق</option>
            <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>مدفوع</option>
            <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>فشل</option>
            <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>مسترجع</option>
        </select>

        <button type="submit" class="cursor-pointer bg-primary hover:bg-accent text-white text-sm font-medium px-5 rounded-lg transition">
            تطبيق
        </button>

        @if(request()->hasAny(['search', 'status', 'payment_status']))
            <a href="{{ route('admin.orders.index') }}" class="cursor-pointer text-sm text-gray-500 hover:text-accent self-center">مسح الفلاتر</a>
        @endif
    </form>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr class="text-right text-gray-500">
                <th class="p-4">رقم الطلب</th>
                <th class="p-4">العميل</th>
                <th class="p-4">الإجمالي</th>
                <th class="p-4">طريقة الدفع</th>
                <th class="p-4">حالة الدفع</th>
                <th class="p-4">حالة الطلب</th>
                <th class="p-4">التاريخ</th>
                <th class="p-4"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr class="border-t">
                <td class="p-4 font-medium text-primary">{{ $order->order_number }}</td>
                <td class="p-4">{{ $order->customer_name }}</td>
                <td class="p-4">{{ number_format($order->total, 2) }} ₪</td>
                <td class="p-4">
                    @switch($order->payment_method)
                        @case('cod') COD @break
                        @case('stripe') Stripe @break
                        @case('paytabs') PayTabs @break
                    @endswitch
                </td>
                <td class="p-4">
                    @php
                        $paymentColors = [
                            'pending' => 'bg-yellow-50 text-yellow-600',
                            'paid' => 'bg-green-50 text-success',
                            'failed' => 'bg-red-50 text-sale',
                            'refunded' => 'bg-gray-100 text-gray-500',
                            'partially_refunded' => 'bg-orange-50 text-accent',
                        ];
                    @endphp
                    <span class="text-xs {{ $paymentColors[$order->payment_status] ?? '' }} px-2 py-1 rounded">
                        {{ $order->payment_status }}
                    </span>
                </td>
                <td class="p-4">
                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">{{ $order->status }}</span>
                </td>
                <td class="p-4 text-gray-500">{{ $order->created_at->format('Y-m-d') }}</td>
                <td class="p-4">
                    <a href="{{ route('admin.orders.show', $order) }}" class="cursor-pointer text-accent hover:underline">عرض</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="p-8 text-center text-gray-500">لا يوجد طلبات مطابقة.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $orders->links() }}
</div>

@endsection