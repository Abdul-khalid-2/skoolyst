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
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function isSuperAdmin()
    {
        return $this->hasRole('super-admin');
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
            return asset('website/' . $this->profile_picture);
        }

        return null;
    }

    /**
     * Get the shops owned by the user.
     */
    public function shops()
    {
        return $this->hasMany(Shop::class);
    }


    /**
     * Check if the user is a shop owner.
     */
    public function isShopOwner(): bool
    {
        return $this->shops()->exists();
    }

    /**
     * Check if the user owns a specific shop.
     */
    public function ownsShop(Shop $shop): bool
    {
        return $this->id === $shop->user_id;
    }
}
