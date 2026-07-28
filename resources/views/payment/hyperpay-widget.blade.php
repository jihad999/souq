@extends('layouts.app')
@section('title', 'إتمام الدفع - سوق')

@section('content')
<div class="container mx-auto px-4 py-16">
    <div class="max-w-lg mx-auto bg-white rounded-2xl shadow p-8">
        <h1 class="text-xl font-bold text-primary mb-2 text-center">إتمام الدفع</h1>
        <p class="text-gray-500 text-sm text-center mb-6">
            المبلغ المطلوب: <span class="font-semibold text-primary">{{ number_format($order->total, 2) }} ₪</span>
        </p>

        <form action="{{ config('services.hyperpay.mode') === 'live' ? 'https://oppwa.com' : 'https://eu-test.oppwa.com' }}/v1/checkouts/{{ $checkoutId }}/payment"
              class="paymentWidgets"
              data-brands="VISA MASTER AMEX">
        </form>
    </div>
</div>

<script src="{{ (config('services.hyperpay.mode') === 'live' ? 'https://oppwa.com' : 'https://eu-test.oppwa.com') }}/v1/paymentWidgets.js?checkoutId={{ $checkoutId }}"></script>

<script>
    var wpwlOptions = {
        onReady: function () {
            // ممكن تخصيص شكل الفورم هون لاحقًا لو حبيت
        }
    };
</script>
@endsection