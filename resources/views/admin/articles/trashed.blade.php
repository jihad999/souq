@extends('layouts.admin')
@section('title', 'المقالات المحذوفة - سوق')
@section('page-title', 'سلة المحذوفات - المقالات')

@section('content')

<div class="flex justify-end mb-6">
    <a href="{{ route('admin.categories.index') }}" class="cursor-pointer text-sm text-gray-500 hover:text-accent">← الرجوع لقائمة المقالات</a>
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
                            <form action="{{ route('admin.articles.restore', $article->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="cursor-pointer text-success hover:underline">استرجاع</button>
                            </form>
                            <form action="{{ route('admin.articles.force-delete', $article->id) }}" method="POST"
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
    {{ $articles->links() }}
</div>

@endsection