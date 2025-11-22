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
        'association_type',
        'discount_percentage',
        'is_active',
    ];

    protected $casts = [
        'discount_percentage' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the shop that owns the association.
     */
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Get the school that owns the association.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Scope a query to only include active associations.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include associations of a specific type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('association_type', $type);
    }

    /**
     * Check if the association is official.
     */
    public function isOfficial(): bool
    {
        return $this->association_type === 'official';
    }

    /**
     * Check if the association is preferred.
     */
    public function isPreferred(): bool
    {
        return $this->association_type === 'preferred';
    }

    /**
     * Check if the association is affiliated.
     */
    public function isAffiliated(): bool
    {
        return $this->association_type === 'affiliated';
    }

    /**
     * Get the association type label.
     */
    public function getAssociationTypeLabelAttribute(): string
    {
        return match ($this->association_type) {
            'official' => 'Official Partner',
            'preferred' => 'Preferred Vendor',
            'affiliated' => 'Affiliated Shop',
            'general' => 'General Association',
            default => ucfirst($this->association_type),
        };
    }
}
