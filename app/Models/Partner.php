<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Partner extends Model
{
    protected $fillable = [
        'company_name', 'contact_name', 'email', 'phone',
        'website', 'message', 'logo', 'status',
        'reviewed_by', 'reviewed_at', 'show_on_site',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved')->where('show_on_site', true);
    }
}