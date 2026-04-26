<?php

namespace App\Models;

use App\Enums\VideoReactionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VideoReaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'video_id',
        'user_id',
        'reaction',
    ];

    protected $casts = [
        'reaction' => VideoReactionType::class,
    ];

    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
