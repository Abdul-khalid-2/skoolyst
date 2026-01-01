<?php

namespace App\Models;

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
        'difficulty_level' => 'string',
        'status' => 'string'
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
        return $query->where('status', 'active');
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
        return ucfirst($this->difficulty_level);
    }

    public function getFormattedStatusAttribute()
    {
        return ucfirst($this->status);
    }
}
