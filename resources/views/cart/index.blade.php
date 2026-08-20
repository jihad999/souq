@extends('layouts.app')
@section('title', 'سلة التسوق - سوق')

@section('content')
<div class="container mx-auto px-4 py-10" x-data="{
    promoCode: null,
    promoType: null,
    promoValue: 0,
    minOrderAmount: 0,
    discount: 0,
    total: 0,
    init() {
        this.promoCode = {{ Illuminate\Support\Js::from($promoCode?->code) }};
        this.promoType = {{ Illuminate\Support\Js::from($promoCode?->type) }};
        this.promoValue = {{ (float) ($promoCode?->value ?? 0) }};
        this.minOrderAmount = {{ (float) ($promoCode?->min_order_amount ?? 0) }};
        this.$watch('$store.cart.subtotal', () => this.recalculate());
        this.recalculate();
    },
    recalculate() {
        if (!this.promoCode || $store.cart.subtotal < this.minOrderAmount) {
            this.discount = 0;
        } else if (this.promoType === 'percentage') {
            this.discount = $store.cart.subtotal * (this.promoValue / 100);
        } else if (this.promoType === 'fixed') {
            this.discount = Math.min(this.promoValue, $store.cart.subtotal);
        } else {
            this.discount = 0;
        }
        this.total = $store.cart.subtotal - this.discount;
    }
}">

    <h1 class="text-3xl font-bold text-primary mb-8">سلة التسوق</h1>

    <template x-if="$store.cart.items.length === 0">
        <div class="bg-white rounded-xl shadow p-16 text-center">
            <div class="text-5xl mb-4">🛒</div>
            <p class="text-gray-500 text-lg mb-6">سلتك فارغة حاليًا.</p>
            <a href="{{ route('products.index') }}" class="bg-accent hover:bg-accent-dark text-white font-semibold px-6 py-3 rounded-lg transition">
                تصفح المنتجات
            </a>
        </div>
    </template>

    <template x-if="$store.cart.items.length > 0">
    <div class="grid md:grid-cols-3 gap-8">

        <div class="md:col-span-2 space-y-4">
            <div class="flex justify-end">
                <button class="text-sale text-sm font-medium hover:underline flex items-center gap-1 cursor-pointer" @click="$store.confirm.show('هل أنت متأكد من إفراغ السلة بالكامل؟', () => $store.cart.clearCart())">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    إفراغ السلة
                </button>
            </div>
            <template x-for="item in $store.cart.items" :key="item.id">
                <div class="bg-white rounded-xl shadow p-4 flex items-center gap-4">
                    <div class="w-20 h-20 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                        <img :src="item.image" x-show="item.image" class="w-full h-full object-cover">
                    </div>

                    <div class="flex-1">
                        <p class="font-semibold text-primary" x-text="item.name"></p>
                        <p x-show="item.variant_label" x-text="item.variant_label" class="text-xs text-gray-400 mt-0.5"></p>
                        <p class="text-sm text-gray-500 mt-1">
                            <span x-text="item.price.toFixed(2)"></span> ₪ / قطعة
                        </p>
                    </div>

                    <input type="number" :value="item.quantity" min="1"
                        @change="$store.cart.updateQuantity(item.id, $event.target.value)"
                        class="w-16 border rounded-lg px-2 py-1 text-center focus:ring-2 focus:ring-accent focus:outline-none">

                    <div class="w-32 text-left">
                        <template x-if="discount > 0">
                            <div>
                                <span class="text-gray-400 text-xs line-through" x-text="item.lineTotal.toFixed(2) + ' ₪'"></span>
                                <span class="font-bold text-sale"
                                    x-text="(item.lineTotal * (1 - discount / $store.cart.subtotal)).toFixed(2) + ' ₪'"></span>
                            </div>
                        </template>
                        <template x-if="discount <= 0">
                            <span class="font-bold text-primary" x-text="item.lineTotal.toFixed(2) + ' ₪'"></span>
                        </template>
                    </div>

                    <button @click="$store.confirm.show('هل أنت متأكد من حذف هذا المنتج من السلة؟', () => $store.cart.remove(item.id))"
                            class="cursor-pointer text-sale hover:text-red-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </template>
        </div>

        <div class="bg-white rounded-xl shadow p-6 h-fit sticky top-20">
            <h2 class="text-xl font-bold text-primary mb-6">ملخص الطلب</h2>

            <div class="space-y-3 mb-6">
                <div class="flex justify-between text-gray-600">
                    <span>المجموع الفرعي</span>
                    <span x-text="$store.cart.subtotal.toFixed(2) + ' ₪'"></span>
                </div>
                <div class="flex justify-between text-success" x-show="discount > 0">
                    <span>الخصم (<span x-text="promoCode"></span>)</span>
                    <span x-text="'- ' + discount.toFixed(2) + ' ₪'"></span>
                </div>
                <div class="flex justify-between text-primary font-bold text-lg border-t pt-3">
                    <span>الإجمالي</span>
                    <span x-text="total.toFixed(2) + ' ₪'"></span>
                </div>
            </div>

            @if($promoCode)
                <form action="{{ route('cart.remove-promo') }}" method="POST" class="mb-4">
                    @csrf
                    <div class="flex items-center justify-between bg-green-50 border border-green-200 rounded-lg p-3">
                        <span class="text-sm text-success font-medium">كود "{{ $promoCode->code }}" مفعّل</span>
                        <button type="submit" class="text-sale text-sm cursor-pointer hover:underline">إلغاء</button>
                    </div>
                </form>
            @else
                <form action="{{ route('cart.apply-promo') }}" method="POST" class="mb-6 flex gap-2">
                    @csrf
                    <input type="text" name="code" placeholder="كود الخصم"
                           class="flex-1 border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-accent focus:outline-none">
                    <button type="submit" class="bg-primary cursor-pointer hover:bg-primary-light text-white px-4 rounded-lg text-sm font-medium">
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
    </template>
</div>
@endsection