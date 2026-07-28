@if($products->isEmpty())
    <div class="bg-white rounded-xl shadow p-12 text-center text-gray-500">
        ما في منتجات مطابقة لبحثك.
    </div>
@else
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @foreach($products as $product)
            @include('components.product-card', ['product' => $product])
        @endforeach
    </div>

    <div>
        {{ $products->links() }}
    </div>
@endif