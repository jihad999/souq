@extends('layouts.app')
@section('title', 'سلة التسوق - سوق')

@section('content')
<div class="container mx-auto px-4 py-10">

    <h1 class="text-3xl font-bold text-primary mb-8">سلة التسوق</h1>

    @if(session('success'))
        <div class="bg-green-50 text-success border border-green-200 rounded-lg p-4 mb-6">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 text-sale border border-red-200 rounded-lg p-4 mb-6">{{ session('error') }}</div>
    @endif

    @if($cart->items->isEmpty())
        <div class="bg-white rounded-xl shadow p-16 text-center">
            <div class="text-5xl mb-4">🛒</div>
            <p class="text-gray-500 text-lg mb-6">سلتك فارغة حاليًا.</p>
            <a href="{{ route('products.index') }}" class="bg-accent hover:bg-accent-dark text-white font-semibold px-6 py-3 rounded-lg transition">
                تصفح المنتجات
            </a>
        </div>
    @else
    <div class="grid md:grid-cols-3 gap-8">

        {{-- عناصر السلة --}}
        <div class="md:col-span-2 space-y-4">
            @foreach($cart->items as $item)
            <div class="bg-white rounded-xl shadow p-4 flex items-center gap-4">
                <div class="w-20 h-20 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                    @if($item->product->main_image)
                        <img src="{{ asset('storage/' . $item->product->main_image) }}" class="w-full h-full object-cover">
                    @endif
                </div>

                <div class="flex-1">
                    <a href="{{ route('products.show', $item->product->slug) }}" class="font-semibold text-primary hover:text-accent">
                        {{ $item->product->name }}
                    </a>
                    <p class="text-sm text-gray-500 mt-1">
                        {{ number_format($item->product->final_price, 2) }} ₪ / قطعة
                    </p>
                </div>

                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center gap-2">
                    @csrf
                    @method('PATCH')
                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1"
                           onchange="this.form.submit()"
                           class="w-16 border rounded-lg px-2 py-1 text-center focus:ring-2 focus:ring-accent focus:outline-none">
                </form>

                <span class="font-bold text-primary w-24 text-left">
                    {{ number_format($item->product->final_price * $item->quantity, 2) }} ₪
                </span>

                <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sale hover:text-red-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </form>
            </div>
            @endforeach
        </div>

        {{-- ملخص الطلب --}}
        <div class="bg-white rounded-xl shadow p-6 h-fit sticky top-20">
            <h2 class="text-xl font-bold text-primary mb-6">ملخص الطلب</h2>

            <div class="space-y-3 mb-6">
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

            {{-- Promo Code --}}
            @if($promoCode)
                <form action="{{ route('cart.remove-promo') }}" method="POST" class="mb-4">
                    @csrf
                    <div class="flex items-center justify-between bg-green-50 border border-green-200 rounded-lg p-3">
                        <span class="text-sm text-success font-medium">كود "{{ $promoCode->code }}" مفعّل</span>
                        <button type="submit" class="text-sale text-sm hover:underline">إلغاء</button>
                    </div>
                </form>
            @else
                <form action="{{ route('cart.apply-promo') }}" method="POST" class="mb-6 flex gap-2">
                    @csrf
                    <input type="text" name="code" placeholder="كود الخصم"
                           class="flex-1 border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-accent focus:outline-none">
                    <button type="submit" class="bg-primary hover:bg-primary-light text-white px-4 rounded-lg text-sm font-medium">
                        تطبيق
                    </button>
                </form>
            @endif

            <a href="{{ route('checkout.index') }}"
               class="block text-center bg-accent hover:bg-accent-dark text-white font-semibold py-3 rounded-lg transition">
                إتمام الطلب
            </a>
        </div>
    </div>
    @endif
</div>
@endsection