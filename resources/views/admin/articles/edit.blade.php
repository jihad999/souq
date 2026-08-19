@extends('layouts.admin')
@section('title', 'تعديل مقال - سوق')
@section('page-title', 'تعديل: ' . $article->title)

@section('content')

<form action="{{ route('admin.articles.update', $article) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">عنوان المقال</label>
            <input type="text" name="title" value="{{ old('title', $article->title) }}"
                   class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none">
            @error('title') <p class="text-sale text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">مقدمة قصيرة</label>
            <textarea name="excerpt" rows="2" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none">{{ old('excerpt', $article->excerpt) }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">الصورة الحالية</label>
            @if($article->image)
                <div class="w-24 h-24 bg-gray-100 rounded-lg overflow-hidden mb-2">
                    <img src="{{ asset('storage/' . $article->image) }}" class="w-full h-full object-cover">
                </div>
            @endif
            <input type="file" name="image" accept="image/*" class="w-full border rounded-lg px-4 py-2 cursor-pointer">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">المحتوى الكامل</label>
            <textarea name="content" rows="12" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none">{{ old('content', $article->content) }}</textarea>
            @error('content') <p class="text-sale text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">تاريخ النشر</label>
            <input type="datetime-local" name="published_at"
                   value="{{ old('published_at', $article->published_at?->format('Y-m-d\TH:i')) }}"
                   class="w-full md:w-1/2 border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none">
        </div>

        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="is_published" value="1" {{ old('is_published', $article->is_published) ? 'checked' : '' }} class="cursor-pointer">
            <span class="text-sm">منشور</span>
        </label>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="cursor-pointer bg-accent hover:bg-accent-dark text-white font-semibold px-6 py-3 rounded-lg transition">
            حفظ التعديلات
        </button>
        <a href="{{ route('admin.articles.index') }}" class="cursor-pointer bg-gray-100 hover:bg-gray-200 text-primary font-semibold px-6 py-3 rounded-lg transition">
            إلغاء
        </a>
    </div>
</form>

@endsection