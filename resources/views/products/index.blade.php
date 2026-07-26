@extends('layouts.app')
@section('title', 'المنتجات - سوق')

@section('content')
<div class="container mx-auto px-4 py-10">

    <h1 class="text-3xl font-bold text-primary mb-8">المنتجات</h1>

    <div class="grid md:grid-cols-4 gap-8">

        {{-- الفلاتر --}}
        <aside class="md:col-span-1">
            <form method="GET" action="{{ route('products.index') }}" class="bg-white rounded-xl shadow p-6 space-y-6 sticky top-20">

                {{-- البحث بالاسم --}}
                <div>
                    <label class="block text-sm font-medium mb-2">بحث بالاسم</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="اسم المنتج..."
                           class="w-full border rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-accent focus:outline-none">
                </div>

                {{-- الفئة --}}
                <div>
                    <label class="block text-sm font-medium mb-2">الفئة</label>
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm">
                            <input type="radio" name="category" value="" {{ !request('category') ? 'checked' : '' }}
                                   onchange="this.form.submit()">
                            الكل
                        </label>
                        @foreach($categories as $category)
                        <label class="flex items-center gap-2 text-sm">
                            <input type="radio" name="category" value="{{ $category->slug }}"
                                   {{ request('category') == $category->slug ? 'checked' : '' }}
                                   onchange="this.form.submit()">
                            {{ $category->name }}
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- السعر --}}
                <div>
                    <label class="block text-sm font-medium mb-2">نطاق السعر</label>
                    <div class="flex gap-2">
                        <input type="number" name="min_price" value="{{ request('min_price') }}"
                               placeholder="من" class="w-1/2 border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-accent focus:outline-none">
                        <input type="number" name="max_price" value="{{ request('max_price') }}"
                               placeholder="إلى" class="w-1/2 border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-accent focus:outline-none">
                    </div>
                </div>

                {{-- الترتيب --}}
                <div>
                    <label class="block text-sm font-medium mb-2">الترتيب</label>
                    <select name="sort" onchange="this.form.submit()"
                            class="w-full border rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-accent focus:outline-none">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>الأحدث</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>السعر: من الأقل للأعلى</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>السعر: من الأعلى للأقل</option>
                    </select>
                </div>

                <button type="submit" class="w-full bg-accent hover:bg-accent-dark text-white font-medium py-2 rounded-lg transition">
                    تطبيق الفلاتر
                </button>

                @if(request()->hasAny(['search', 'category', 'min_price', 'max_price', 'sort']))
                    <a href="{{ route('products.index') }}" class="block text-center text-sm text-gray-500 hover:text-accent">
                        مسح الفلاتر
                    </a>
                @endif
            </form>
        </aside>

        {{-- المنتجات --}}
        <div class="md:col-span-3">
            @if($products->isEmpty())
                <div class="bg-white rounded-xl shadow p-12 text-center text-gray-500">
                    ما في منتجات مطابقة لبحثك.
                </div>
            @else
                <div class="grid grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    @foreach($products as $product)
                        @include('components.product-card', ['product' => $product])
                    @endforeach
                </div>

                <div>
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection