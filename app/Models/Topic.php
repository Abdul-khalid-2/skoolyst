<?php

namespace App\Models;

use App\Enums\ActiveStatus;
use App\Enums\TopicDifficultyLevel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Topic extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'subject_id',
        'title',
        'slug',
        'description',
        'content',
        'sort_order',
        'estimated_time_minutes',
        'difficulty_level',
        'status'
    ];

    protected $casts = [
        'difficulty_level' => TopicDifficultyLevel::class,
        'status' => ActiveStatus::class,
    ];

    // Relationships
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function mcqs()
    {
        return $this->hasMany(Mcq::class);
    }

    public function userProgress()
    {
        return $this->hasMany(UserProgress::class);
    }

    public function userMcqAnswers()
    {
        return $this->hasMany(UserMcqAnswer::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', ActiveStatus::Active);
    }

    public function scopeBySubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    public function scopeByDifficulty($query, $difficulty)
    {
        return $query->where('difficulty_level', $difficulty);
    }

    // Attribute getters
    public function getMcqsCountAttribute()
    {
        return $this->mcqs()->count();
    }

    public function getFormattedDifficultyAttribute()
    {
        return ucfirst($this->difficulty_level?->value ?? '');
    }

    /** Raw value for CSS classes (beginner, intermediate, advanced) */
    public function getDifficultyValueAttribute(): string
    {
        $d = $this->difficulty_level;
        if ($d instanceof \BackedEnum) {
            return $d->value;
        }

        return (string) ($d ?? '');
    }

    public function getFormattedStatusAttribute()
    {
        return ucfirst($this->status?->value ?? '');
    }

    /** Bootstrap `bg-*` variant for topic difficulty */
    public function getDifficultyBadgeVariantAttribute(): string
    {
        return match ($this->difficulty_level) {
            TopicDifficultyLevel::Beginner => 'success',
            TopicDifficultyLevel::Intermediate => 'warning',
            TopicDifficultyLevel::Advanced => 'danger',
            default => 'secondary',
        };
    }
}
