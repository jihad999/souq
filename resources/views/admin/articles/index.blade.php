@extends('layouts.admin')
@section('title', 'إدارة المقالات - سوق')
@section('page-title', 'المقالات')

@section('content')

<div class="flex justify-between mb-6">
    <a href="{{ route('admin.articles.create') }}"
       class="cursor-pointer bg-accent hover:bg-accent-dark text-white font-medium px-5 py-2.5 rounded-lg transition">
        + إضافة مقال جديد
    </a>
    <a href="{{ route('admin.articles.trashed') }}" class="cursor-pointer text-sm text-gray-500 hover:text-sale flex items-center gap-1">
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
                <th class="p-4">العنوان</th>
                <th class="p-4">تاريخ النشر</th>
                <th class="p-4">الحالة</th>
                <th class="p-4">الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($articles as $article)
            <tr class="border-t">
                <td class="p-4">
                    <div class="w-12 h-12 bg-gray-100 rounded-lg overflow-hidden">
                        @if($article->image)
                            <img src="{{ asset('storage/' . $article->image) }}" class="w-full h-full object-cover">
                        @endif
                    </div>
                </td>
                <td class="p-4 font-medium text-primary">{{ $article->title }}</td>
                <td class="p-4 text-gray-500">{{ $article->published_at?->format('Y-m-d') ?? '—' }}</td>
                <td class="p-4">
                    @if($article->is_published)
                        <span class="text-xs bg-green-50 text-success px-2 py-1 rounded">منشور</span>
                    @else
                        <span class="text-xs bg-gray-100 text-gray-500 px-2 py-1 rounded">مسودة</span>
                    @endif
                </td>
                <td class="p-4">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.articles.edit', $article) }}" class="cursor-pointer text-accent hover:underline">تعديل</a>
                        <form action="{{ route('admin.articles.destroy', $article) }}" method="POST"
                              x-data
                              @submit.prevent="$store.confirm.show('حذف هذا المقال؟', () => $el.submit())">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="cursor-pointer text-sale hover:underline">حذف</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="p-8 text-center text-gray-500">لا يوجد مقالات بعد.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $articles->links() }}
</div>

@endsection