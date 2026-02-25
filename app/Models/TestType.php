<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestType extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'slug',
        'icon',
        'description',
        'sort_order',
        'status'
    ];

    protected $casts = [
        'status' => 'string'
    ];

    // Relationships
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'subject_test_type')
            ->withPivot('sort_order')
            ->withTimestamps();
    }

    public function mcqs()
    {
        return $this->belongsToMany(Mcq::class, 'mcq_test_type')
            ->withPivot('sort_order')
            ->withTimestamps();
    }

    public function mockTests()
    {
        return $this->hasMany(MockTest::class);
    }

    public function studyMaterials()
    {
        return $this->hasMany(StudyMaterial::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Attribute getters
    public function getSubjectsCountAttribute()
    {
        return $this->subjects()->count();
    }

    public function getMcqsCountAttribute()
    {
        return $this->mcqs()->count();
    }

    public function getFormattedStatusAttribute()
    {
        return ucfirst($this->status);
    }
}
