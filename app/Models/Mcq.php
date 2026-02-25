<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mcq extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'topic_id',
        'subject_id',
        'test_type_id',
        'question',
        'question_type',
        'options',
        'correct_answers',
        'explanation',
        'hint',
        'difficulty_level',
        'time_limit_seconds',
        'marks',
        'negative_marks',
        'tags',
        'reference_book',
        'reference_page',
        'is_premium',
        'is_verified',
        'status',
        'created_by',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'options' => 'array',
        'correct_answers' => 'array',
        'tags' => 'array',
        'is_premium' => 'boolean',
        'is_verified' => 'boolean',
        'approved_at' => 'datetime',
    ];

    // Relationships
    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function testTypes()
    {
        return $this->belongsToMany(TestType::class, 'mcq_test_type')
            ->withPivot('sort_order')
            ->withTimestamps()
            ->orderByPivot('sort_order');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function mockTests()
    {
        return $this->belongsToMany(MockTest::class, 'mock_test_questions')
            ->withPivot(['question_number', 'marks', 'negative_marks', 'time_limit_seconds'])
            ->withTimestamps();
    }

    public function userAnswers()
    {
        return $this->hasMany(UserMcqAnswer::class);
    }

    // Attribute getters
    public function getMockTestsCountAttribute()
    {
        return $this->mockTests()->count();
    }

    public function getOptionsFormattedAttribute()
    {
        $options = $this->options ?? [];
        $formatted = [];

        foreach ($options as $index => $option) {
            $letter = chr(65 + $index);
            $formatted[$letter] = $option;
        }

        return $formatted;
    }

    public function getCorrectAnswersFormattedAttribute()
    {
        $answers = $this->correct_answers ?? [];
        $formatted = [];

        foreach ($answers as $index) {
            $letter = chr(65 + $index);
            $formatted[] = $letter;
        }

        return $formatted;
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopePremium($query)
    {
        return $query->where('is_premium', true);
    }

    public function scopeByDifficulty($query, $difficulty)
    {
        return $query->where('difficulty_level', $difficulty);
    }

    public function scopeBySubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    public function scopeByTopic($query, $topicId)
    {
        return $query->where('topic_id', $topicId);
    }
}
