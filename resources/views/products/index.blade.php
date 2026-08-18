@extends('layouts.app')
@section('title', 'المنتجات - سوق')

@section('content')
<div class="container mx-auto px-4 py-10"
     x-data="productFilters('{{ route('products.index') }}')"
     x-init="init()">

    <h1 class="text-3xl font-bold text-primary mb-8">المنتجات</h1>

    <div class="grid md:grid-cols-4 gap-8">

        {{-- الفلاتر --}}
        <aside class="md:col-span-1">
            <div class="bg-white rounded-xl shadow p-6 space-y-6 sticky top-20">

                {{-- البحث بالاسم --}}
                <div>
                    <label class="block text-sm font-medium mb-2">بحث بالاسم</label>
                    <input type="text" x-model="filters.search" @input.debounce.400ms="applyFilters()"
                           placeholder="اسم المنتج..."
                           class="w-full border rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-accent focus:outline-none">
                </div>

                {{-- الفئة --}}
                <div>
                    <label class="block text-sm font-medium mb-2">الفئة</label>
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm cursor-pointer">
                            <input type="radio" name="category" value="" x-model="filters.category" @change="applyFilters()">
                            الكل
                        </label>
                        @foreach($categories as $category)
                        <label class="flex items-center gap-2 text-sm cursor-pointer">
                            <input type="radio" name="category" value="{{ $category->slug }}" x-model="filters.category" @change="applyFilters()">
                            {{ $category->name }}
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- السعر --}}
                <div>
                    <label class="block text-sm font-medium mb-2">نطاق السعر</label>
                    <div class="flex gap-2">
                        <input type="number" x-model="filters.min_price" @input.debounce.400ms="applyFilters()"
                               placeholder="من" class="w-1/2 border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-accent focus:outline-none">
                        <input type="number" x-model="filters.max_price" @input.debounce.400ms="applyFilters()"
                               placeholder="إلى" class="w-1/2 border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-accent focus:outline-none">
                    </div>
                </div>

                {{-- الترتيب --}}
                <div>
                    <label class="block text-sm font-medium mb-2">الترتيب</label>
                    <select x-model="filters.sort" @change="applyFilters()"
                            class="cursor-pointer w-full border rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-accent focus:outline-none">
                        <option value="latest">الأحدث</option>
                        <option value="price_asc">السعر: من الأقل للأعلى</option>
                        <option value="price_desc">السعر: من الأعلى للأقل</option>
                    </select>
                </div>

                <button type="button" @click="resetFilters()"
                        x-show="hasActiveFilters()"
                        class="cursor-pointer block w-full text-center text-sm text-gray-500 hover:text-accent">
                    مسح الفلاتر
                </button>
            </div>
        </aside>

        {{-- المنتجات --}}
        <div class="md:col-span-3 relative">
            <div x-show="loading" class="absolute inset-0 bg-white/60 z-10 flex items-center justify-center" style="display: none;">
                <svg class="w-8 h-8 animate-spin text-accent" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
            </div>

            <div id="products-grid">
                @include('products.partials.product-grid', ['products' => $products])
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function productFilters(baseUrl) {
        return {
            filters: {
                search: '{{ request('search') }}',
                category: '{{ request('category') }}',
                min_price: '{{ request('min_price') }}',
                max_price: '{{ request('max_price') }}',
                sort: '{{ request('sort', 'latest') }}',
            },
            loading: false,

            init() {
                // اعتراض النقر بس على روابط الـ Pagination (جوا <nav>)، مش كل الروابط
                document.getElementById('products-grid').addEventListener('click', (e) => {
                    const link = e.target.closest('nav[role="navigation"] a');
                    if (link && link.href) {
                        e.preventDefault();
                        this.fetchPage(link.href);
                    }
                });
            },

            hasActiveFilters() {
                return this.filters.search || this.filters.category || this.filters.min_price || this.filters.max_price || this.filters.sort !== 'latest';
            },

            buildQuery(extra = {}) {
                const params = new URLSearchParams();
                Object.entries({ ...this.filters, ...extra }).forEach(([key, value]) => {
                    if (value) params.append(key, value);
                });
                return params.toString();
            },

            applyFilters() {
                const query = this.buildQuery();
                const url = query ? `${baseUrl}?${query}` : baseUrl;
                this.fetchPage(url, true);
            },

            resetFilters() {
                this.filters = { search: '', category: '', min_price: '', max_price: '', sort: 'latest' };
                this.fetchPage(baseUrl, true);
            },

            async fetchPage(url, resetScroll = false) {
                this.loading = true;
                try {
                    const response = await fetch(url, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    });
                    const html = await response.text();
                    document.getElementById('products-grid').innerHTML = html;
                    window.history.pushState({}, '', url);

                    if (resetScroll) {
                        document.getElementById('products-grid').scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                } catch (e) {
                    console.error('Filter error:', e);
                } finally {
                    this.loading = false;
                }
            },
        };
    }
</script>
@endpush