<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'code',
        'name',
        'description',
        'discount_type',
        'discount_value',
        'minimum_order_amount',
        'maximum_discount_amount',
        'usage_limit',
        'usage_count',
        'usage_per_customer',
        'valid_from',
        'valid_until',
        'scope',
        'is_active',
        'is_featured'
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'minimum_order_amount' => 'decimal:2',
        'maximum_discount_amount' => 'decimal:2',
        'usage_count' => 'integer',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    // Relationships
    public function couponUsage(): HasMany
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function applicables(): HasMany
    {
        return $this->hasMany(CouponApplicable::class);
    }

    // Polymorphic relationships for different scopes
    public function shops(): MorphToMany
    {
        return $this->morphedByMany(Shop::class, 'applicable', 'coupon_applicables');
    }

    public function schools(): MorphToMany
    {
        return $this->morphedByMany(School::class, 'applicable', 'coupon_applicables');
    }

    public function products(): MorphToMany
    {
        return $this->morphedByMany(Product::class, 'applicable', 'coupon_applicables');
    }

    public function categories(): MorphToMany
    {
        return $this->morphedByMany(ProductCategory::class, 'applicable', 'coupon_applicables');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('valid_from')
                    ->orWhere('valid_from', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('valid_until')
                    ->orWhere('valid_until', '>=', now());
            });
    }

    public function scopeGlobal($query)
    {
        return $query->where('scope', 'global');
    }

    public function scopeShopSpecific($query)
    {
        return $query->where('scope', 'shop_specific');
    }

    public function scopeSchoolSpecific($query)
    {
        return $query->where('scope', 'school_specific');
    }

    public function scopeProductSpecific($query)
    {
        return $query->where('scope', 'product_specific');
    }

    // Methods
    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->valid_from && $this->valid_from->isFuture()) {
            return false;
        }

        if ($this->valid_until && $this->valid_until->isPast()) {
            return false;
        }

        if ($this->usage_limit && $this->usage_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    public function calculateDiscount(float $orderAmount): float
    {
        $discount = 0;

        switch ($this->discount_type) {
            case 'percentage':
                $discount = ($orderAmount * $this->discount_value) / 100;
                if ($this->maximum_discount_amount && $discount > $this->maximum_discount_amount) {
                    $discount = $this->maximum_discount_amount;
                }
                break;
            case 'fixed_amount':
                $discount = $this->discount_value;
                break;
            case 'free_shipping':
                // This would be handled differently in order calculation
                $discount = 0; // Actual shipping cost would be subtracted
                break;
        }

        return min($discount, $orderAmount);
    }

    public function canBeUsedByUser(User $user): bool
    {
        if (!$this->usage_per_customer) {
            return true;
        }

        $userUsageCount = $this->couponUsage()
            ->where('user_id', $user->id)
            ->count();

        return $userUsageCount < $this->usage_per_customer;
    }

    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }
}
