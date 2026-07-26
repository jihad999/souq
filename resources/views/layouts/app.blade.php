<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'سوق - متجرك الإلكتروني')</title>
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
                <a href="{{ route('cart.index') }}" class="relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span class="absolute -top-2 -left-2 bg-accent text-xs w-5 h-5 rounded-full flex items-center justify-center">
                        {{ session('cart_count', 0) }}
                    </span>
                </a>

                <button class="md:hidden" id="mobile-menu-btn">
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

    {{-- Footer --}}
    <footer class="bg-primary text-gray-300 mt-16">
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