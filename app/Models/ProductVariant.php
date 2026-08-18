<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductVariant extends Model
{
    protected $fillable = ['product_id', 'sku', 'stock', 'price_adjustment'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function attributeValues(): BelongsToMany
    {
        return $this->belongsToMany(
            ProductAttributeValue::class,
            'product_variant_values',
            'product_variant_id',
            'product_attribute_value_id'
        );
    }

    public function getLabelAttribute(): string
    {
        return $this->attributeValues
            ->load('attribute')
            ->map(fn ($val) => "{$val->attribute->name}: {$val->value}")
            ->implode('، ');
    }

    public function getFinalPriceAttribute(): float
    {
        $basePrice = $this->product->hasActiveSale() ? $this->product->sale_price : $this->product->price;

        return $basePrice + $this->price_adjustment;
    }
}