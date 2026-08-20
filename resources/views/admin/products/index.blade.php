@extends('layouts.admin')
@section('title', 'إدارة المنتجات - سوق')
@section('page-title', 'المنتجات')

@section('content')

<div class="flex justify-between mb-6">
    <a href="{{ route('admin.products.create') }}"
       class="cursor-pointer bg-accent hover:bg-accent-dark text-white font-medium px-5 py-2.5 rounded-lg transition">
        + إضافة منتج جديد
    </a>
    <a href="{{ route('admin.products.trashed') }}" class="cursor-pointer text-sm text-gray-500 hover:text-sale flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
        </svg>
        سلة المحذوفات
    </a>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr class="text-right text-gray-500">
                <th class="p-4">الصورة</th>
                <th class="p-4">الاسم</th>
                <th class="p-4">الفئة</th>
                <th class="p-4">السعر</th>
                <th class="p-4">المخزون</th>
                <th class="p-4">الحالة</th>
                <th class="p-4">الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr class="border-t">
                <td class="p-4">
                    <div class="w-12 h-12 bg-gray-100 rounded-lg overflow-hidden">
                        @if($product->main_image)
                            <img src="{{ asset('storage/' . $product->main_image) }}" class="w-full h-full object-cover">
                        @endif
                    </div>
                </td>
                <td class="p-4 font-medium text-primary">{{ $product->name }}</td>
                <td class="p-4 text-gray-500">
                    @foreach ($product->categories as $category)
                        <span class="text-xs bg-yellow-50 text-yellow-600 px-2 py-1 rounded">{{ $category->name }}</span>
                    @endforeach
                </td>
                <td class="p-4">{{ number_format($product->price, 2) }} ₪</td>
                <td class="p-4">
                    @if($product->variants_count > 0)
                        <span class="text-xs bg-blue-50 text-blue-600 px-2 py-1 rounded">{{ $product->variants_count }} خيارات</span>
                    @else
                        {{ $product->stock }}
                    @endif
                </td>
                <td class="p-4">
                    @if($product->is_active)
                        <span class="text-xs bg-green-50 text-success px-2 py-1 rounded">نشط</span>
                    @else
                        <span class="text-xs bg-gray-100 text-gray-500 px-2 py-1 rounded">غير نشط</span>
                    @endif
                </td>
                <td class="p-4">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.products.edit', $product) }}" class="cursor-pointer text-accent hover:underline">تعديل</a>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                              x-data
                              @submit.prevent="$store.confirm.show('هل أنت متأكد من حذف هذا المنتج؟', () => $el.submit())">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="cursor-pointer text-sale hover:underline">حذف</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="p-8 text-center text-gray-500">لا يوجد منتجات بعد.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $products->links() }}
</div>

@endsection