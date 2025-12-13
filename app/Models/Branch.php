<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'name',
        'address',
        'city',
        'contact_number',
        'branch_head_name',
        'description',
        'school_type',
        'fee_structure',
        'curriculums',
        'classes',
        'latitude',
        'longitude',
        'is_main_branch',
        'status'
    ];

    protected $casts = [
        'is_main_branch' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'features' => 'array',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(BranchImage::class)->orderBy('sort_order')->orderBy('created_at', 'desc');
    }

    public function galleryImages()
    {
        return $this->images()->where('type', 'gallery')->where('status', 'active');
    }

    public function bannerImages()
    {
        return $this->images()->where('type', 'banner')->where('status', 'active');
    }

    public function featuredImages()
    {
        return $this->images()->where('is_featured', true)->where('status', 'active');
    }

    public function mainBanner()
    {
        return $this->images()->where('is_main_banner', true)->where('status', 'active')->first();
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public static function getSchoolTypeOptions(): array
    {
        return [
            'Co-Ed' => 'Co-Educational',
            'Boys' => 'Boys Only',
            'Girls' => 'Girls Only',
        ];
    }

    public function getFeaturesArray(): array
    {
        return $this->features ?? [];
    }
}
