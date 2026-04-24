<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlogPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'heading',
        'user_id',
        'school_id',
        'blog_category_id',
        'title',
        'slug',
        'structure',
        'excerpt',
        'content',
        'featured_image',
        'tags',
        'view_count',
        'read_time',
        'status',
        'is_featured',
        'published_at',
        'meta_title',
        'meta_description',
        'banner',
        'image',
        'rich_text',
        'text_left_image_right',
        'custom_html',
        'canvas_elements'
    ];

    protected $casts = [
        'heading' => 'array',
        'structure' => 'array',
        'tags' => 'array',
        'banner' => 'array',
        'image' => 'array',
        'rich_text' => 'array',
        'text_left_image_right' => 'array',
        'custom_html' => 'array',
        'canvas_elements' => 'array',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
        'total_tracked_read_minutes' => 'decimal:5',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function resolveRouteBinding($value, $field = null)
    {
        if ($field !== null) {
            return static::where($field, $value)->first();
        }
        if (is_numeric($value) && (string) (int) $value === (string) $value) {
            $byId = static::query()->whereKey((int) $value)->first();
            if ($byId) {
                return $byId;
            }
        }

        if ($bySlug = static::where('slug', $value)->first()) {
            return $bySlug;
        }

        // Old bug: route('admin.blog-posts.show', [category_slug, post_slug]) put the
        // post slug in the query string (e.g. ?long-post-slug or ?0=long-post-slug).
        $req = request();
        if ($req !== null) {
            $candidates = [];
            foreach ($req->query() as $k => $v) {
                if (is_string($k) && str_contains($k, '-')) {
                    $candidates[] = $k;
                }
                if (is_string($v) && str_contains($v, '-')) {
                    $candidates[] = $v;
                }
            }
            foreach (array_unique($candidates) as $slug) {
                if ($post = static::where('slug', $slug)->first()) {
                    return $post;
                }
            }
        }

        return static::where('slug', $value)->firstOrFail();
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Aggregate time as "X.XX min" (two decimals) from total_tracked_read_minutes.
     * Long values use h + min so a full hour+ does not look like 6000+ min.
     */
    public function getFormattedTrackedReadTimeAttribute(): string
    {
        $m = (float) ($this->total_tracked_read_minutes ?? 0);
        if ($m < 0.0001) {
            return '';
        }
        if ($m >= 60) {
            $h = (int) floor($m / 60);
            $r = fmod($m, 60.0);

            return $h.'h '.number_format(round($r, 2), 2, '.', '').' min';
        }

        return number_format(round($m, 2), 2, '.', '').' min';
    }
}
