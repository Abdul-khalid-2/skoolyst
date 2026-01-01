<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'uuid',
        'name',
        'email',
        'phone',
        'address',
        'bio',
        'profile_picture',
        'password',
        'school_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function shop()
    {
        return $this->hasOne(Shop::class, 'user_id');
    }
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function isSuperAdmin()
    {
        return $this->hasRole('super-admin');
    }

    // NEW: Test Preparation Relationships
    public function userMcqAnswers()
    {
        return $this->hasMany(UserMcqAnswer::class);
    }

    public function userTestAttempts()
    {
        return $this->hasMany(UserTestAttempt::class);
    }

    public function userProgress()
    {
        return $this->hasMany(UserProgress::class);
    }

    public function createdMcqs()
    {
        return $this->hasMany(Mcq::class, 'created_by');
    }

    public function approvedMcqs()
    {
        return $this->hasMany(Mcq::class, 'approved_by');
    }

    public function createdMockTests()
    {
        return $this->hasMany(MockTest::class, 'created_by');
    }

    public function bookListings()
    {
        return $this->hasManyThrough(Book::class, Product::class, 'user_id', 'product_id');
    }

    // Helper methods for test preparation
    public function getTotalCorrectAnswers()
    {
        return $this->userMcqAnswers()->where('is_correct', true)->count();
    }

    public function getTotalTestsTaken()
    {
        return $this->userTestAttempts()->where('status', 'completed')->count();
    }

    public function getAverageTestScore()
    {
        $attempts = $this->userTestAttempts()->where('status', 'completed')->get();

        if ($attempts->count() === 0) {
            return 0;
        }

        return $attempts->avg('percentage');
    }

    public function getTopicsReadCount()
    {
        return $this->userProgress()
            ->where('progress_type', 'topic_read')
            ->where('progress_percentage', '>=', 100)
            ->count();
    }

    // Generate UUID automatically
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function getProfilePictureUrlAttribute()
    {
        if ($this->profile_picture) {
            // Check if the path is a URL or local path
            if (filter_var($this->profile_picture, FILTER_VALIDATE_URL)) {
                return $this->profile_picture;
            }

            if (Storage::disk('public')->exists($this->profile_picture)) {
                return Storage::disk('public')->url($this->profile_picture);
            }

            return asset('website/' . $this->profile_picture);
        }

        return null;
    }

    // Accessor for user's test preparation stats
    public function getTestStatsAttribute()
    {
        return [
            'correct_answers' => $this->getTotalCorrectAnswers(),
            'tests_taken' => $this->getTotalTestsTaken(),
            'average_score' => round($this->getAverageTestScore(), 1),
            'topics_read' => $this->getTopicsReadCount(),
        ];
    }

    // Check if user has premium access
    public function hasPremiumAccess()
    {
        // You can implement your premium access logic here
        // For example, check subscription, purchase history, etc.
        return $this->hasRole('premium-user') || $this->premium_expires_at > now();
    }
}
