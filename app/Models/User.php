<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'uuid',
        'name',
        'email',
        'google_id',
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
    public function school()
    {
        return $this->belongsTo(School::class);
    }
    
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function isSuperAdmin()
    {
        return $this->hasRole('super-admin');
    }

    /**
     * Users who use the back-office /dashboard (super-admin, school-admin, shop-owner).
     */
    public function hasDashboardAccess(): bool
    {
        return $this->hasAnyRole(['super-admin', 'school-admin', 'shop-owner']);
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

    // Check if user has premium access
    public function hasPremiumAccess()
    {
        // You can implement your premium access logic here
        // For example, check subscription, purchase history, etc.
        return $this->hasRole('premium-user') || $this->premium_expires_at > now();
    }
}
