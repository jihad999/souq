<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductVariantController extends Controller
{
    public function generate(Product $product)
    {
        $attributes = $product->attributes()->with('values')->get();

        if ($attributes->isEmpty() || $attributes->contains(fn ($attr) => $attr->values->isEmpty())) {
            return back()->with('error', 'يجب إضافة خاصية واحدة على الأقل، مع قيمة واحدة على الأقل لكل خاصية، قبل توليد الخيارات.');
        }

        $valueGroups = $attributes->map(fn ($attr) => $attr->values->all())->values()->all();
        $combinations = $this->cartesianProduct($valueGroups);

        $existingCombos = $product->variants()
            ->with('attributeValues')
            ->get()
            ->map(fn ($v) => $v->attributeValues->pluck('id')->sort()->values()->all());

        $created = 0;

        foreach ($combinations as $combination) {
            $valueIds = collect($combination)->pluck('id')->sort()->values()->all();

            $alreadyExists = $existingCombos->contains(fn ($combo) => $combo === $valueIds);

            if ($alreadyExists) {
                continue;
            }

            $variant = $product->variants()->create([
                'sku' => strtoupper(Str::random(8)),
                'stock' => 0,
                'price_adjustment' => 0,
            ]);

            $variant->attributeValues()->attach($valueIds);
            $created++;
        }

        $message = $created > 0
            ? "تم توليد {$created} خيار جديد بنجاح."
            : 'كل التركيبات الممكنة موجودة مسبقًا، لم يتم إنشاء خيارات جديدة.';

        return back()->with('success', $message);
    }

    public function update(Request $request, Product $product, ProductVariant $variant)
    {
        abort_if($variant->product_id !== $product->id, 404);

        $request->validate([
            'stock' => ['required', 'integer', 'min:0'],
            'price_adjustment' => ['required', 'numeric'],
            'sku' => ['nullable', 'string', 'max:255'],
        ]);

        $variant->update($request->only('stock', 'price_adjustment', 'sku'));

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'تم التحديث بنجاح.']);
        }

        return back()->with('success', 'تم تحديث الخيار بنجاح.');
    }

    public function destroy(Product $product, ProductVariant $variant)
    {
        abort_if($variant->product_id !== $product->id, 404);

        $variant->delete();

        return back()->with('success', 'تم حذف الخيار بنجاح.');
    }

    private function cartesianProduct(array $arrays): array
    {
        if (empty($arrays)) {
            return [];
        }

        $result = [[]];

        foreach ($arrays as $propertyValues) {
            $temp = [];
            foreach ($result as $resultItem) {
                foreach ($propertyValues as $propertyValue) {
                    $temp[] = array_merge($resultItem, [$propertyValue]);
                }
            }
            $result = $temp;
        }

        return $result;
    }
}