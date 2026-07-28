@extends('layouts.app')
@section('title', 'تواصل معنا - سوق')

@section('content')
<div class="container mx-auto px-4 py-12">

    <div class="max-w-4xl mx-auto text-center mb-12">
        <h1 class="text-3xl md:text-4xl font-bold text-primary mb-4">تواصل معنا</h1>
        <p class="text-gray-600">عندك سؤال أو استفسار؟ عبّي الفورم وفريقنا رح يرد عليك بأسرع وقت.</p>
    </div>

    <div class="max-w-5xl mx-auto grid md:grid-cols-3 gap-8">

        {{-- معلومات التواصل --}}
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow p-6">
                <div class="text-accent text-2xl mb-2">📍</div>
                <h3 class="font-semibold text-primary mb-1">العنوان</h3>
                <p class="text-gray-500 text-sm">فلسطين</p>
            </div>
            <div class="bg-white rounded-xl shadow p-6">
                <div class="text-accent text-2xl mb-2">📞</div>
                <h3 class="font-semibold text-primary mb-1">الهاتف</h3>
                <p class="text-gray-500 text-sm" dir="ltr">+970 000 000 000</p>
            </div>
            <div class="bg-white rounded-xl shadow p-6">
                <div class="text-accent text-2xl mb-2">✉️</div>
                <h3 class="font-semibold text-primary mb-1">البريد الإلكتروني</h3>
                <p class="text-gray-500 text-sm" dir="ltr">[email protected]</p>
            </div>
        </div>

        {{-- الفورم --}}
        <div class="md:col-span-2 bg-white rounded-xl shadow p-8">

            @if(session('contact_success'))
                <div class="bg-green-50 text-success border border-green-200 rounded-lg p-4 mb-6 text-center">
                    {{ session('contact_success') }}
                </div>
            @endif

            <form action="{{ route('contact.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">الاسم</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none" required>
                        @error('name') <p class="text-sale text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">البريد الإلكتروني</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none" required>
                        @error('email') <p class="text-sale text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">الموضوع</label>
                    <input type="text" name="subject" value="{{ old('subject') }}"
                           class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">الرسالة</label>
                    <textarea name="message" rows="5"
                              class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none" required>{{ old('message') }}</textarea>
                    @error('message') <p class="text-sale text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <button type="submit"
                        class="w-full bg-accent hover:bg-accent-dark text-white font-semibold py-3 rounded-lg transition cursor-pointer">
                    إرسال الرسالة
                </button>
            </form>
        </div>
    </div>
</div>
@endsection