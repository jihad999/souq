<div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden group">
    <a href="{{ route('products.show', $product->slug) }}" class="block relative">
        <div class="h-40 bg-gray-100 flex items-center justify-center overflow-hidden">
            @if($product->main_image)
                <img src="{{ asset('storage/' . $product->main_image) }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition">
            @else
                <span class="text-gray-400">لا توجد صورة</span>
            @endif
        </div>

        @if($product->hasActiveSale())
            <span class="absolute top-2 right-2 bg-sale text-white text-xs font-bold px-2 py-1 rounded">
                خصم
            </span>
        @endif
    </a>

    <div class="p-4">
        <a href="{{ route('products.show', $product->slug) }}">
            <h3 class="font-semibold text-primary mb-2 truncate">{{ $product->name }}</h3>
        </a>

        <div class="flex items-center gap-2 mb-3">
            @if($product->hasActiveSale())
                <span class="text-sale font-bold">{{ number_format($product->sale_price, 2) }} ₪</span>
                <span class="text-gray-400 text-sm line-through">{{ number_format($product->price, 2) }} ₪</span>
            @else
                <span class="text-primary font-bold">{{ number_format($product->price, 2) }} ₪</span>
            @endif
        </div>

        <form action="{{ route('cart.add', $product->id) }}" method="POST">
            @csrf
            <button type="submit"
                    class="w-full bg-primary hover:bg-accent text-white text-sm font-medium py-2 rounded-lg transition">
                أضف للسلة
            </button>
        </form>
    </div>
</div>