@extends('layouts.app')
@section('title', 'تم استلام طلبك - سوق')

@section('content')
<div class="container mx-auto px-4 py-16">
    <div class="max-w-lg mx-auto bg-white rounded-2xl shadow p-5 text-center">
        <div class="w-16 h-16 mx-auto mb-6 rounded-full bg-green-50 flex items-center justify-center text-success">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>

        <h1 class="text-2xl font-bold text-primary mb-2">تم استلام طلبك بنجاح!</h1>
        <p class="text-gray-500 mb-6">رقم الطلب: <span class="font-semibold text-primary">{{ $order->order_number }}</span></p>

        <div class="bg-gray-50 rounded-xl p-5 text-right mb-6">
            <div class="flex justify-between text-sm mb-2">
                <span class="text-gray-500">الإجمالي</span>
                <span class="font-semibold text-primary">{{ number_format($order->total, 2) }} ₪</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">طريقة الدفع</span>
                <span class="font-semibold text-primary">
                    @switch($order->payment_method)
                        @case('cod') الدفع عند الاستلام @break
                        @case('stripe') Stripe @break
                        @case('paytabs') PayTabs @break
                        @case('hyperpay') HyperPay @break
                    @endswitch
                </span>
            </div>
        </div>

        <p class="text-sm text-gray-500 mb-6">تم إرسال تفاصيل الطلب إلى بريدك الإلكتروني.</p>

        <a href="{{ route('home') }}" class="cursor-pointer inline-block bg-accent hover:bg-accent-dark text-white font-semibold px-8 py-3 rounded-lg transition">
            العودة للرئيسية
        </a>
    </div>
</div>
@endsection