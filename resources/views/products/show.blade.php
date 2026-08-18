@extends('layouts.app')
@section('title', $product->name . ' - سوق')

@section('content')
<div class="container mx-auto px-4 py-10">

    <div class="grid md:grid-cols-2 gap-10 bg-white rounded-xl shadow p-8"
         x-data="productVariants(
            {{ Illuminate\Support\Js::from($product->attributes->map(fn($attr) => [
                'id' => $attr->id,
                'values' => $attr->values->map(fn($v) => ['id' => $v->id])->values(),
            ])->values()) }},
            {{ Illuminate\Support\Js::from($product->variants->map(fn($v) => [
                'id' => $v->id,
                'stock' => $v->stock,
                'price' => $v->final_price,
                'value_ids' => $v->attributeValues->pluck('id')->all(),
            ])) }},
            {{ (float) ($product->hasActiveSale() ? $product->sale_price : $product->price) }}
         )"
         x-init="init()">

        {{-- الصور --}}
        <div x-data="{ activeImage: '{{ $product->main_image ? asset('storage/' . $product->main_image) : '' }}' }">
            <div class="h-80 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden mb-4">
                @if($product->main_image)
                    <img :src="activeImage" class="w-full h-full object-cover transition">
                @else
                    <span class="text-gray-400">لا توجد صورة</span>
                @endif
            </div>

            @if($product->images->isNotEmpty())
            <div class="grid grid-cols-4 gap-3">
                <button @click="activeImage = '{{ asset('storage/' . $product->main_image) }}'"
                        class="cursor-pointer h-20 bg-gray-100 rounded-lg overflow-hidden ring-2 ring-transparent hover:ring-accent transition">
                    <img src="{{ asset('storage/' . $product->main_image) }}" class="w-full h-full object-cover">
                </button>
                @foreach($product->images as $image)
                <button @click="activeImage = '{{ asset('storage/' . $image->image) }}'"
                        class="cursor-pointer h-20 bg-gray-100 rounded-lg overflow-hidden ring-2 ring-transparent hover:ring-accent transition">
                    <img src="{{ asset('storage/' . $image->image) }}" class="w-full h-full object-cover">
                </button>
                @endforeach
            </div>
            @endif
        </div>
        {{-- التفاصيل --}}
        <div>
            {{-- الفئات --}}
            <div class="flex flex-wrap gap-2 mb-3">
                @foreach($product->categories as $cat)
                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">{{ $cat->name }}</span>
                @endforeach
            </div>

            <h1 class="text-2xl md:text-3xl font-bold text-primary mb-4">{{ $product->name }}</h1>

            {{-- السعر (تفاعلي حسب الاختيار) --}}
            <div class="flex items-center gap-3 mb-6">
                <span class="text-2xl font-bold text-primary" x-text="currentPrice.toFixed(2) + ' ₪'"></span>
                @if($product->hasActiveSale())
                    <span class="text-lg text-gray-400 line-through" x-show="!hasVariants">{{ number_format($product->price, 2) }} ₪</span>
                    <span class="bg-sale text-white text-xs font-bold px-2 py-1 rounded" x-show="!hasVariants">خصم</span>
                @endif
            </div>

            {{-- اختيار الخصائص (Radio buttons) --}}
            @if($product->attributes->isNotEmpty())
            <div class="mb-6 space-y-4">
                @foreach($product->attributes as $attribute)
                <div>
                    <span class="text-sm font-medium text-gray-500 block mb-2">{{ $attribute->name }}</span>
                    <div class="flex flex-wrap gap-2">
                        @foreach($attribute->values as $val)
                        <button type="button"
                                @click="select({{ $attribute->id }}, {{ $val->id }})"
                                :class="selected[{{ $attribute->id }}] === {{ $val->id }}
                                    ? 'bg-primary text-white border-primary'
                                    : 'bg-white text-primary border-gray-200 hover:border-accent'"
                                class="cursor-pointer border rounded-lg px-4 py-2 text-sm font-medium transition">
                            {{ $val->value }}
                        </button>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            <p class="text-gray-600 leading-7 mb-6">{{ $product->description }}</p>

            {{-- حالة المخزون (تفاعلية) --}}
            <p class="text-sm mb-6">
                <template x-if="hasVariants && matchedVariant && matchedVariant.stock > 0">
                    <span class="text-success">متوفر بالمخزون (<span x-text="matchedVariant.stock"></span> قطعة)</span>
                </template>
                <template x-if="hasVariants && matchedVariant && matchedVariant.stock <= 0">
                    <span class="text-sale">غير متوفر حاليًا بهذا الخيار</span>
                </template>
                <template x-if="hasVariants && !matchedVariant">
                    <span class="text-gray-500">يرجى اختيار كل الخيارات أعلاه</span>
                </template>
                <template x-if="!hasVariants">
                    <span class="{{ $product->stock > 0 ? 'text-success' : 'text-sale' }}">
                        {{ $product->stock > 0 ? 'متوفر بالمخزون (' . $product->stock . ' قطعة)' : 'غير متوفر حاليًا' }}
                    </span>
                </template>
            </p>

            {{-- فورم الإضافة للسلة --}}
            <template x-if="canAddToCart">
                <div>
                    <div class="flex gap-4">
                        <input type="number" x-model.number="quantity" min="1" :max="maxQuantity"
                               class="w-20 border rounded-lg px-3 py-2 text-center focus:ring-2 focus:ring-accent focus:outline-none">
                        <button type="button" @click="addToCart()" :disabled="adding"
                                class="cursor-pointer flex-1 bg-accent hover:bg-accent-dark disabled:opacity-60 disabled:cursor-not-allowed text-white font-semibold py-3 rounded-lg transition">
                            <span x-show="!adding">أضف للسلة</span>
                            <span x-show="adding">جاري الإضافة...</span>
                        </button>
                    </div>
                    <p x-show="addError" x-text="addError" class="text-sale text-sm mt-2"></p>
                    <p x-show="addSuccess" class="text-success text-sm mt-2 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        تمت الإضافة للسلة بنجاح
                    </p>
                </div>
            </template>

            {{-- لو المنتج (بدون variants) نفدت كميته بالكامل: نموذج "نبهني" --}}
            @if($product->stock <= 0)
            <template x-if="!hasVariants">
                <div x-data="{
                    email: '',
                    notifyQuantity: 1,
                    submitted: false,
                    loading: false,
                    error: '',
                    async submit() {
                        this.loading = true;
                        this.error = '';
                        try {
                            const response = await fetch('{{ route('stock-notifications.store') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                },
                                body: JSON.stringify({
                                    product_id: {{ $product->id }},
                                    email: this.email,
                                    quantity: this.notifyQuantity,
                                }),
                            });
                            const data = await response.json();
                            if (data.success) {
                                this.submitted = true;
                            } else {
                                this.error = data.message || 'حدث خطأ، حاول مرة أخرى.';
                            }
                        } catch (e) {
                            this.error = 'حدث خطأ، حاول مرة أخرى.';
                        }
                        this.loading = false;
                    }
                }" class="bg-gray-50 border border-gray-200 rounded-xl p-5">

                    <template x-if="!submitted">
                        <div>
                            <p class="text-sm font-medium text-primary mb-3">
                                هذا المنتج غير متوفر حاليًا. سجّل بريدك والكمية المطلوبة ونبلغك فور توفره.
                            </p>
                            <div class="flex gap-2">
                                <input type="number" x-model="notifyQuantity" min="1" max="100"
                                       class="w-20 border rounded-lg px-3 py-2 text-sm text-center focus:ring-2 focus:ring-accent focus:outline-none">
                                <input type="email" x-model="email" placeholder="بريدك الإلكتروني"
                                       class="flex-1 border rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-accent focus:outline-none">
                                <button @click="submit()" :disabled="loading || !email"
                                        class="cursor-pointer bg-primary hover:bg-accent disabled:opacity-50 disabled:cursor-not-allowed text-white text-sm font-medium px-5 rounded-lg transition">
                                    <span x-show="!loading">نبهني</span>
                                    <span x-show="loading">جاري الإرسال...</span>
                                </button>
                            </div>
                            <p x-show="error" x-text="error" class="text-sale text-xs mt-2"></p>
                        </div>
                    </template>

                    <template x-if="submitted">
                        <p class="text-success text-sm font-medium flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            تم تسجيل طلبك بنجاح، رح نبلغك فور توفر المنتج.
                        </p>
                    </template>
                </div>
            </template>
            @endif
        </div>
    </div>

    {{-- منتجات ذات صلة --}}
    @if($relatedProducts->isNotEmpty())
    <div class="mt-12">
        <h2 class="text-2xl font-bold text-primary mb-6">منتجات ذات صلة</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($relatedProducts as $related)
                @include('components.product-card', ['product' => $related])
            @endforeach
        </div>
    </div>
    @endif
</div>

@endsection


@push('scripts')
<script>
    function productVariants(attributesData, variants, basePrice) {
        return {
            attributesData: attributesData,
            variants: variants,
            hasVariants: variants.length > 0,
            selected: {},
            matchedVariant: null,
            currentPrice: basePrice,
            quantity: 1,
            adding: false,
            addError: '',
            addSuccess: false,

            init() {
                const defaults = {};
                this.attributesData.forEach(attr => {
                    if (attr.values.length > 0) {
                        defaults[attr.id] = attr.values[0].id;
                    }
                });
                this.selected = defaults;
                this.updateMatch();
            },

            select(attributeId, valueId) {
                this.selected = { ...this.selected, [attributeId]: valueId };
                this.addError = '';
                this.addSuccess = false;
                this.updateMatch();
            },

            updateMatch() {
                if (!this.hasVariants) {
                    this.currentPrice = basePrice;
                    return;
                }

                const selectedIds = Object.values(this.selected).map(Number).sort((a, b) => a - b);

                this.matchedVariant = this.variants.find(v => {
                    const ids = [...v.value_ids].map(Number).sort((a, b) => a - b);
                    return ids.length === selectedIds.length && ids.every((id, i) => id === selectedIds[i]);
                }) || null;

                if (this.matchedVariant) {
                    this.currentPrice = this.matchedVariant.price;
                    this.quantity = Math.min(this.quantity, Math.max(this.matchedVariant.stock, 1));
                } else {
                    this.currentPrice = basePrice;
                }
            },

            get maxQuantity() {
                if (this.hasVariants) {
                    return this.matchedVariant ? this.matchedVariant.stock : 1;
                }
                return {{ $product->stock }};
            },

            get canAddToCart() {
                if (this.hasVariants) {
                    return this.matchedVariant && this.matchedVariant.stock > 0;
                }
                return {{ $product->stock }} > 0;
            },

            async addToCart() {
                if (!this.canAddToCart) return;
                this.adding = true;
                this.addError = '';
                this.addSuccess = false;
                try {
                    const result = await this.$store.cart.add(
                        {{ $product->id }},
                        this.quantity,
                        this.matchedVariant ? this.matchedVariant.id : null
                    );

                    if (result && result.success !== false) {
                        this.addSuccess = true;
                        setTimeout(() => this.addSuccess = false, 3000);
                    } else {
                        this.addError = (result && result.message) || 'حدث خطأ، حاول مرة أخرى.';
                    }
                } catch (e) {
                    console.error('Add to cart error:', e);
                    this.addError = 'حدث خطأ غير متوقع، حاول مرة أخرى.';
                } finally {
                    this.adding = false;
                }
            },
        };
    }
</script>
@endpush