<?php

namespace App\Models;

use App\Enums\ContentStatus;
use App\Enums\McqDifficulty;
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
        'status' => ContentStatus::class,
        'difficulty_level' => McqDifficulty::class,
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

    /** @internal Stored value (for CSS classes, JS, string comparisons) */
    public function getDifficultyValueAttribute(): string
    {
        $d = $this->difficulty_level;
        if ($d instanceof \BackedEnum) {
            return $d->value;
        }

        return (string) ($d ?? '');
    }

    /** @internal Shown in UI; replaces ucfirst($mcq->difficulty_level) when column is cast to enum */
    public function getDifficultyLabelAttribute(): string
    {
        $v = $this->difficulty_value;

        return $v === '' ? '' : ucfirst($v);
    }

    /** Bootstrap `bg-*` variant for difficulty (success, warning, danger, secondary) */
    public function getDifficultyBadgeVariantAttribute(): string
    {
        return match ($this->difficulty_level) {
            McqDifficulty::Easy => 'success',
            McqDifficulty::Medium => 'warning',
            McqDifficulty::Hard => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Full alert/pill classes (e.g. bg-warning text-dark) for website practice views.
     */
    public function getDifficultyPillClassAttribute(): string
    {
        return match ($this->difficulty_level) {
            McqDifficulty::Easy => 'bg-success',
            McqDifficulty::Medium => 'bg-warning text-dark',
            McqDifficulty::Hard => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    /** @internal For Blade when `status` is cast to ContentStatus (avoid ucfirst on enum) */
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

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', ContentStatus::Published);
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
