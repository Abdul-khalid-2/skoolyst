<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Short-lived, single-use authorization code issued by /oauth/authorize
 * and redeemed by POST /api/oauth/token. See OAuthController.
 */
class OAuthAuthCode extends Model
{
    use HasFactory;

    protected $table = 'oauth_auth_codes';

    protected $fillable = [
        'code',
        'client_id',
        'user_id',
        'redirect_uri',
        'state',
        'expires_at',
        'used_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public static function generateCode(): string
    {
        return Str::random(64);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isValid(): bool
    {
        return $this->used_at === null && $this->expires_at->isFuture();
    }
}
