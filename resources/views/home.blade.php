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
<section class="bg-white border-t border-gray-100">
    <div class="container mx-auto px-4 py-14">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">

            <div class="flex flex-col items-center text-center gap-3 p-6 rounded-2xl hover:bg-gray-50 transition group">
                <div class="w-14 h-14 rounded-full bg-accent/10 flex items-center justify-center group-hover:bg-accent group-hover:text-white text-accent transition">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M3 7h11v8H3V7zM14 10h4l3 3v2h-7v-5zM6.5 19a1.5 1.5 0 100-3 1.5 1.5 0 000 3zM17.5 19a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-primary text-sm mb-1">توصيل سريع</h3>
                    <p class="text-xs text-gray-500">لجميع المناطق</p>
                </div>
            </div>

            <div class="flex flex-col items-center text-center gap-3 p-6 rounded-2xl hover:bg-gray-50 transition group">
                <div class="w-14 h-14 rounded-full bg-accent/10 flex items-center justify-center group-hover:bg-accent group-hover:text-white text-accent transition">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-primary text-sm mb-1">ضمان الجودة</h3>
                    <p class="text-xs text-gray-500">منتجات موثوقة</p>
                </div>
            </div>

            <div class="flex flex-col items-center text-center gap-3 p-6 rounded-2xl hover:bg-gray-50 transition group">
                <div class="w-14 h-14 rounded-full bg-accent/10 flex items-center justify-center group-hover:bg-accent group-hover:text-white text-accent transition">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M3 10h18M7 15h2m2 0h6M5 6h14a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-primary text-sm mb-1">دفع آمن</h3>
                    <p class="text-xs text-gray-500">طرق دفع متعددة</p>
                </div>
            </div>

            <div class="flex flex-col items-center text-center gap-3 p-6 rounded-2xl hover:bg-gray-50 transition group">
                <div class="w-14 h-14 rounded-full bg-accent/10 flex items-center justify-center group-hover:bg-accent group-hover:text-white text-accent transition">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M3 10h10a5 5 0 010 10H9m-6-10l4-4m-4 4l4 4"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-primary text-sm mb-1">إمكانية الاسترجاع</h3>
                    <p class="text-xs text-gray-500">خلال 14 يوم</p>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection