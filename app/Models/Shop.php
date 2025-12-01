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
        'priority'
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
        return $this->hasMany(ShopSchoolAssociation::class, 'shop_id');
    }

    public function associatedSchools(): BelongsToMany
    {
        return $this->belongsToMany(School::class, 'shop_school_associations')
            ->withPivot('association_type', 'discount_percentage', 'is_active', 'status')
            ->withTimestamps();
    }

    // Scope for shops with active school associations
    public function scopeWithActiveSchoolAssociations($query)
    {
        return $query->whereHas('schoolAssociations', function ($q) {
            $q->where('is_active', true)
                ->where('status', 'approved');
        });
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

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
