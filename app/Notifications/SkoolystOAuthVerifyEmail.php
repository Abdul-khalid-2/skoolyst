<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\URL;

/**
 * Same as Laravel's built-in VerifyEmail notification, except the signed
 * verification link also carries the OAuth client/redirect_uri/state that
 * triggered it (see OAuthController::verifyRequired()), so
 * VerifyEmailController can send the user back to the consumer app's
 * callback instead of this app's own dashboard once they're verified.
 */
class SkoolystOAuthVerifyEmail extends VerifyEmail
{
    public function __construct(private array $oauthParams)
    {
    }

    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(config('auth.verification.expire', 60)),
            array_filter([
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
                ...$this->oauthParams,
            ])
        );
    }
}
