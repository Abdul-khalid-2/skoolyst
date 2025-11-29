<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CouponApplicable extends Model
{
    use HasFactory;

    protected $table = 'coupon_applicables';

    protected $fillable = [
        'coupon_id',
        'applicable_id',
        'applicable_type'
    ];

    // Relationships
    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function applicable(): MorphTo
    {
        return $this->morphTo();
    }

    // Methods to check applicability
    public function isForShop(): bool
    {
        return $this->applicable_type === Shop::class;
    }

    public function isForSchool(): bool
    {
        return $this->applicable_type === School::class;
    }

    public function isForProduct(): bool
    {
        return $this->applicable_type === Product::class;
    }

    public function isForCategory(): bool
    {
        return $this->applicable_type === ProductCategory::class;
    }
}
