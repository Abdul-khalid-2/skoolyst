<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'user_id',
        'user_id',
        'review',
        'rating',
        'reviewer_name'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    // Accessor for reviewer name
    public function getReviewerNameAttribute($value)
    {
        return $value ?? $this->user->name ?? 'Anonymous';
    }
}
