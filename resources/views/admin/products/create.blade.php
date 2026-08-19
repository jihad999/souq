@extends('layouts.admin')
@section('title', 'إضافة منتج - سوق')
@section('page-title', 'إضافة منتج جديد')

@section('content')

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="max-w-4xl">
    @csrf

    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <h2 class="text-lg font-bold text-primary mb-4">المعلومات الأساسية</h2>

        <div class="grid md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium mb-1">اسم المنتج</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none">
                @error('name') <p class="text-sale text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">الفئة الأساسية</label>
                <select name="category_id" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none">
                    <option value="">اختر فئة</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <p class="text-sale text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
            <label class="block text-sm font-medium mb-1">فئات إضافية (اختياري)</label>
                <select name="extra_categories[]" multiple class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none h-32">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-400 mt-1">اضغط Ctrl (أو Cmd على Mac) لاختيار أكثر من فئة</p>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">الوصف</label>
                <textarea name="description" rows="4" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none">{{ old('description') }}</textarea>
            </div>

            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="cursor-pointer">
                <span class="text-sm">منتج نشط (يظهر بالموقع)</span>
            </label>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <h2 class="text-lg font-bold text-primary mb-4">السعر والمخزون</h2>

        <div class="grid md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium mb-1">السعر الأساسي</label>
                <input type="number" step="0.01" name="price" value="{{ old('price') }}"
                       class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none">
                @error('price') <p class="text-sale text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">سعر الخصم (اختياري)</label>
                <input type="number" step="0.01" name="sale_price" value="{{ old('sale_price') }}"
                       class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none">
                @error('sale_price') <p class="text-sale text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium mb-1">بداية الخصم</label>
                <input type="datetime-local" name="sale_starts_at" value="{{ old('sale_starts_at') }}"
                       class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">نهاية الخصم</label>
                <input type="datetime-local" name="sale_ends_at" value="{{ old('sale_ends_at') }}"
                       class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">الكمية بالمخزون</label>
            <input type="number" name="stock" value="{{ old('stock', 0) }}"
                   class="w-full md:w-1/2 border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none">
            <p class="text-xs text-gray-400 mt-1">إذا أضفت خيارات (ألوان/مقاسات) لاحقًا، سيتم استخدام مخزون كل خيار بدلًا من هذا الحقل.</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <h2 class="text-lg font-bold text-primary mb-4">الصور</h2>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">الصورة الرئيسية</label>
            <input type="file" name="main_image" accept="image/*"
                   class="w-full border rounded-lg px-4 py-2 cursor-pointer">
            @error('main_image') <p class="text-sale text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">صور إضافية (معرض الصور)</label>
            <input type="file" name="gallery_images[]" accept="image/*" multiple
                   class="w-full border rounded-lg px-4 py-2 cursor-pointer">
        </div>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="cursor-pointer bg-accent hover:bg-accent-dark text-white font-semibold px-6 py-3 rounded-lg transition">
            حفظ المنتج
        </button>
        <a href="{{ route('admin.products.index') }}" class="cursor-pointer bg-gray-100 hover:bg-gray-200 text-primary font-semibold px-6 py-3 rounded-lg transition">
            إلغاء
        </a>
    </div>
</form>

@endsection