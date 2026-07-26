<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientLogo extends Model
{
    protected $fillable = ['name', 'logo', 'website', 'testimonial', 'is_active', 'order'];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }
}