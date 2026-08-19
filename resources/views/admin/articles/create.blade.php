@extends('layouts.admin')
@section('title', 'إضافة مقال - سوق')
@section('page-title', 'إضافة مقال جديد')

@section('content')

<form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">عنوان المقال</label>
            <input type="text" name="title" value="{{ old('title') }}"
                   class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none">
            @error('title') <p class="text-sale text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">مقدمة قصيرة (تظهر بالكارد)</label>
            <textarea name="excerpt" rows="2" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none">{{ old('excerpt') }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">صورة المقال</label>
            <input type="file" name="image" accept="image/*" class="w-full border rounded-lg px-4 py-2 cursor-pointer">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">المحتوى الكامل</label>
            <textarea name="content" rows="12" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none">{{ old('content') }}</textarea>
            @error('content') <p class="text-sale text-sm mt-1">{{ $message }}</p> @enderror
            <p class="text-xs text-gray-400 mt-1">يمكن استخدام وسوم HTML بسيطة مثل &lt;p&gt; و&lt;strong&gt;</p>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">تاريخ النشر (اختياري)</label>
            <input type="datetime-local" name="published_at" value="{{ old('published_at') }}"
                   class="w-full md:w-1/2 border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none">
            <p class="text-xs text-gray-400 mt-1">اتركه فارغًا لاستخدام تاريخ اليوم عند النشر</p>
        </div>

        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }} class="cursor-pointer">
            <span class="text-sm">نشر المقال مباشرة</span>
        </label>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="cursor-pointer bg-accent hover:bg-accent-dark text-white font-semibold px-6 py-3 rounded-lg transition">
            حفظ المقال
        </button>
        <a href="{{ route('admin.articles.index') }}" class="cursor-pointer bg-gray-100 hover:bg-gray-200 text-primary font-semibold px-6 py-3 rounded-lg transition">
            إلغاء
        </a>
    </div>
</form>

@endsection