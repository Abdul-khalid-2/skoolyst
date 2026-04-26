<?php

namespace App\Models;

use App\Enums\ActiveStatus;
use App\Enums\ContentStatus;
use App\Enums\FeeStructureType;
use App\Enums\SchoolType;
use App\Enums\SchoolVisibility;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'banner_image',
        'description',
        'address',
        'city',
        'contact_number',
        'email',
        'website',
        'facilities',
        'school_type',
        'fee_structure_type',
        'regular_fees',
        'discounted_fees',
        'class_wise_fees',
        'admission_fees',
        'status',
        'visibility',
        'publish_date',
        // 'logo',
        'banner_title',
        'banner_tagline'
    ];

    // Generate UUID automatically
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    protected $casts = [
        'publish_date' => 'datetime',
        'school_type' => SchoolType::class,
        'fee_structure_type' => FeeStructureType::class,
        'status' => ActiveStatus::class,
        'visibility' => SchoolVisibility::class,
    ];
    public function branches()
    {
        return $this->hasMany(Branch::class);
    }

    public function mainBranch()
    {
        return $this->hasOne(Branch::class)->where('is_main_branch', true);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
    public function user()
    {
        return $this->hasOne(User::class, 'school_id');
    }

    // Add shop associations relationship
    public function shopAssociations(): HasMany
    {
        return $this->hasMany(ShopSchoolAssociation::class, 'school_id');
    }

    public function associatedShops(): BelongsToMany
    {
        return $this->belongsToMany(Shop::class, 'shop_school_associations')
            ->withPivot('association_type', 'discount_percentage', 'is_active', 'status')
            ->withTimestamps();
    }

    // Scope for active shop associations
    public function scopeWithActiveShopAssociations($query)
    {
        return $query->whereHas('shopAssociations', function ($q) {
            $q->where('is_active', true)
                ->where('status', 'approved');
        });
    }

    // Check if school has active shop associations
    public function hasActiveShopAssociations(): bool
    {
        return $this->shopAssociations()
            ->where('is_active', true)
            ->where('status', 'approved')
            ->exists();
    }

    // Scope for school admins to see only their schools
    public function scopeForUser($query, $user)
    {
        if ($user->isSuperAdmin()) {
            return $query;
        }

        return $query->where('id', $user->school_id);
    }

    public function images()
    {
        return $this->hasMany(SchoolImage::class);
    }


    public function getBannerUrlAttribute(): string
    {
        return $this->banner_image ? asset('website/' . $this->banner_image) : asset('website/images/male_advocate_avatar.jpg');
    }

    public function features()
    {
        return $this->belongsToMany(Feature::class, 'school_feature')
            ->withPivot('description', 'priority')
            ->orderBy('priority')
            ->withTimestamps();
    }

    public function curriculums()
    {
        return $this->belongsToMany(Curriculum::class, 'school_curriculum')
            ->withTimestamps();
    }

    // Add the profile relationship
    public function profile()
    {
        return $this->hasOne(SchoolProfile::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(SchoolTranslation::class);
    }

    public function translationForLocale(string $locale): ?SchoolTranslation
    {
        if ($this->relationLoaded('translations')) {
            return $this->translations->firstWhere('locale', $locale);
        }

        return $this->translations()->where('locale', $locale)->first();
    }

    /**
     * User-facing string for the current app locale, falling back to English (schools + school_profiles).
     */
    public function localized(string $field): string
    {
        if (in_array($field, ['mission', 'vision', 'school_motto'], true) && ! $this->relationLoaded('profile')) {
            $this->load('profile');
        }

        $locale = app()->getLocale();
        $fallback = config('app.fallback_locale', 'en');
        $default = $this->getDefaultLocaleString($field);

        if ($locale === $fallback || $locale === 'en') {
            return $default;
        }

        $tr = $this->translationForLocale($locale);
        if (! $tr) {
            return $default;
        }

        $value = $tr->getAttribute($field);
        if ($value === null || $value === '') {
            return $default;
        }

        return (string) $value;
    }

    protected function getDefaultLocaleString(string $field): string
    {
        $raw = null;
        if (in_array($field, ['name', 'description', 'facilities', 'banner_title', 'banner_tagline'], true)) {
            $raw = $this->attributes[$field] ?? null;
        } elseif (in_array($field, ['mission', 'vision', 'school_motto'], true)) {
            $raw = $this->profile?->getAttribute($field);
        }

        if ($raw === null) {
            return '';
        }

        return (string) $raw;
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }

    public function recentAnnouncements()
    {
        return $this->hasMany(Announcement::class)
            ->where('status', ContentStatus::Published)
            ->where(function ($query) {
                $query->whereNull('publish_at')
                    ->orWhere('publish_at', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('expire_at')
                    ->orWhere('expire_at', '>=', now());
            })
            ->orderBy('created_at', 'desc')
            ->limit(5);
    }

    public function hasNewAnnouncements()
    {
        // Consider announcements as "new" if created within the last 7 days
        return $this->announcements()
            ->where('status', ContentStatus::Published)
            ->where('created_at', '>=', now()->subDays(7))
            ->exists();
    }

    public function getLatestAnnouncementDate()
    {
        $latestAnnouncement = $this->announcements()
            ->where('status', ContentStatus::Published)
            ->orderBy('created_at', 'desc')
            ->first();

        return $latestAnnouncement ? $latestAnnouncement->created_at : null;
    }
}
