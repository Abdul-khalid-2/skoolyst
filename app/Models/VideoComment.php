<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VideoComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'video_id',
        'user_id',
        'parent_id',
        'message',
        'name',
        'email',
        'is_approved',
        'likes_count',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'likes_count' => 'integer',
    ];

    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(VideoComment::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(VideoComment::class, 'parent_id')->where('is_approved', true);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(VideoReaction::class, 'comment_id');
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }
}
