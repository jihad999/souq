<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة التحكم - سوق')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-primary" x-data="{ sidebarOpen: false }">

    <div class="flex min-h-screen">

        {{-- Sidebar --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : 'translate-x-full md:translate-x-0'"
               class="fixed md:sticky top-0 right-0 h-screen w-64 bg-primary text-white flex flex-col transition-transform z-40">

            <div class="p-5 border-b border-primary-light">
                <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold text-accent">سوق - الإدارة</a>
            </div>

            <nav class="flex-1 overflow-y-auto py-4 space-y-1 px-3">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition {{ request()->routeIs('admin.dashboard') ? 'bg-accent text-white' : 'hover:bg-primary-light' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    الرئيسية
                </a>

                <a href="{{ route('admin.products.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition {{ request()->routeIs('admin.products.*') ? 'bg-accent text-white' : 'hover:bg-primary-light' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    المنتجات
                </a>

                <a href="{{ route('admin.categories.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition {{ request()->routeIs('admin.categories.*') ? 'bg-accent text-white' : 'hover:bg-primary-light' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    الفئات
                </a>

                <a href="{{ route('admin.orders.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition {{ request()->routeIs('admin.orders.*') ? 'bg-accent text-white' : 'hover:bg-primary-light' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    الطلبات
                </a>

                <a href="{{ route('admin.promo-codes.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition {{ request()->routeIs('admin.promo-codes.*') ? 'bg-accent text-white' : 'hover:bg-primary-light' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    أكواد الخصم
                </a>

                <a href="{{ route('admin.partners.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition {{ request()->routeIs('admin.partners.*') ? 'bg-accent text-white' : 'hover:bg-primary-light' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 100-8 4 4 0 000 8zm6 3c0-2.21-3.582-4-8-4s-8 1.79-8 4"/>
                    </svg>
                    الشركاء
                </a>

                <a href="{{ route('admin.articles.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition {{ request()->routeIs('admin.articles.*') ? 'bg-accent text-white' : 'hover:bg-primary-light' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17h6m-6-4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    المقالات
                </a>

                <a href="{{ route('admin.messages.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition {{ request()->routeIs('admin.messages.*') ? 'bg-accent text-white' : 'hover:bg-primary-light' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    رسائل التواصل
                </a>
            </nav>

            <div class="p-3 border-t border-primary-light">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="cursor-pointer flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm w-full hover:bg-primary-light transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        تسجيل الخروج
                    </button>
                </form>
            </div>
        </aside>

        {{-- Overlay للموبايل --}}
        <div x-show="sidebarOpen" @click="sidebarOpen = false"
             class="fixed inset-0 bg-black/40 z-30 md:hidden" style="display: none;"></div>

        {{-- المحتوى --}}
        <div class="flex-1 flex flex-col min-w-0">
            <header class="bg-white shadow-sm sticky top-0 z-20 flex items-center justify-between px-6 h-16">
                <button @click="sidebarOpen = !sidebarOpen" class="cursor-pointer md:hidden">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <h1 class="text-lg font-bold text-primary">@yield('page-title', 'لوحة التحكم')</h1>
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" target="_blank" class="text-sm text-gray-500 hover:text-accent">عرض الموقع</a>
                    <span class="text-sm text-gray-600">{{ auth()->user()->name }}</span>
                </div>
            </header>

            <main class="flex-1 p-6">
                @if(session('success'))
                    <div class="bg-green-50 text-success border border-green-200 rounded-lg p-4 mb-6">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="bg-red-50 text-sale border border-red-200 rounded-lg p-4 mb-6">{{ session('error') }}</div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

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
                        class="cursor-pointer flex-1 bg-gray-100 hover:bg-gray-200 text-primary font-medium py-2.5 rounded-lg transition">
                    إلغاء
                </button>
                <button @click="$store.confirm.confirmed()"
                        class="cursor-pointer flex-1 bg-sale hover:bg-red-700 text-white font-medium py-2.5 rounded-lg transition">
                    تأكيد الحذف
                </button>
            </div>
        </div>
    </div>

</body>
</html>