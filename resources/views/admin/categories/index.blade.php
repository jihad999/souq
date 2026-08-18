@extends('layouts.admin')
@section('title', 'إدارة الفئات - سوق')
@section('page-title', 'الفئات')

@section('content')

<div class="flex justify-end mb-6">
    <a href="{{ route('admin.categories.create') }}"
       class="cursor-pointer bg-accent hover:bg-accent-dark text-white font-medium px-5 py-2.5 rounded-lg transition">
        + إضافة فئة جديدة
    </a>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr class="text-right text-gray-500">
                <th class="p-4">الصورة</th>
                <th class="p-4">الاسم</th>
                <th class="p-4">عدد المنتجات</th>
                <th class="p-4">الحالة</th>
                <th class="p-4">الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
            <tr class="border-t">
                <td class="p-4">
                    <div class="w-12 h-12 bg-gray-100 rounded-lg overflow-hidden">
                        @if($category->image)
                            <img src="{{ asset('storage/' . $category->image) }}" class="w-full h-full object-cover">
                        @endif
                    </div>
                </td>
                <td class="p-4 font-medium text-primary">{{ $category->name }}</td>
                <td class="p-4 text-gray-500">{{ $category->products_count }}</td>
                <td class="p-4">
                    @if($category->is_active)
                        <span class="text-xs bg-green-50 text-success px-2 py-1 rounded">نشطة</span>
                    @else
                        <span class="text-xs bg-gray-100 text-gray-500 px-2 py-1 rounded">غير نشطة</span>
                    @endif
                </td>
                <td class="p-4">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="cursor-pointer text-accent hover:underline">تعديل</a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                              x-data
                              @submit.prevent="$store.confirm.show('هل أنت متأكد من حذف هذه الفئة؟', () => $el.submit())">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="cursor-pointer text-sale hover:underline">حذف</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="p-8 text-center text-gray-500">لا يوجد فئات بعد.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $categories->links() }}
</div>

@endsection