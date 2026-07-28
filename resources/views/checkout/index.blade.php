@extends('layouts.app')
@section('title', 'إتمام الطلب - سوق')

@section('content')
<div class="container mx-auto px-4 py-10">

    <h1 class="text-3xl font-bold text-primary mb-8">إتمام الطلب</h1>

    <form action="{{ route('checkout.store') }}" method="POST" class="grid md:grid-cols-3 gap-8">
        @csrf

        {{-- بيانات الشحن + الدفع --}}
        <div class="md:col-span-2 space-y-6">

            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-lg font-bold text-primary mb-4">بيانات الشحن</h2>

                <div class="grid md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">الاسم الكامل</label>
                        <input type="text" name="customer_name" value="{{ old('customer_name') }}"
                               class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none" required>
                        @error('customer_name') <p class="text-sale text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">البريد الإلكتروني</label>
                        <input type="email" name="customer_email" value="{{ old('customer_email') }}"
                               class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none" required>
                        @error('customer_email') <p class="text-sale text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">رقم الهاتف</label>
                    <input type="text" name="customer_phone" value="{{ old('customer_phone') }}"
                           class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none" required>
                    @error('customer_phone') <p class="text-sale text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">عنوان الشحن الكامل</label>
                    <textarea name="shipping_address" rows="3"
                              class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none" required>{{ old('shipping_address') }}</textarea>
                    @error('shipping_address') <p class="text-sale text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- طريقة الدفع --}}
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-lg font-bold text-primary mb-4">طريقة الدفع</h2>

                <div class="space-y-3">
                    <label class="flex items-center gap-3 border rounded-lg p-4 cursor-pointer hover:border-accent transition has-[:checked]:border-accent has-[:checked]:bg-accent/5">
                        <input type="radio" name="payment_method" value="cod" {{ old('payment_method', 'cod') == 'cod' ? 'checked' : '' }} class="cursor-pointer">
                        <div>
                            <span class="font-medium text-primary block">الدفع عند الاستلام</span>
                            <span class="text-xs text-gray-500">ادفع نقدًا عند وصول الطلب</span>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 border rounded-lg p-4 cursor-pointer hover:border-accent transition has-[:checked]:border-accent has-[:checked]:bg-accent/5">
                        <input type="radio" name="payment_method" value="stripe" {{ old('payment_method') == 'stripe' ? 'checked' : '' }} class="cursor-pointer">
                        <div>
                            <span class="font-medium text-primary block">بطاقة ائتمان (Stripe)</span>
                            <span class="text-xs text-gray-500">فيزا / ماستركارد</span>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 border rounded-lg p-4 cursor-pointer hover:border-accent transition has-[:checked]:border-accent has-[:checked]:bg-accent/5">
                        <input type="radio" name="payment_method" value="paytabs" {{ old('payment_method') == 'paytabs' ? 'checked' : '' }} class="cursor-pointer">
                        <div>
                            <span class="font-medium text-primary block">PayTabs</span>
                            <span class="text-xs text-gray-500">بوابة دفع عربية</span>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 border rounded-lg p-4 cursor-pointer hover:border-accent transition has-[:checked]:border-accent has-[:checked]:bg-accent/5">
                        <input type="radio" name="payment_method" value="hyperpay" {{ old('payment_method') == 'hyperpay' ? 'checked' : '' }} class="cursor-pointer">
                        <div>
                            <span class="font-medium text-primary block">HyperPay</span>
                            <span class="text-xs text-gray-500">بوابة دفع عربية</span>
                        </div>
                    </label>

                    @error('payment_method') <p class="text-sale text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- ملخص الطلب --}}
        <div class="bg-white rounded-xl shadow p-6 h-fit sticky top-20">
            <h2 class="text-xl font-bold text-primary mb-6">ملخص الطلب</h2>

            <div class="space-y-3 mb-6 max-h-64 overflow-y-auto">
                @foreach($cart->items as $item)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">{{ $item->product->name }} × {{ $item->quantity }}</span>
                    <span class="font-medium">{{ number_format($item->product->final_price * $item->quantity, 2) }} ₪</span>
                </div>
                @endforeach
            </div>

            <div class="space-y-3 mb-6 border-t pt-4">
                <div class="flex justify-between text-gray-600">
                    <span>المجموع الفرعي</span>
                    <span>{{ number_format($cart->subtotal, 2) }} ₪</span>
                </div>
                @if($discount > 0)
                <div class="flex justify-between text-success">
                    <span>الخصم ({{ $promoCode->code }})</span>
                    <span>- {{ number_format($discount, 2) }} ₪</span>
                </div>
                @endif
                <div class="flex justify-between text-primary font-bold text-lg border-t pt-3">
                    <span>الإجمالي</span>
                    <span>{{ number_format($total, 2) }} ₪</span>
                </div>
            </div>

            <button type="submit"
                    class="cursor-pointer w-full bg-accent hover:bg-accent-dark text-white font-semibold py-3 rounded-lg transition">
                تأكيد الطلب
            </button>
        </div>
    </form>
</div>
@endsection