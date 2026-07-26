<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'price',
        'sale_price', 'sale_starts_at', 'sale_ends_at',
        'stock', 'main_image', 'is_active',
    ];

    protected $casts = [
        'sale_starts_at' => 'datetime',
        'sale_ends_at' => 'datetime',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    // هل المنتج عليه خصم فعّال هلأ (بدون كود)
    public function hasActiveSale(): bool
    {
        if (! $this->sale_price) {
            return false;
        }

        $now = now();
        $startsOk = ! $this->sale_starts_at || $this->sale_starts_at <= $now;
        $endsOk = ! $this->sale_ends_at || $this->sale_ends_at >= $now;

        return $startsOk && $endsOk;
    }

    public function getFinalPriceAttribute(): float
    {
        return $this->hasActiveSale() ? $this->sale_price : $this->price;
    }

    // Scope لصفحة العروض: بس المنتجات يلي عليها خصم فعّال هلأ
    public function scopeOnSale($query)
    {
        return $query->whereNotNull('sale_price')
            ->where(function ($q) {
                $q->whereNull('sale_starts_at')->orWhere('sale_starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('sale_ends_at')->orWhere('sale_ends_at', '>=', now());
            });
    }
}