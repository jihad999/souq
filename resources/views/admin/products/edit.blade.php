@extends('layouts.admin')
@section('title', 'تعديل منتج - سوق')
@section('page-title', 'تعديل: ' . $product->name)

@section('content')

<form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <h2 class="text-lg font-bold text-primary mb-4">المعلومات الأساسية</h2>

        <div class="grid md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium mb-1">اسم المنتج</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}"
                       class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none">
                @error('name') <p class="text-sale text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">الفئة الأساسية</label>
                <select name="category_id" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <p class="text-sale text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">فئات إضافية (اختياري)</label>
            @php $selectedExtra = $product->categories->pluck('id')->all(); @endphp
            <select name="extra_categories[]" multiple class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none h-32">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ in_array($category->id, $selectedExtra) ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">الوصف</label>
            <textarea name="description" rows="4" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none">{{ old('description', $product->description) }}</textarea>
        </div>

        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="cursor-pointer">
            <span class="text-sm">منتج نشط (يظهر بالموقع)</span>
        </label>
    </div>

    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <h2 class="text-lg font-bold text-primary mb-4">السعر والمخزون</h2>

        <div class="grid md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium mb-1">السعر الأساسي</label>
                <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}"
                       class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none">
                @error('price') <p class="text-sale text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">سعر الخصم (اختياري)</label>
                <input type="number" step="0.01" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}"
                       class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none">
                @error('sale_price') <p class="text-sale text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium mb-1">بداية الخصم</label>
                <input type="datetime-local" name="sale_starts_at"
                       value="{{ old('sale_starts_at', $product->sale_starts_at?->format('Y-m-d\TH:i')) }}"
                       class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">نهاية الخصم</label>
                <input type="datetime-local" name="sale_ends_at"
                       value="{{ old('sale_ends_at', $product->sale_ends_at?->format('Y-m-d\TH:i')) }}"
                       class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">الكمية بالمخزون</label>
            <input type="number" name="stock" value="{{ old('stock', $product->stock) }}"
                   class="w-full md:w-1/2 border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none">
        </div>
    </div>

    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <h2 class="text-lg font-bold text-primary mb-4">الصور</h2>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">الصورة الرئيسية الحالية</label>
            @if($product->main_image)
                <div class="w-24 h-24 bg-gray-100 rounded-lg overflow-hidden mb-2">
                    <img src="{{ asset('storage/' . $product->main_image) }}" class="w-full h-full object-cover">
                </div>
            @endif
            <input type="file" name="main_image" accept="image/*" class="w-full border rounded-lg px-4 py-2 cursor-pointer">
            <p class="text-xs text-gray-400 mt-1">اترك الحقل فارغًا للاحتفاظ بالصورة الحالية</p>
        </div>

        @if($product->images->isNotEmpty())
        <div class="mb-4">
            <label class="block text-sm font-medium mb-2">صور المعرض الحالية</label>
            <div class="grid grid-cols-4 gap-3">
                @foreach($product->images as $image)
                <div class="relative group">
                    <div class="w-full h-20 bg-gray-100 rounded-lg overflow-hidden">
                        <img src="{{ asset('storage/' . $image->image) }}" class="w-full h-full object-cover">
                    </div>
                    <form action="{{ route('admin.products.gallery.destroy', $image) }}" method="POST"
                          x-data
                          @submit.prevent="$store.confirm.show('حذف هذه الصورة؟', () => $el.submit())"
                          class="absolute top-1 left-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="cursor-pointer bg-white rounded-full w-6 h-6 flex items-center justify-center text-sale shadow opacity-0 group-hover:opacity-100 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </form>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div>
            <label class="block text-sm font-medium mb-1">إضافة صور جديدة للمعرض</label>
            <input type="file" name="gallery_images[]" accept="image/*" multiple class="w-full border rounded-lg px-4 py-2 cursor-pointer">
        </div>
    </div>

    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-primary">الخصائص والخيارات (ألوان، مقاسات...)</h2>
                <p class="text-sm text-gray-500">إدارة الخصائص والـ Variants تتم من صفحة منفصلة بعد حفظ المنتج.</p>
            </div>
            <a href="{{ route('admin.products.attributes.index', $product) }}"
               class="cursor-pointer bg-primary hover:bg-accent text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                إدارة الخصائص ({{ $product->variants->count() }} خيار)
            </a>
        </div>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="cursor-pointer bg-accent hover:bg-accent-dark text-white font-semibold px-6 py-3 rounded-lg transition">
            حفظ التعديلات
        </button>
        <a href="{{ route('admin.products.index') }}" class="cursor-pointer bg-gray-100 hover:bg-gray-200 text-primary font-semibold px-6 py-3 rounded-lg transition">
            إلغاء
        </a>
    </div>
</form>

@endsection