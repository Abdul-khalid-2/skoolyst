<?php
// app/Models/SchoolProfile.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchoolProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'quick_facts',
        'logo',
        'social_media',
        'visitor_count',
        'total_time_spent',
        'last_visited_at',
        'established_year',
        'student_strength',
        'faculty_count',
        'campus_size',
        'school_motto',
        'mission',
        'vision'
    ];

    protected $casts = [
        'quick_facts' => 'array',
        'social_media' => 'array',
        'last_visited_at' => 'datetime',
    ];

    /**
     * Get the school that owns the profile.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get quick facts as array with defaults
     */
    // public function getQuickFactsAttribute($value)
    // {
    //     $facts = json_decode($value, true) ?? [];

    //     // Create base facts array from individual fields
    //     $baseFacts = [];

    //     if ($this->established_year) {
    //         $baseFacts['established_year'] = $this->established_year;
    //     }
    //     if ($this->student_strength) {
    //         $baseFacts['student_strength'] = $this->student_strength;
    //     }
    //     if ($this->faculty_count) {
    //         $baseFacts['faculty_count'] = $this->faculty_count;
    //     }
    //     if ($this->campus_size) {
    //         $baseFacts['campus_size'] = $this->campus_size;
    //     }

    //     // Merge JSON facts with base facts (JSON facts take precedence)
    //     return array_merge($baseFacts, $facts);
    // }

    /**
     * Get social media links with defaults
     */
    // public function getSocialMediaAttribute($value)
    // {
    //     $socials = json_decode($value, true) ?? [];

    //     return array_merge([
    //         'facebook' => null,
    //         'twitter' => null,
    //         'instagram' => null,
    //         'linkedin' => null,
    //         'youtube' => null,
    //     ], $socials);
    // }

    /**
     * Accessor for established_year with fallback
     */
    public function getEstablishedYearAttribute($value)
    {
        return $value ?? null;
    }

    /**
     * Accessor for student_strength with fallback
     */
    public function getStudentStrengthAttribute($value)
    {
        return $value ?? null;
    }

    /**
     * Accessor for faculty_count with fallback
     */
    public function getFacultyCountAttribute($value)
    {
        return $value ?? null;
    }

    /**
     * Accessor for campus_size with fallback
     */
    public function getCampusSizeAttribute($value)
    {
        return $value ?? null;
    }
}
