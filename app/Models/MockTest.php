<?php

namespace App\Models;

use App\Enums\ContentStatus;
use App\Enums\MockTestMode;
use App\Enums\UserTestAttemptStatus;
use App\Enums\UserTestResultStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MockTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'title',
        'slug',
        'test_type_id',
        'description',
        'instructions',
        'total_questions',
        'total_marks',
        'total_time_minutes',
        'passing_marks',
        'test_mode',
        'shuffle_questions',
        'show_result_immediately',
        'allow_retake',
        'max_attempts',
        'is_free',
        'price',
        'status',
        'subject_breakdown',
        'created_by'
    ];

    protected $casts = [
        'shuffle_questions' => 'boolean',
        'show_result_immediately' => 'boolean',
        'allow_retake' => 'boolean',
        'is_free' => 'boolean',
        'subject_breakdown' => 'array',
        'status' => ContentStatus::class,
        'test_mode' => MockTestMode::class,
        'total_questions' => 'integer',
        'total_marks' => 'integer',
        'total_time_minutes' => 'integer',
        'passing_marks' => 'integer',
        'max_attempts' => 'integer',
        'price' => 'decimal:2',
    ];

    public function testType()
    {
        return $this->belongsTo(TestType::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function questions()
    {
        return $this->hasMany(MockTestQuestion::class);
    }

    public function mcqs()
    {
        return $this->belongsToMany(Mcq::class, 'mock_test_questions')
            ->withPivot('question_number', 'marks', 'negative_marks', 'time_limit_seconds')
            ->orderBy('question_number');
    }

    public function attempts()
    {
        return $this->hasMany(UserTestAttempt::class);
    }

    // Alias for attempts for consistency
    public function userTestAttempts()
    {
        return $this->hasMany(UserTestAttempt::class);
    }

    // Get all attempts count
    public function getAttemptsCountAttribute()
    {
        return $this->attempts()->count();
    }

    // Get completed attempts only
    public function getCompletedAttemptsCountAttribute()
    {
        return $this->attempts()->where('status', UserTestAttemptStatus::Completed)->count();
    }

    public function getAverageScoreAttribute()
    {
        return $this->attempts()->where('status', UserTestAttemptStatus::Completed)->avg('percentage');
    }

    // Get pass rate percentage
    public function getPassRateAttribute()
    {
        $completedAttempts = $this->attempts()->where('status', UserTestAttemptStatus::Completed)->count();
        if ($completedAttempts === 0) return 0;

        $passedAttempts = $this->attempts()->where('status', UserTestAttemptStatus::Completed)->where('result_status', UserTestResultStatus::Passed)->count();
        return ($passedAttempts / $completedAttempts) * 100;
    }

    // Get formatted price
    public function getFormattedPriceAttribute()
    {
        if ($this->is_free) {
            return 'Free';
        }
        return config('app.currency', 'PKR') . ' ' . number_format($this->price, 2);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', ContentStatus::Published);
    }

    public function scopeFree($query)
    {
        return $query->where('is_free', true);
    }

    public function scopePremium($query)
    {
        return $query->where('is_free', false);
    }

    public function scopeByTestType($query, $testTypeId)
    {
        return $query->where('test_type_id', $testTypeId);
    }

    public function scopeActive($query)
    {
        return $query->where('status', ContentStatus::Published);
    }

    // Check if user can attempt test
    public function canUserAttempt($userId = null)
    {
        $userId = $userId ?? auth()->id();

        if (!$userId) return false;

        if (!$this->allow_retake) {
            $userAttempts = $this->attempts()->where('user_id', $userId)->count();
            if ($userAttempts > 0) return false;
        }

        if ($this->max_attempts) {
            $userAttempts = $this->attempts()->where('user_id', $userId)->count();
            return $userAttempts < $this->max_attempts;
        }

        return true;
    }

    // Get remaining attempts for user
    public function getRemainingAttempts($userId = null)
    {
        $userId = $userId ?? auth()->id();

        if (!$this->max_attempts) return null; // Unlimited

        $userAttempts = $this->attempts()->where('user_id', $userId)->count();
        return max(0, $this->max_attempts - $userAttempts);
    }
}
