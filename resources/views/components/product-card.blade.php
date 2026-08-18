<div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden group">
    <a href="{{ route('products.show', $product->slug) }}" class="block relative">
        <div class="h-40 bg-gray-100 flex items-center justify-center overflow-hidden">
            @if($product->main_image)
                <img src="{{ asset('storage/' . $product->main_image) }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition {{ !$product->has_variants && $product->stock <= 0 ? 'opacity-50 grayscale' : '' }}">
            @else
                <span class="text-gray-400">لا توجد صورة</span>
            @endif
        </div>

        @if($product->hasActiveSale())
            <span class="absolute top-2 right-2 bg-sale text-white text-xs font-bold px-2 py-1 rounded">
                خصم
            </span>
        @endif

        @if(!$product->has_variants && $product->stock <= 0)
            <span class="absolute top-2 right-2 bg-gray-700 text-white text-xs font-bold px-2 py-1 rounded">
                نفدت الكمية
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

        @if($product->has_variants)
            {{-- منتج فيه خيارات (لون/مقاس/إلخ) - لازم يختارهم من صفحة المنتج نفسها --}}
            <a href="{{ route('products.show', $product->slug) }}"
               class="cursor-pointer w-full block text-center bg-primary hover:bg-accent text-white text-sm font-medium py-2 rounded-lg transition">
                اختر الخيارات
            </a>
        @elseif($product->stock <= 0)
            <button type="button" disabled
                    class="w-full bg-gray-100 text-gray-400 text-sm font-medium py-2 rounded-lg cursor-not-allowed">
                نفدت الكمية
            </button>
        @else
            <button
                x-data
                @click="$store.cart.add({{ $product->id }})"
                :disabled="$store.cart.items.some(i => i.product_id === {{ $product->id }}) || $store.cart.loading"
                :class="$store.cart.items.some(i => i.product_id === {{ $product->id }})
                    ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                    : 'bg-primary hover:bg-accent text-white'"
                class="w-full text-sm font-medium py-2 rounded-lg transition flex items-center justify-center gap-1">
                <template x-if="$store.cart.items.some(i => i.product_id === {{ $product->id }})">
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        مضاف للسلة
                    </span>
                </template>
                <template x-if="!$store.cart.items.some(i => i.product_id === {{ $product->id }})">
                    <span>أضف للسلة</span>
                </template>
            </button>
        @endif
    </div>
</div>