<?php
// app/Models/Shop.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'user_id',
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
        'total_reviews'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
        'rating' => 'decimal:2'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function schoolAssociations(): HasMany
    {
        return $this->hasMany(ShopSchoolAssociation::class);
    }

    public function associatedSchools()
    {
        return $this->belongsToMany(School::class, 'shop_school_associations')
            ->withPivot('association_type', 'discount_percentage', 'is_active', 'status')
            ->withTimestamps();
    }

    public function isOwner(User $user): bool
    {
        return $this->user_id === $user->id;
    }

    public function isAssociatedWithSchool(School $school): bool
    {
        return $this->schoolAssociations()
            ->where('school_id', $school->id)
            ->where('is_active', true)
            ->where('status', 'approved')
            ->exists();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($shop) {
            $shop->uuid = (string) \Illuminate\Support\Str::uuid();
        });
    }
}
