<?php
// app/Models/Product.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'shop_id',
        'category_id',
        'school_id',
        'association_id',
        'name',
        'slug',
        'sku',
        'description',
        'short_description',
        'product_type',
        'brand',
        'material',
        'color',
        'size',
        'base_price',
        'sale_price',
        'cost_price',
        'profit_margin',
        'stock_quantity',
        'low_stock_threshold',
        'manage_stock',
        'is_in_stock',
        'is_featured',
        'is_active',
        'is_approved',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'main_image_url',
        'image_gallery'
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'profit_margin' => 'decimal:2',
        'manage_stock' => 'boolean',
        'is_in_stock' => 'boolean',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'is_approved' => 'boolean',
        'image_gallery' => 'array'
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function association(): BelongsTo
    {
        return $this->belongsTo(ShopSchoolAssociation::class);
    }

    public function attributes(): HasOne
    {
        return $this->hasOne(ProductAttribute::class);
    }

    public function getCurrentPriceAttribute()
    {
        return $this->sale_price ?? $this->base_price;
    }

    public function getDiscountPercentageAttribute()
    {
        if (!$this->sale_price || $this->sale_price >= $this->base_price) {
            return 0;
        }

        return round((($this->base_price - $this->sale_price) / $this->base_price) * 100, 2);
    }

    public function isLowStock(): bool
    {
        return $this->manage_stock && $this->stock_quantity <= $this->low_stock_threshold;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('is_approved', true);
    }

    public function scopeForShop($query, Shop $shop)
    {
        return $query->where('shop_id', $shop->id);
    }

    public function scopeForSchool($query, School $school)
    {
        return $query->where('school_id', $school->id);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $product->uuid = (string) \Illuminate\Support\Str::uuid();

            // Auto-generate SKU if not provided
            if (empty($product->sku)) {
                $product->sku = 'SKU-' . strtoupper(uniqid());
            }
        });

        static::updating(function ($product) {
            // Update is_in_stock based on stock_quantity
            if ($product->manage_stock) {
                $product->is_in_stock = $product->stock_quantity > 0;
            }
        });
    }

    // In your Product model, update the getImageGalleryAttribute method:
    public function getImageGalleryAttribute($value)
    {
        if (empty($value)) {
            return [];
        }

        // If it's already an array, return it (but filter it)
        if (is_array($value)) {
            return $this->filterGalleryArray($value);
        }

        // Try to decode JSON
        $decoded = json_decode($value, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $this->filterGalleryArray($decoded);
        }

        // If JSON decoding fails, try to handle malformed data
        if (is_string($value)) {
            // Remove any malformed array syntax and extract paths
            $cleaned = str_replace(['[[],', '"]', '"'], '', $value);
            $paths = array_filter(explode(',', $cleaned));

            return $this->filterGalleryArray(array_map('trim', $paths));
        }

        // Return empty array as fallback
        return [];
    }

    // Add this helper method to filter the gallery array
    protected function filterGalleryArray(array $gallery): array
    {
        return array_filter($gallery, function ($item) {
            // Remove empty arrays, null values, and empty strings
            if (is_array($item) && empty($item)) {
                return false;
            }

            if ($item === null || $item === '' || $item === []) {
                return false;
            }

            return true;
        });
    }

    // Also update the hasGalleryImages method to be more robust
    public function hasGalleryImages(): bool
    {
        $gallery = $this->image_gallery ?? [];
        return !empty($gallery) && count($gallery) > 0;
    }

    // Simple helper method to check if main image exists
    public function hasMainImage(): bool
    {
        return !empty($this->main_image_url);
    }
}
