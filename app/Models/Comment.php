<?php

namespace App\Models;

use App\Enums\ModerationStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'blog_post_id',
        'parent_id',
        'name',
        'email',
        'comment',
        'status'
    ];

    protected $casts = [
        'status' => ModerationStatus::class,
    ];

    /** @internal For Blade when `status` is cast to ModerationStatus */
    public function getStatusLabelAttribute(): string
    {
        $s = $this->status;
        if ($s === null) {
            return '';
        }
        if ($s instanceof \BackedEnum) {
            return ucfirst($s->value);
        }

        return ucfirst((string) $s);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function blogPost(): BelongsTo
    {
        return $this->belongsTo(BlogPost::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
}
