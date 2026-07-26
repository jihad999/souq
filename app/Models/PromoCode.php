<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PromoCode extends Model
{
    protected $fillable = [
        'code', 'type', 'value', 'min_order_amount',
        'usage_limit', 'used_count', 'starts_at', 'expires_at', 'is_active',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function isValid(float $orderAmount): bool
    {
        if (! $this->is_active) return false;
        if ($this->starts_at && $this->starts_at > now()) return false;
        if ($this->expires_at && $this->expires_at < now()) return false;
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;
        if ($this->min_order_amount && $orderAmount < $this->min_order_amount) return false;

        return true;
    }

    public function calculateDiscount(float $amount): float
    {
        return $this->type === 'percentage'
            ? $amount * ($this->value / 100)
            : min($this->value, $amount);
    }
}
