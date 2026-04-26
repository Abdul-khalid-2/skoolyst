<?php

namespace App\Models;

use App\Enums\UserProgressType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'topic_id',
        'subject_id',
        'progress_type',
        'progress_percentage',
        'total_items',
        'completed_items',
        'last_accessed_at',
        'completed_at',
        'progress_data'
    ];

    protected $casts = [
        'progress_data' => 'array',
        'last_accessed_at' => 'datetime',
        'completed_at' => 'datetime',
        'progress_type' => UserProgressType::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function updateProgress($completed = 1)
    {
        $this->completed_items += $completed;
        $this->progress_percentage = ($this->completed_items / $this->total_items) * 100;

        if ($this->completed_items >= $this->total_items) {
            $this->completed_at = now();
        }

        $this->last_accessed_at = now();
        $this->save();
    }
}
