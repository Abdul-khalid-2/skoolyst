<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'regular_fees',
        'discounted_fees',
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

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }
}
