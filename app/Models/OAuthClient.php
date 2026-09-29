<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * A third-party Skoolyst app (blogs, mcqs, store, ...) allowed to use
 * "Login with Skoolyst". See app/Http/Controllers/OAuthController.php.
 */
class OAuthClient extends Model
{
    use HasFactory;

    protected $table = 'oauth_clients';

    protected $fillable = [
        'name',
        'client_id',
        'client_secret',
        'redirect_uris',
        'is_active',
        'last_used_at',
    ];

    protected $hidden = [
        'client_secret',
    ];

    protected $casts = [
        'redirect_uris' => 'array',
        'is_active' => 'boolean',
        'last_used_at' => 'datetime',
    ];

    public static function generateClientId(): string
    {
        return 'skl_client_' . Str::random(24);
    }

    public static function generateClientSecret(): string
    {
        return 'skl_secret_' . Str::random(40);
    }

    public function setPlainSecret(string $plainSecret): void
    {
        $this->client_secret = Hash::make($plainSecret);
    }

    public function checkSecret(string $plainSecret): bool
    {
        return Hash::check($plainSecret, $this->client_secret);
    }

    public function allowsRedirectUri(?string $uri): bool
    {
        if (empty($uri)) {
            return false;
        }

        return in_array($uri, $this->redirect_uris ?? [], true);
    }
}
