@extends('layouts.app')
@section('title', 'سوق - متجرك الإلكتروني')

@section('content')

{{-- Hero Section --}}
<section class="bg-primary text-white">
    <div class="container mx-auto px-4 py-16 md:py-24 grid md:grid-cols-2 items-center gap-8">
        <div class="text-center md:text-right">
            <h1 class="text-3xl md:text-5xl font-extrabold mb-4">
                تسوّق بذكاء، <span class="text-accent">وفر أكتر</span>
            </h1>
            <p class="text-gray-300 text-lg mb-8 leading-8">
                منتجات متنوعة بأفضل الأسعار، توصيل سريع، وضمان جودة على كل قطعة.
            </p>
            <a href="{{ route('products.index') }}"
               class="inline-block bg-accent hover:bg-accent-dark text-white font-semibold px-8 py-3 rounded-lg transition">
                تصفح المنتجات
            </a>
        </div>
        <div class="hidden md:block">
            <div class="bg-primary-light rounded-2xl h-72 flex items-center justify-center text-gray-400">
                {{-- بانر / صورة ترويجية --}}
                صورة ترويجية
            </div>
        </div>
    </div>
</section>

{{-- الفئات --}}
@if($categories->isNotEmpty())
<section class="container mx-auto px-4 py-14">
    <h2 class="text-2xl font-bold text-primary mb-8 text-center">تسوّق حسب الفئة</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        @foreach($categories as $category)
        <a href="{{ route('products.index', ['category' => $category->slug]) }}"
           class="group bg-white rounded-xl shadow hover:shadow-lg transition p-6 text-center">
            <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-gray-100 flex items-center justify-center overflow-hidden">
                @if($category->image)
                    <img src="{{ asset('storage/' . $category->image) }}" class="w-full h-full object-cover">
                @else
                    <span class="text-2xl">📦</span>
                @endif
            </div>
            <span class="font-medium text-primary group-hover:text-accent transition">{{ $category->name }}</span>
        </a>
        @endforeach
    </div>
</section>
@endif

{{-- عروض خاصة --}}
@if($onSaleProducts->isNotEmpty())
<section class="bg-white py-14">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-primary">عروض خاصة</h2>
            <a href="{{ route('offers') }}" class="text-accent hover:underline font-medium">شوف الكل</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($onSaleProducts as $product)
                @include('components.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- منتجات مميزة --}}
<section class="container mx-auto px-4 py-14">
    <h2 class="text-2xl font-bold text-primary mb-8 text-center">منتجات مميزة</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        @forelse($featuredProducts as $product)
            @include('components.product-card', ['product' => $product])
        @empty
            <p class="col-span-full text-center text-gray-500">لا يوجد منتجات حاليًا.</p>
        @endforelse
    </div>
</section>

{{-- بانر ثقة --}}
<section class="bg-primary-light">
    <div class="container mx-auto px-4 py-10 grid grid-cols-2 md:grid-cols-4 gap-6 text-center text-white">
        <div>
            <div class="text-3xl mb-2">🚚</div>
            <p class="text-sm">توصيل سريع</p>
        </div>
        <div>
            <div class="text-3xl mb-2">✅</div>
            <p class="text-sm">ضمان الجودة</p>
        </div>
        <div>
            <div class="text-3xl mb-2">💳</div>
            <p class="text-sm">دفع آمن</p>
        </div>
        <div>
            <div class="text-3xl mb-2">↩️</div>
            <p class="text-sm">إمكانية الاسترجاع</p>
        </div>
    </div>
</section>

@endsection