<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'shop_id',
        'category_id',
        'school_id',
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
        'image_gallery',
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
        'image_gallery' => 'array',
    ];

    /**
     * Get the shop that owns the product.
     */
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Get the category that the product belongs to.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class);
    }

    /**
     * Get the school that the product is associated with.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the product attributes.
     */
    public function attributes(): HasOne
    {
        return $this->hasOne(ProductAttribute::class);
    }

    /**
     * Scope a query to only include active products.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include approved products.
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope a query to only include featured products.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to only include products in stock.
     */
    public function scopeInStock($query)
    {
        return $query->where('is_in_stock', true);
    }

    /**
     * Scope a query to only include products of a specific type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('product_type', $type);
    }

    /**
     * Get the current price (sale price if available, otherwise base price).
     */
    protected function currentPrice(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->sale_price ?? $this->base_price,
        );
    }

    /**
     * Check if the product is on sale.
     */
    public function isOnSale(): bool
    {
        return !is_null($this->sale_price) && $this->sale_price < $this->base_price;
    }

    /**
     * Calculate the discount percentage.
     */
    public function discountPercentage(): float
    {
        if (!$this->isOnSale()) {
            return 0;
        }

        return (($this->base_price - $this->sale_price) / $this->base_price) * 100;
    }

    /**
     * Check if the product is low in stock.
     */
    public function isLowStock(): bool
    {
        return $this->manage_stock && $this->stock_quantity <= $this->low_stock_threshold;
    }

    /**
     * Check if the product is out of stock.
     */
    public function isOutOfStock(): bool
    {
        return !$this->is_in_stock || ($this->manage_stock && $this->stock_quantity <= 0);
    }

    /**
     * Get the main image URL.
     */
    public function getMainImageUrlAttribute($value): ?string
    {
        return $value ? asset('website/' . $value) : null;
    }

    /**
     * Get the image gallery URLs.
     */
    public function getImageGalleryUrlsAttribute(): array
    {
        if (empty($this->image_gallery)) {
            return [];
        }

        return array_map(function ($image) {
            return asset('website/' . $image);
        }, $this->image_gallery);
    }

    /**
     * Update stock status based on quantity.
     */
    public function updateStockStatus(): void
    {
        if ($this->manage_stock) {
            $this->is_in_stock = $this->stock_quantity > 0;
            $this->save();
        }
    }

    /**
     * Calculate profit margin if not set.
     */
    public function calculateProfitMargin(): void
    {
        if (is_null($this->profit_margin) && $this->cost_price && $this->base_price) {
            $this->profit_margin = (($this->base_price - $this->cost_price) / $this->cost_price) * 100;
            $this->save();
        }
    }
}
