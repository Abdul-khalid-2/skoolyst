<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'user_id',
        'school_id',
        'name',
        'slug',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'country',
        'latitude',
        'longitude',
        'shop_type',
        'description',
        'logo_url',
        'banner_url',
        'is_verified',
        'is_active',
        'rating',
        'total_reviews',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'rating' => 'decimal:2',
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user that owns the shop.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the school that the shop is associated with.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the products for the shop.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the shop's school associations.
     */
    public function shopSchoolAssociations(): HasMany
    {
        return $this->hasMany(ShopSchoolAssociation::class);
    }

    /**
     * Get the associated schools through shop school associations.
     */
    public function associatedSchools(): BelongsToMany
    {
        return $this->belongsToMany(School::class, 'shop_school_associations')
            ->withPivot('association_type', 'discount_percentage', 'is_active')
            ->withTimestamps();
    }

    /**
     * Scope a query to only include active shops.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include verified shops.
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope a query to only include shops of a specific type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('shop_type', $type);
    }

    /**
     * Get the full address of the shop.
     */
    public function getFullAddressAttribute(): string
    {
        $addressParts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->country,
        ]);

        return implode(', ', $addressParts);
    }

    /**
     * Check if the shop has a logo.
     */
    public function hasLogo(): bool
    {
        return !empty($this->logo_url);
    }

    /**
     * Check if the shop has a banner.
     */
    public function hasBanner(): bool
    {
        return !empty($this->banner_url);
    }

    /**
     * Get the logo URL.
     */
    public function getLogoUrlAttribute($value): ?string
    {
        return $value ? asset('website/' . $value) : null;
    }

    /**
     * Get the banner URL.
     */
    public function getBannerUrlAttribute($value): ?string
    {
        return $value ? asset('website/' . $value) : null;
    }

    /**
     * Calculate the average rating.
     */
    public function updateRating(): void
    {
        // This would typically be called when a new review is added
        // For now, it's a placeholder for future implementation
    }
}
