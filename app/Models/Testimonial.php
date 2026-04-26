<?php

namespace App\Models;

use App\Enums\ModerationStatus;
use App\Enums\TestimonialExperienceRating;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'user_id',
        'message',
        'rating',
        'author_name',
        'author_email',
        'author_location',
        'author_role',
        'avatar',
        'status',
        'featured',
        'show_on_homepage',
        'platform_features_liked',
        'experience_rating',
        'approved_at'
    ];

    protected $casts = [
        'platform_features_liked' => 'array',
        'approved_at' => 'datetime',
        'featured' => 'boolean',
        'show_on_homepage' => 'boolean',
        'status' => ModerationStatus::class,
        'experience_rating' => TestimonialExperienceRating::class,
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($testimonial) {
            $testimonial->uuid = \Illuminate\Support\Str::uuid();
        });
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('status', ModerationStatus::Approved);
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeShowOnHomepage($query)
    {
        return $query->where('show_on_homepage', true);
    }

    public function scopeRecent($query, $limit = 6)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    // Accessors
    public function getInitialsAttribute(): string
    {
        $name = $this->author_name;
        $words = explode(' ', $name);

        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr(end($words), 0, 1));
        }

        return strtoupper(substr($name, 0, 2));
    }

    public function getFormattedDateAttribute(): string
    {
        return $this->created_at->format('F Y');
    }

    public function getStarsAttribute(): string
    {
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $this->rating) {
                $stars .= '<i class="fas fa-star text-warning"></i>';
            } else {
                $stars .= '<i class="far fa-star text-muted"></i>';
            }
        }
        return $stars;
    }
}
