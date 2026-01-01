<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'slug',
        'test_type_id',
        'description',
        'icon',
        'color_code',
        'sort_order',
        'status'
    ];

    protected $casts = [
        'status' => 'string'
    ];

    public function testType()
    {
        return $this->belongsTo(TestType::class);
    }

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    public function mcqs()
    {
        return $this->hasMany(Mcq::class);
    }

    public function books()
    {
        return $this->hasMany(Book::class, 'subject');
    }

    public function userProgress()
    {
        return $this->hasMany(UserProgress::class);
    }

    // Add these scope methods
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByTestType($query, $testTypeId)
    {
        return $query->where('test_type_id', $testTypeId);
    }

    // Add relationships count attributes
    public function getTopicsCountAttribute()
    {
        return $this->topics()->count();
    }

    public function getMcqsCountAttribute()
    {
        return $this->mcqs()->count();
    }
}
