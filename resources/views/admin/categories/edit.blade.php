@extends('layouts.admin')
@section('title', 'تعديل فئة - سوق')
@section('page-title', 'تعديل: ' . $category->name)

@section('content')

<form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data" class="max-w-2xl">
    @csrf
    @method('PUT')

    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">اسم الفئة</label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}"
                   class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none">
            @error('name') <p class="text-sale text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">الصورة الحالية</label>
            @if($category->image)
                <div class="w-20 h-20 bg-gray-100 rounded-lg overflow-hidden mb-2">
                    <img src="{{ asset('storage/' . $category->image) }}" class="w-full h-full object-cover">
                </div>
            @endif
            <input type="file" name="image" accept="image/*" class="w-full border rounded-lg px-4 py-2 cursor-pointer">
            <p class="text-xs text-gray-400 mt-1">اترك الحقل فارغًا للاحتفاظ بالصورة الحالية</p>
        </div>

        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }} class="cursor-pointer">
            <span class="text-sm">فئة نشطة (تظهر بالموقع)</span>
        </label>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="cursor-pointer bg-accent hover:bg-accent-dark text-white font-semibold px-6 py-3 rounded-lg transition">
            حفظ التعديلات
        </button>
        <a href="{{ route('admin.categories.index') }}" class="cursor-pointer bg-gray-100 hover:bg-gray-200 text-primary font-semibold px-6 py-3 rounded-lg transition">
            إلغاء
        </a>
    </div>
</form>

@endsection