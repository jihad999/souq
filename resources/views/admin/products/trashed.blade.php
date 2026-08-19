@extends('layouts.admin')
@section('title', 'المنتجات المحذوفة - سوق')
@section('page-title', 'سلة المحذوفات - المنتجات')

@section('content')

<div class="mb-6">
    <a href="{{ route('admin.products.index') }}" class="cursor-pointer text-sm text-gray-500 hover:text-accent">← الرجوع لقائمة المنتجات</a>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr class="text-right text-gray-500">
                <th class="p-4">الصورة</th>
                <th class="p-4">الاسم</th>
                <th class="p-4">الفئة</th>
                <th class="p-4">تاريخ الحذف</th>
                <th class="p-4">الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr class="border-t">
                <td class="p-4">
                    <div class="w-12 h-12 bg-gray-100 rounded-lg overflow-hidden opacity-60">
                        @if($product->main_image)
                            <img src="{{ asset('storage/' . $product->main_image) }}" class="w-full h-full object-cover">
                        @endif
                    </div>
                </td>
                <td class="p-4 font-medium text-gray-500">{{ $product->name }}</td>
                <td class="p-4 text-gray-400">{{ $product->category->name }}</td>
                <td class="p-4 text-gray-400">{{ $product->deleted_at->format('Y-m-d H:i') }}</td>
                <td class="p-4">
                    <div class="flex items-center gap-3">
                        <form action="{{ route('admin.products.restore', $product->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="cursor-pointer text-success hover:underline">استرجاع</button>
                        </form>
                        <form action="{{ route('admin.products.force-delete', $product->id) }}" method="POST"
                              x-data
                              @submit.prevent="$store.confirm.show('حذف نهائي! هذا الإجراء لا يمكن التراجع عنه. متأكد؟', () => $el.submit())">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="cursor-pointer text-sale hover:underline">حذف نهائي</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="p-8 text-center text-gray-500">سلة المحذوفات فارغة.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $products->links() }}
</div>

@endsection