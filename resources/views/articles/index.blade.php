@extends('layouts.app')
@section('title', 'المدونة - سوق')

@section('content')
<div class="container mx-auto px-4 py-10">

    <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-8">
        <h1 class="text-3xl font-bold text-primary">المدونة</h1>

        <form method="GET" action="{{ route('articles.index') }}" class="w-full md:w-80">
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="ابحث عن مقال..."
                       class="w-full border rounded-lg pr-4 pl-10 py-2 text-sm focus:ring-2 focus:ring-accent focus:outline-none">
                <button type="submit" class="absolute top-1/2 -translate-y-1/2 left-3 text-gray-400 hover:text-accent cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>
            </div>
        </form>
    </div>

    @if(request('search'))
        <p class="text-sm text-gray-500 mb-6">
            نتائج البحث عن: <span class="font-semibold text-primary">"{{ request('search') }}"</span>
            <a href="{{ route('articles.index') }}" class="text-accent hover:underline mr-2">(مسح)</a>
        </p>
    @endif

    @if($articles->isEmpty())
        <div class="bg-white rounded-xl shadow p-16 text-center text-gray-500">
            @if(request('search'))
                ما في مقالات مطابقة لبحثك.
            @else
                لا يوجد مقالات حاليًا.
            @endif
        </div>
    @else
        <div class="grid md:grid-cols-3 gap-6 mb-8">
            @foreach($articles as $article)
            <a href="{{ route('articles.show', $article->slug) }}"
               class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">
                <div class="h-40 bg-gray-100 overflow-hidden">
                    @if($article->image)
                        <img src="{{ asset('storage/' . $article->image) }}" class="w-full h-full object-cover">
                    @endif
                </div>
                <div class="p-4">
                    <h2 class="font-semibold text-primary mb-2">{{ $article->title }}</h2>
                    <p class="text-sm text-gray-500 line-clamp-2">{{ $article->excerpt }}</p>
                    <span class="text-xs text-gray-400 mt-2 block">{{ $article->published_at->format('Y-m-d') }}</span>
                </div>
            </a>
            @endforeach
        </div>
        {{ $articles->links() }}
    @endif
</div>
@endsection