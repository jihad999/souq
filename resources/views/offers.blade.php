@extends('layouts.app')
@section('title', 'العروض - سوق')

@section('content')
<div class="container mx-auto px-4 py-10">

    <h1 class="text-3xl font-bold text-primary mb-8">العروض الخاصة</h1>

    @if($products->isEmpty())
        <div class="bg-white rounded-xl shadow p-16 text-center">
            <div class="text-5xl mb-4">🏷️</div>
            <p class="text-gray-500 text-lg">ما في عروض حاليًا، تابعنا قريبًا لعروض جديدة!</p>
        </div>
    @else
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
            @foreach($products as $product)
                @include('components.product-card', ['product' => $product])
            @endforeach
        </div>
        <div>{{ $products->links() }}</div>
    @endif
</div>
@endsection