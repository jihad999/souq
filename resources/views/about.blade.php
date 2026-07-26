@extends('layouts.app')
@section('title', 'من نحن - سوق')

@section('content')
<div class="container mx-auto px-4 py-12">

    {{-- من نحن --}}
    <section class="max-w-3xl mx-auto text-center mb-16">
        <h1 class="text-3xl md:text-4xl font-bold text-primary mb-4">من نحن</h1>
        <p class="text-gray-600 leading-8">
            سوق منصة إلكترونية تهدف لتوفير تجربة تسوق سهلة وموثوقة، بنوفر منتجات متنوعة
            بجودة عالية وأسعار منافسة، مع خدمة عملاء دايمًا جاهزة لمساعدتك.
        </p>
    </section>

   {{-- شركاء النجاح --}}
    @if($partners->isNotEmpty())
    <section class="mb-20" x-data="{
        current: 0,
        perView: 4,
        total: {{ $partners->count() }},
        get pages() { return Math.ceil(this.total / this.perView) },
        next() { this.current = (this.current + 1) % this.pages },
        prev() { this.current = (this.current - 1 + this.pages) % this.pages }
    }">
        <div class="text-center mb-10">
            <span class="text-accent font-semibold text-sm tracking-wide">نفخر بالتعاون معهم</span>
            <h2 class="text-2xl md:text-3xl font-bold text-primary mt-2">شركاء النجاح</h2>
        </div>

        <div class="relative">
            <div class="overflow-hidden">
                <div class="flex transition-transform duration-500 ease-in-out"
                    :style="`transform: translateX(${current * 100}%)`">
                    @foreach($partners->chunk(4) as $chunk)
                    <div class="w-full flex-shrink-0 grid grid-cols-2 md:grid-cols-4 gap-6 px-1">
                        @foreach($chunk as $partner)
                        <div class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 p-6 flex flex-col items-center text-center">
                            <div class="w-20 h-20 rounded-full bg-gray-50 flex items-center justify-center overflow-hidden mb-4 ring-1 ring-gray-100 group-hover:ring-accent transition">
                                @if($partner->logo)
                                    <img src="{{ asset('storage/' . $partner->logo) }}"
                                        alt="{{ $partner->company_name }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <span class="text-2xl">🏢</span>
                                @endif
                            </div>
                            <h3 class="font-semibold text-primary text-sm leading-5">{{ $partner->company_name }}</h3>
                        </div>
                        @endforeach
                    </div>
                    @endforeach
                </div>
            </div>

            @if($partners->count() > 4)
            <button @click="prev()"
                    class="absolute top-1/2 -translate-y-1/2 right-2 md:right-0 md:translate-x-1/2 bg-white shadow-lg rounded-full w-11 h-11 flex items-center justify-center text-primary hover:bg-accent hover:text-white transition z-10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <button @click="next()"
                    class="absolute top-1/2 -translate-y-1/2 left-2 md:left-0 md:-translate-x-1/2 bg-white shadow-lg rounded-full w-11 h-11 flex items-center justify-center text-primary hover:bg-accent hover:text-white transition z-10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>

            {{-- نقاط التنقل --}}
            <div class="flex justify-center gap-2 mt-8">
                <template x-for="page in pages">
                    <button @click="current = page - 1"
                            class="w-2 h-2 rounded-full transition"
                            :class="current === page - 1 ? 'bg-accent w-6' : 'bg-gray-300'">
                    </button>
                </template>
            </div>
            @endif
        </div>
    </section>
    @endif

    {{-- عملاؤنا --}}
    @if($clientLogos->isNotEmpty())
    <section class="mb-20" x-data="{
        current: 0,
        perView: 4,
        total: {{ $clientLogos->count() }},
        get pages() { return Math.ceil(this.total / this.perView) },
        next() { this.current = (this.current + 1) % this.pages },
        prev() { this.current = (this.current - 1 + this.pages) % this.pages }
    }">
        <div class="text-center mb-10">
            <span class="text-accent font-semibold text-sm tracking-wide">ثقتكم سر نجاحنا</span>
            <h2 class="text-2xl md:text-3xl font-bold text-primary mt-2">عملاؤنا</h2>
        </div>

        <div class="relative">
            <div class="overflow-hidden">
                <div class="flex transition-transform duration-500 ease-in-out"
                    :style="`transform: translateX(${current * 100}%)`">
                    @foreach($clientLogos->chunk(4) as $chunk)
                    <div class="w-full flex-shrink-0 grid grid-cols-2 md:grid-cols-4 gap-6 px-1">
                        @foreach($chunk as $client)
                        <div class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 p-5 flex flex-col items-center text-center">
                            <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center overflow-hidden mb-3 ring-1 ring-gray-100 group-hover:ring-accent transition">
                                @if($client->logo)
                                    <img src="{{ asset('storage/' . $client->logo) }}"
                                        alt="{{ $client->name }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <span class="text-xl">🏬</span>
                                @endif
                            </div>
                            <h3 class="font-semibold text-primary text-xs leading-5">{{ $client->name }}</h3>
                        </div>
                        @endforeach
                    </div>
                    @endforeach
                </div>
            </div>

            @if($clientLogos->count() > 4)
            <button @click="prev()"
                    class="absolute top-1/2 -translate-y-1/2 right-2 md:right-0 md:translate-x-1/2 bg-white shadow-lg rounded-full w-11 h-11 flex items-center justify-center text-primary hover:bg-accent hover:text-white transition z-10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <button @click="next()"
                    class="absolute top-1/2 -translate-y-1/2 left-2 md:left-0 md:-translate-x-1/2 bg-white shadow-lg rounded-full w-11 h-11 flex items-center justify-center text-primary hover:bg-accent hover:text-white transition z-10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>

            <div class="flex justify-center gap-2 mt-8">
                <template x-for="page in pages">
                    <button @click="current = page - 1"
                            class="w-2 h-2 rounded-full transition"
                            :class="current === page - 1 ? 'bg-accent w-6' : 'bg-gray-300'">
                    </button>
                </template>
            </div>
            @endif
        </div>
    </section>
    @endif

    {{-- فورم انضمام كشريك --}}
    <section class="max-w-2xl mx-auto bg-white rounded-xl shadow p-8">
        <h2 class="text-2xl font-bold text-primary mb-2 text-center">بدك تصير شريك معنا؟</h2>
        <p class="text-gray-500 text-center mb-6">عبّي الفورم وفريقنا رح يراجع طلبك ويتواصل معك.</p>

        @if(session('partner_success'))
            <div class="bg-green-50 text-success border border-green-200 rounded-lg p-4 mb-6 text-center">
                {{ session('partner_success') }}
            </div>
        @endif

        <form action="{{ route('partners.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">اسم الشركة</label>
                    <input type="text" name="company_name" value="{{ old('company_name') }}"
                           class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none" required>
                    @error('company_name') <p class="text-sale text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">اسم المسؤول</label>
                    <input type="text" name="contact_name" value="{{ old('contact_name') }}"
                           class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none" required>
                    @error('contact_name') <p class="text-sale text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">البريد الإلكتروني</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none" required>
                    @error('email') <p class="text-sale text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">رقم الهاتف</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none" required>
                    @error('phone') <p class="text-sale text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">الموقع الإلكتروني (اختياري)</label>
                <input type="text" name="website" value="{{ old('website') }}"
                       class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">رسالة</label>
                <textarea name="message" rows="4"
                          class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none">{{ old('message') }}</textarea>
            </div>
            <button type="submit" class="w-full bg-accent hover:bg-accent-dark text-white font-semibold py-3 rounded-lg transition">
                إرسال طلب الشراكة
            </button>
        </form>
    </section>
</div>
@endsection