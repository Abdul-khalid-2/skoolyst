<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMcqAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'mcq_id',
        'test_attempt_id',
        'topic_id',
        'selected_answers',
        'is_correct',
        'time_taken_seconds',
        'answered_at'
    ];

    protected $casts = [
        'selected_answers' => 'array',
        'is_correct' => 'boolean',
        'answered_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mcq()
    {
        return $this->belongsTo(Mcq::class);
    }

    public function testAttempt()
    {
        return $this->belongsTo(UserTestAttempt::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
}
