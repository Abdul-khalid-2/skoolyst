<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShopSchoolAssociation extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'shop_id',
        'school_id',
        'created_by',
        'association_type',
        'discount_percentage',
        'is_active',
        'status',
        'approved_at',
        'approved_by',
        'can_add_products',
        'can_manage_products',
        'can_view_analytics',
        'notes'
    ];

    protected $casts = [
        'discount_percentage' => 'decimal:2',
        'is_active' => 'boolean',
        'approved_at' => 'datetime',
        'can_add_products' => 'boolean',
        'can_manage_products' => 'boolean',
        'can_view_analytics' => 'boolean'
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'association_id');
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved' && $this->is_active;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($association) {
            $association->uuid = (string) \Illuminate\Support\Str::uuid();
        });
    }
}
