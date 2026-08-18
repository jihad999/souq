<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'سوق - متجرك الإلكتروني')</title>

    @php
        $cartItemsForJs = ($cartItems ?? collect())->map(function ($item) {
            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'name' => $item->product->name,
                'image' => $item->product->main_image ? asset('storage/' . $item->product->main_image) : null,
                'quantity' => (int) $item->quantity,
                'price' => (float) ($item->variant ? $item->variant->final_price : $item->product->final_price),
                'lineTotal' => (float) ($item->product->final_price * $item->quantity),
                'variant_id' => $item->product_variant_id,
                'variant_label' => $item->variant?->label,
            ];
        });
    @endphp

    <script>
        window.initialCartCount = {{ $cartItemsCount ?? 0 }};
        window.initialCartSubtotal = {{ (float) ($cartSubtotal ?? 0) }};
        window.initialCartItems = @json($cartItemsForJs);
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-primary overflow-x-hidden">

    {{-- Header --}}
    <header class="bg-primary text-white sticky top-0 z-50 shadow-md">
        <div class="container mx-auto px-4 flex items-center justify-between h-16">
            <a href="{{ route('home') }}" class="text-2xl font-bold text-accent">سوق</a>

            <nav class="hidden md:flex items-center gap-8">
                <a href="{{ route('home') }}" class="hover:text-accent transition">الرئيسية</a>
                <a href="{{ route('products.index') }}" class="hover:text-accent transition">المنتجات</a>
                @if($showOffersLink ?? true)
                <a href="{{ route('offers') }}" class="hover:text-accent transition">العروض</a>
                @endif
                <a href="{{ route('articles.index') }}" class="hover:text-accent transition">المدونة</a>
                <a href="{{ route('about') }}" class="hover:text-accent transition">من نحن</a>
                <a href="{{ route('contact') }}" class="hover:text-accent transition">تواصل معنا</a>
            </nav>

            <div class="flex items-center gap-4">

                {{-- Cart Dropdown --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false" class="relative cursor-pointer">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span class="absolute -top-2 -left-2 bg-accent text-xs w-5 h-5 rounded-full flex items-center justify-center"
                            x-text="$store.cart.count"></span>
                    </button>

                    <div x-show="open"
                        x-transition
                        @click.outside="open = false"
                        class="absolute left-0 mt-3 w-80 bg-white text-primary rounded-xl shadow-2xl z-50 overflow-hidden"
                        style="display: none;">

                        <div class="p-4 border-b">
                            <h3 class="font-semibold">سلة التسوق</h3>
                        </div>

                        <template x-if="$store.cart.items.length === 0">
                            <div class="p-8 text-center text-gray-400 text-sm">السلة فارغة حاليًا</div>
                        </template>

                        <template x-if="$store.cart.items.length > 0">
                            <div>
                                <div class="max-h-80 overflow-y-auto divide-y">
                                    <template x-for="item in $store.cart.items" :key="item.id">
                                        <div class="flex items-center gap-3 p-3">
                                            <div class="w-14 h-14 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                                                <img :src="item.image" x-show="item.image" class="w-full h-full object-cover">
                                            </div>

                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium truncate" x-text="item.name"></p>
                                                <p x-show="item.variant_label" x-text="item.variant_label" class="text-xs text-gray-400"></p>
                                                <p class="text-xs text-gray-500">
                                                    <span x-text="item.quantity"></span> × <span x-text="item.price.toFixed(2)"></span> ₪
                                                </p>
                                            </div>

                                            <button class="text-gray-400 hover:text-sale transition cursor-pointer" @click="$store.confirm.show('هل أنت متأكد من حذف هذا المنتج من السلة؟', () => $store.cart.remove(item.id))">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </template>
                                </div>

                                <div class="p-4 border-t bg-gray-50">
                                    <div class="flex justify-between text-sm font-semibold mb-3">
                                        <span>المجموع</span>
                                        <span x-text="$store.cart.subtotal.toFixed(2) + ' ₪'"></span>
                                    </div>
                                    <a href="{{ route('cart.index') }}"
                                    class="block text-center bg-accent hover:bg-accent-dark text-white text-sm font-medium py-2.5 rounded-lg transition mb-2">
                                        عرض السلة
                                    </a>
                                    <button class="block w-full text-center text-sale hover:underline text-xs font-medium py-1 cursor-pointer" @click="$store.confirm.show('هل أنت متأكد من إفراغ السلة بالكامل؟', () => $store.cart.clearCart())">
                                        إفراغ السلة
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <button class="md:hidden" id="mobile-menu-btn cursor-pointer">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div id="mobile-menu" class="hidden md:hidden bg-primary-light px-4 pb-4">
            <a href="{{ route('home') }}" class="block py-2 hover:text-accent">الرئيسية</a>
            <a href="{{ route('products.index') }}" class="block py-2 hover:text-accent">المنتجات</a>
            @if($showOffersLink ?? true)
            <a href="{{ route('offers') }}" class="block py-2 hover:text-accent">العروض</a>
            @endif
            <a href="{{ route('articles.index') }}" class="block py-2 hover:text-accent">المدونة</a>
            <a href="{{ route('about') }}" class="block py-2 hover:text-accent">من نحن</a>
            <a href="{{ route('contact') }}" class="block py-2 hover:text-accent">تواصل معنا</a>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    {{-- Confirm Modal --}}
    <div x-data
        x-show="$store.confirm.open"
        x-transition.opacity
        class="fixed inset-0 bg-black/50 z-[100] flex items-center justify-center px-4"
        style="display: none;">
        <div x-show="$store.confirm.open"
            x-transition
            @click.outside="$store.confirm.cancel()"
            class="bg-white rounded-2xl shadow-2xl max-w-sm w-full p-6 text-center">

            <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-red-50 flex items-center justify-center text-sale">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>

            <p class="text-primary font-medium mb-6" x-text="$store.confirm.message"></p>

            <div class="flex gap-3">
                <button @click="$store.confirm.cancel()"
                        class="flex-1 bg-gray-100 hover:bg-gray-200 text-primary font-medium py-2.5 rounded-lg transition cursor-pointer">
                    إلغاء
                </button>
                <button @click="$store.confirm.confirmed()"
                        class="flex-1 bg-sale hover:bg-red-700 text-white font-medium py-2.5 rounded-lg transition cursor-pointer">
                    تأكيد الحذف
                </button>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <footer class="bg-primary text-gray-300">
        <div class="container mx-auto px-4 py-10 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <h3 class="text-accent text-xl font-bold mb-3">سوق</h3>
                <p class="text-sm leading-6">وجهتك الأولى للتسوق الإلكتروني بمنتجات متنوعة وجودة موثوقة.</p>
            </div>
            <div>
                <h4 class="font-semibold text-white mb-3">روابط سريعة</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('about') }}" class="hover:text-accent">من نحن</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-accent">تواصل معنا</a></li>
                    <li><a href="{{ route('products.index') }}" class="hover:text-accent">المنتجات</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold text-white mb-3">السياسات</h4>
                <ul class="space-y-2 text-sm">
                    @foreach(\App\Models\Policy::where('is_active', true)->get() as $policy)
                        <li><a href="{{ route('policies.show', $policy->slug) }}" class="hover:text-accent">{{ $policy->title }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h4 class="font-semibold text-white mb-3">تابعنا</h4>
                <div class="flex gap-3">
                    {{-- أيقونات السوشال ميديا --}}
                </div>
            </div>
        </div>
        <div class="border-t border-gray-700 text-center py-4 text-sm">
            &copy; {{ date('Y') }} سوق. جميع الحقوق محفوظة.
        </div>
    </footer>

    <script>
        document.getElementById('mobile-menu-btn')?.addEventListener('click', () => {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    </script>
    @stack('scripts')
</body>
</html>