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

            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="flex gap-4">
                @csrf
                <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}"
                       class="w-20 border rounded-lg px-3 py-2 text-center focus:ring-2 focus:ring-accent focus:outline-none">
                <button type="submit" {{ $product->stock <= 0 ? 'disabled' : '' }}
                        class="flex-1 bg-accent hover:bg-accent-dark disabled:bg-gray-300 text-white font-semibold py-3 rounded-lg transition">
                    أضف للسلة
                </button>
            </form>
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