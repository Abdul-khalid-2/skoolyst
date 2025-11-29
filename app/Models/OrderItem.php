<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'order_id',
        'product_id',
        'shop_id',
        'category_id',
        'school_id',
        'association_id',
        'product_name',
        'product_description',
        'product_sku',
        'product_image',
        'product_attributes',
        'unit_price',
        'sale_price',
        'discount_amount',
        'tax_amount',
        'quantity',
        'total_price',
        'category_name',
        'status',
        'cancellation_reason',
        'cancelled_at'
    ];

    protected $casts = [
        'product_attributes' => 'array',
        'unit_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_price' => 'decimal:2',
        'cancelled_at' => 'datetime',
    ];

    // Relationships
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function association(): BelongsTo
    {
        return $this->belongsTo(ShopSchoolAssociation::class, 'association_id');
    }

    // Methods
    public function calculateLineTotal(): float
    {
        $price = $this->sale_price ?? $this->unit_price;
        return ($price * $this->quantity) - $this->discount_amount + $this->tax_amount;
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']) &&
            in_array($this->order->status, ['pending', 'confirmed']);
    }
}
