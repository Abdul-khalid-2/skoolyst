<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'school_id',
        'branch_id',
        'title',
        'content',
        'slug',
        'feature_image',
        'view_count',
        'status',
        'publish_at',
        'expire_at',
        'meta_title',
        'meta_description'
    ];

    protected $casts = [
        'publish_at' => 'datetime',
        'expire_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->title) . '-' . Str::random(6);
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty('title') && empty($model->getOriginal('slug'))) {
                $model->slug = Str::slug($model->title) . '-' . Str::random(6);
            }
        });
    }

    // Relationships
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function comments()
    {
        return $this->hasMany(AnnouncementComment::class)->where('status', 'approved');
    }

    public function allComments()
    {
        return $this->hasMany(AnnouncementComment::class);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('publish_at')
                    ->orWhere('publish_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('expire_at')
                    ->orWhere('expire_at', '>=', now());
            });
    }

    public function scopeForSchool($query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    public function scopeForBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    // Methods
    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    public function getFeatureImageUrlAttribute()
    {
        return $this->feature_image
            ? asset('website/' . $this->feature_image)
            : asset('website/images/default-announcement.jpg');
    }

    public function isPublished()
    {
        return $this->status === 'published' &&
            (!$this->publish_at || $this->publish_at <= now()) &&
            (!$this->expire_at || $this->expire_at >= now());
    }
}
