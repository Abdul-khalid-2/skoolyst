<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTestAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'user_id',
        'mock_test_id',
        'topic_id',
        'subject_id',
        'test_type_id',
        'started_at',
        'completed_at',
        'submitted_at',
        'total_questions',
        'attempted_questions',
        'correct_answers',
        'wrong_answers',
        'skipped_questions',
        'total_marks_obtained',
        'negative_marks_obtained',
        'total_possible_marks',
        'percentage',
        'accuracy',
        'result_status',
        'time_taken_seconds',
        'remaining_time_seconds',
        'answers_data',
        'question_analysis',
        'status',
        'test_source',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'answers_data' => 'array',
        'question_analysis' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'submitted_at' => 'datetime',
        'result_status' => 'string',
        'status' => 'string'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mockTest()
    {
        return $this->belongsTo(MockTest::class);
    }

    public function userMcqAnswers()
    {
        return $this->hasMany(UserMcqAnswer::class);
    }

    public function calculateScore()
    {
        // Implement scoring logic
    }

    public function getTimeRemainingAttribute()
    {
        if (!$this->started_at || $this->completed_at) {
            return 0;
        }

        $totalTime = $this->mockTest->total_time_minutes * 60;
        $elapsed = now()->diffInSeconds($this->started_at);

        return max(0, $totalTime - $elapsed);
    }
}
