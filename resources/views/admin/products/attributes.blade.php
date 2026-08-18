@extends('layouts.admin')
@section('title', 'خصائص المنتج - سوق')
@section('page-title', 'خصائص وخيارات: ' . $product->name)

@section('content')

<div class="mb-6">
    <a href="{{ route('admin.products.edit', $product) }}" class="cursor-pointer text-sm text-gray-500 hover:text-accent">
        ← الرجوع لتعديل المنتج
    </a>
</div>

{{-- إضافة خاصية جديدة --}}
<div class="bg-white rounded-xl shadow p-6 mb-6">
    <h2 class="text-lg font-bold text-primary mb-4">إضافة خاصية جديدة</h2>
    <form action="{{ route('admin.products.attributes.store', $product) }}" method="POST" class="flex gap-3">
        @csrf
        <input type="text" name="name" placeholder="مثال: اللون، المقاس، السعة التخزينية..."
               class="flex-1 border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none">
        <button type="submit" class="cursor-pointer bg-primary hover:bg-accent text-white font-medium px-6 py-2 rounded-lg transition">
            إضافة
        </button>
    </form>
</div>

{{-- الخصائص الحالية وقيمها --}}
@forelse($product->attributes as $attribute)
<div class="bg-white rounded-xl shadow p-6 mb-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-bold text-primary">{{ $attribute->name }}</h3>
        <form action="{{ route('admin.products.attributes.destroy', [$product, $attribute]) }}" method="POST"
              x-data
              @submit.prevent="$store.confirm.show('حذف خاصية \'{{ $attribute->name }}\' وكل قيمها؟', () => $el.submit())">
            @csrf
            @method('DELETE')
            <button type="submit" class="cursor-pointer text-sale text-sm hover:underline">حذف الخاصية</button>
        </form>
    </div>

    <div class="flex flex-wrap gap-2 mb-4">
        @foreach($attribute->values as $value)
        <div class="flex items-center gap-2 bg-gray-50 border rounded-lg px-3 py-1.5">
            @if($value->hex_code)
                <span class="w-4 h-4 rounded-full border" style="background-color: {{ $value->hex_code }};"></span>
            @endif
            <span class="text-sm">{{ $value->value }}</span>
            <form action="{{ route('admin.products.attributes.values.destroy', [$product, $value]) }}" method="POST"
                  x-data
                  @submit.prevent="$store.confirm.show('حذف القيمة \'{{ $value->value }}\'؟', () => $el.submit())">
                @csrf
                @method('DELETE')
                <button type="submit" class="cursor-pointer text-gray-400 hover:text-sale">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </form>
        </div>
        @endforeach
    </div>

    <form action="{{ route('admin.products.attributes.values.store', [$product, $attribute]) }}" method="POST" class="flex gap-3">
        @csrf
        <input type="text" name="value" placeholder="قيمة جديدة (مثال: أحمر، L، 128GB)"
               class="flex-1 border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-accent focus:outline-none">
        <input type="text" name="hex_code" placeholder="#FF0000 (اختياري، للألوان فقط)"
               class="w-48 border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-accent focus:outline-none">
        <button type="submit" class="cursor-pointer bg-gray-100 hover:bg-gray-200 text-primary text-sm font-medium px-4 py-2 rounded-lg transition">
            + إضافة قيمة
        </button>
    </form>
</div>
@empty
<div class="bg-white rounded-xl shadow p-8 text-center text-gray-500 mb-6">
    لا يوجد خصائص لهذا المنتج بعد. أضف خاصية من الأعلى (مثل اللون أو المقاس).
</div>
@endforelse

{{-- توليد الخيارات (Variants) --}}
@if($product->attributes->isNotEmpty())
<div class="bg-white rounded-xl shadow p-6 mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-primary">توليد الخيارات (Variants)</h2>
            <p class="text-sm text-gray-500">يقوم بإنشاء كل التركيبات الممكنة من الخصائص أعلاه تلقائيًا.</p>
        </div>
        <form action="{{ route('admin.products.variants.generate', $product) }}" method="POST">
            @csrf
            <button type="submit" class="cursor-pointer bg-accent hover:bg-accent-dark text-white font-medium px-5 py-2.5 rounded-lg transition">
                توليد الخيارات
            </button>
        </form>
    </div>
</div>
@endif

{{-- جدول الخيارات الحالية --}}
@if($product->variants->isNotEmpty())
<div class="bg-white rounded-xl shadow overflow-hidden">
    <div class="p-6 border-b">
        <h2 class="text-lg font-bold text-primary">الخيارات الحالية ({{ $product->variants->count() }})</h2>
    </div>
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr class="text-right text-gray-500">
                <th class="p-4">التركيبة</th>
                <th class="p-4">SKU</th>
                <th class="p-4">المخزون</th>
                <th class="p-4">فرق السعر</th>
                <th class="p-4">الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($product->variants as $variant)
            <tr class="border-t" x-data="{ editing: false, stock: {{ $variant->stock }}, priceAdj: {{ $variant->price_adjustment }}, sku: '{{ $variant->sku }}' }">
                <td class="p-4 font-medium text-primary">{{ $variant->label }}</td>
                <td class="p-4">
                    <span x-show="!editing">{{ $variant->sku }}</span>
                    <input type="text" x-show="editing" x-model="sku" class="border rounded px-2 py-1 text-sm w-32">
                </td>
                <td class="p-4">
                    <span x-show="!editing">{{ $variant->stock }}</span>
                    <input type="number" x-show="editing" x-model.number="stock" class="border rounded px-2 py-1 text-sm w-20">
                </td>
                <td class="p-4">
                    <span x-show="!editing">{{ number_format($variant->price_adjustment, 2) }} ₪</span>
                    <input type="number" step="0.01" x-show="editing" x-model.number="priceAdj" class="border rounded px-2 py-1 text-sm w-24">
                </td>
                <td class="p-4">
                    <div class="flex items-center gap-3">
                        <button type="button" x-show="!editing" @click="editing = true" class="cursor-pointer text-accent hover:underline">تعديل</button>

                        <button type="button" x-show="editing"
                                @click="
                                    fetch('{{ route('admin.products.variants.update', [$product, $variant]) }}', {
                                        method: 'PATCH',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'Accept': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                        },
                                        body: JSON.stringify({ stock, price_adjustment: priceAdj, sku }),
                                    }).then(() => { editing = false; window.location.reload(); })
                                "
                                class="cursor-pointer text-success hover:underline">حفظ</button>
                        <button type="button" x-show="editing" @click="editing = false" class="cursor-pointer text-gray-400 hover:underline">إلغاء</button>

                        <form action="{{ route('admin.products.variants.destroy', [$product, $variant]) }}" method="POST"
                              x-data
                              @submit.prevent="$store.confirm.show('حذف هذا الخيار؟', () => $el.submit())">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="cursor-pointer text-sale hover:underline">حذف</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

@endsection