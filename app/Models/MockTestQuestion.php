<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MockTestQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'mock_test_id',
        'mcq_id',
        'question_number',
        'marks',
        'negative_marks',
        'time_limit_seconds'
    ];

    protected $casts = [
        'question_number' => 'integer',
        'marks' => 'integer',
        'negative_marks' => 'integer',
        'time_limit_seconds' => 'integer',
    ];

    public function mockTest()
    {
        return $this->belongsTo(MockTest::class);
    }

    public function mcq()
    {
        return $this->belongsTo(Mcq::class);
    }

    // Get subject through MCQ
    public function subject()
    {
        return $this->hasOneThrough(
            Subject::class,
            Mcq::class,
            'id', // Foreign key on mcqs table
            'id', // Foreign key on subjects table
            'mcq_id', // Local key on mock_test_questions table
            'subject_id' // Local key on mcqs table
        );
    }

    // Get topic through MCQ
    public function topic()
    {
        return $this->hasOneThrough(
            Topic::class,
            Mcq::class,
            'id', // Foreign key on mcqs table
            'id', // Foreign key on topics table
            'mcq_id', // Local key on mock_test_questions table
            'topic_id' // Local key on mcqs table
        );
    }

    // Scope for ordering
    public function scopeOrdered($query)
    {
        return $query->orderBy('question_number');
    }
}
