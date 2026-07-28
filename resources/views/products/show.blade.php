@extends('layouts.app')
@section('title', $product->name . ' - سوق')

@section('content')
<div class="container mx-auto px-4 py-10">

    <div class="grid md:grid-cols-2 gap-10 bg-white rounded-xl shadow p-8">

        {{-- الصور --}}
        <div>
            <div class="h-80 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden mb-4">
                @if($product->main_image)
                    <img src="{{ asset('storage/' . $product->main_image) }}" class="w-full h-full object-cover">
                @else
                    <span class="text-gray-400">لا توجد صورة</span>
                @endif
            </div>
            @if($product->images->isNotEmpty())
            <div class="grid grid-cols-4 gap-3">
                @foreach($product->images as $image)
                <div class="h-20 bg-gray-100 rounded-lg overflow-hidden">
                    <img src="{{ asset('storage/' . $image->image) }}" class="w-full h-full object-cover">
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- التفاصيل --}}
        <div>
            <span class="text-sm text-accent font-medium">{{ $product->category->name }}</span>
            <h1 class="text-2xl md:text-3xl font-bold text-primary mt-2 mb-4">{{ $product->name }}</h1>

            <div class="flex items-center gap-3 mb-6">
                @if($product->hasActiveSale())
                    <span class="text-2xl font-bold text-sale">{{ number_format($product->sale_price, 2) }} ₪</span>
                    <span class="text-lg text-gray-400 line-through">{{ number_format($product->price, 2) }} ₪</span>
                    <span class="bg-sale text-white text-xs font-bold px-2 py-1 rounded">خصم</span>
                @else
                    <span class="text-2xl font-bold text-primary">{{ number_format($product->price, 2) }} ₪</span>
                @endif
            </div>

            <p class="text-gray-600 leading-7 mb-6">{{ $product->description }}</p>

            <p class="text-sm text-gray-500 mb-6">
                @if($product->stock > 0)
                    <span class="text-success">متوفر بالمخزون ({{ $product->stock }} قطعة)</span>
                @else
                    <span class="text-sale">غير متوفر حاليًا</span>
                @endif
            </p>

            @if($product->stock > 0)
                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="flex gap-4">
                    @csrf
                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}"
                        class="w-20 border rounded-lg px-3 py-2 text-center focus:ring-2 focus:ring-accent focus:outline-none">
                    <button type="submit"
                            class="cursor-pointer flex-1 bg-accent hover:bg-accent-dark text-white font-semibold py-3 rounded-lg transition">
                        أضف للسلة
                    </button>
                </form>
            @else
                <div x-data="{
                        email: '',
                        quantity: 1,
                        submitted: false,
                        loading: false,
                        error: '',
                        async submit() {
                            this.loading = true;
                            this.error = '';
                            try {
                                const response = await fetch('{{ route('stock-notifications.store') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                    },
                                    body: JSON.stringify({
                                        product_id: {{ $product->id }},
                                        email: this.email,
                                        quantity: this.quantity,
                                    }),
                                });
                                const data = await response.json();
                                if (data.success) {
                                    this.submitted = true;
                                } else {
                                    this.error = data.message || 'حدث خطأ، حاول مرة أخرى.';
                                }
                            } catch (e) {
                                this.error = 'حدث خطأ، حاول مرة أخرى.';
                            }
                            this.loading = false;
                        }
                    }" class="bg-gray-50 border border-gray-200 rounded-xl p-5">

                    <template x-if="!submitted">
                        <div>
                            <p class="text-sm font-medium text-primary mb-3">
                                هذا المنتج غير متوفر حاليًا. سجّل بريدك والكمية المطلوبة ونبلغك فور توفره.
                            </p>
                            <div class="flex gap-2">
                                <input type="number" x-model="quantity" min="1" max="100"
                                    class="w-20 border rounded-lg px-3 py-2 text-sm text-center focus:ring-2 focus:ring-accent focus:outline-none">
                                <input type="email" x-model="email" placeholder="بريدك الإلكتروني"
                                    class="flex-1 border rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-accent focus:outline-none">
                                <button @click="submit()" :disabled="loading || !email"
                                        class="cursor-pointer bg-primary hover:bg-accent disabled:opacity-50 disabled:cursor-not-allowed text-white text-sm font-medium px-5 rounded-lg transition">
                                    <span x-show="!loading">نبهني</span>
                                    <span x-show="loading">جاري الإرسال...</span>
                                </button>
                            </div>
                            <p x-show="error" x-text="error" class="text-sale text-xs mt-2"></p>
                        </div>
                    </template>

                    <template x-if="submitted">
                        <p class="text-success text-sm font-medium flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            تم تسجيل طلبك بنجاح، رح نبلغك فور توفر المنتج.
                        </p>
                    </template>
                </div>
            @endif
        </div>
    </div>

    {{-- منتجات ذات صلة --}}
    @if($relatedProducts->isNotEmpty())
    <div class="mt-12">
        <h2 class="text-2xl font-bold text-primary mb-6">منتجات ذات صلة</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($relatedProducts as $related)
                @include('components.product-card', ['product' => $related])
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection