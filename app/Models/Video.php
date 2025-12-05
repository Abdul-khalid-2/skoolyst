<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'category_id',
        'user_id',
        'school_id',
        'shop_id',
        'title',
        'slug',
        'description',
        'embed_video_link',
        'thumbnail',
        'video_id',
        'views',
        'likes_count',
        'comments_count',
        'is_featured',
        'is_approved',
        'status',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'published_at',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_approved' => 'boolean',
        'published_at' => 'datetime',
        'views' => 'integer',
        'likes_count' => 'integer',
        'comments_count' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($video) {
            if (empty($video->uuid)) {
                $video->uuid = (string) \Illuminate\Support\Str::uuid();
            }
            if (empty($video->slug)) {
                $video->slug = \Illuminate\Support\Str::slug($video->title);
            }
            if (empty($video->video_id)) {
                $video->video_id = self::extractYouTubeId($video->embed_video_link);
            }
        });

        static::updating(function ($video) {
            if ($video->isDirty('embed_video_link')) {
                $video->video_id = self::extractYouTubeId($video->embed_video_link);
            }
        });
    }

    private static function extractYouTubeId($url): ?string
    {
        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/';
        preg_match($pattern, $url, $matches);
        return $matches[1] ?? null;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(VideoCategory::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(VideoReaction::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(VideoComment::class)->whereNull('parent_id')->where('is_approved', true);
    }

    public function allComments(): HasMany
    {
        return $this->hasMany(VideoComment::class);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopePopular($query)
    {
        return $query->orderBy('views', 'desc');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('published_at', 'desc');
    }

    public function scopeBySchool($query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    public function scopeByShop($query, $shopId)
    {
        return $query->where('shop_id', $shopId);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }
}
