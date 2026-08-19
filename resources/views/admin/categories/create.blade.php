@extends('layouts.admin')
@section('title', 'إضافة فئة - سوق')
@section('page-title', 'إضافة فئة جديدة')

@section('content')

<form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">اسم الفئة</label>
            <input type="text" name="name" value="{{ old('name') }}"
                   class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none">
            @error('name') <p class="text-sale text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">صورة الفئة</label>
            <input type="file" name="image" accept="image/*" class="w-full border rounded-lg px-4 py-2 cursor-pointer">
            @error('image') <p class="text-sale text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="cursor-pointer">
            <span class="text-sm">فئة نشطة (تظهر بالموقع)</span>
        </label>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="cursor-pointer bg-accent hover:bg-accent-dark text-white font-semibold px-6 py-3 rounded-lg transition">
            حفظ الفئة
        </button>
        <a href="{{ route('admin.categories.index') }}" class="cursor-pointer bg-gray-100 hover:bg-gray-200 text-primary font-semibold px-6 py-3 rounded-lg transition">
            إلغاء
        </a>
    </div>
</form>

@endsection