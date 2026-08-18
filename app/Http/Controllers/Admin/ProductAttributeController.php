<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use Illuminate\Http\Request;

class ProductAttributeController extends Controller
{
    public function index(Product $product)
    {
        $product->load('attributes.values', 'variants.attributeValues.attribute');

        return view('admin.products.attributes', compact('product'));
    }

    public function store(Request $request, Product $product)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $product->attributes()->create([
            'name' => $request->name,
            'order' => $product->attributes()->count(),
        ]);

        return back()->with('success', 'تمت إضافة الخاصية بنجاح.');
    }

    public function destroy(Product $product, ProductAttribute $attribute)
    {
        abort_if($attribute->product_id !== $product->id, 404);

        $attribute->delete();

        return back()->with('success', 'تم حذف الخاصية بنجاح. تنبيه: أي خيارات (Variants) كانت تستخدم هذه الخاصية قد تأثرت.');
    }

    public function storeValue(Request $request, Product $product, ProductAttribute $attribute)
    {
        abort_if($attribute->product_id !== $product->id, 404);

        $request->validate([
            'value' => ['required', 'string', 'max:255'],
            'hex_code' => ['nullable', 'string', 'max:7'],
        ]);

        $attribute->values()->create([
            'value' => $request->value,
            'hex_code' => $request->hex_code,
            'order' => $attribute->values()->count(),
        ]);

        return back()->with('success', 'تمت إضافة القيمة بنجاح.');
    }

    public function destroyValue(Product $product, ProductAttributeValue $value)
    {
        abort_if($value->attribute->product_id !== $product->id, 404);

        $value->delete();

        return back()->with('success', 'تم حذف القيمة بنجاح.');
    }
}