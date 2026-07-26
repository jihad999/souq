@extends('layouts.app')
@section('title', $article->title . ' - سوق')

@section('content')
<div class="container mx-auto px-4 py-10 max-w-3xl">
    <h1 class="text-3xl font-bold text-primary mb-4">{{ $article->title }}</h1>
    <span class="text-sm text-gray-400 block mb-6">{{ $article->published_at->format('Y-m-d') }}</span>

    @if($article->image)
        <div class="h-72 bg-gray-100 rounded-xl overflow-hidden mb-8">
            <img src="{{ asset('storage/' . $article->image) }}" class="w-full h-full object-cover">
        </div>
    @endif

    <div class="prose max-w-none text-gray-700 leading-8">
        {!! $article->content !!}
    </div>
</div>
@endsection