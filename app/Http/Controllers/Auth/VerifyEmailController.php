<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OAuthClient;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if (!$request->user()->hasVerifiedEmail() && $request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        // If this link was emailed via OAuthController::verifyRequired()
        // (SSO login that got stuck on an unverified email), send the user
        // back to finish that login instead of our own dashboard. Re-check
        // client_id/redirect_uri here too — the link is signed, but a
        // client could since have been deactivated or had its redirect_uri
        // changed.
        if ($request->filled('client_id') && $request->filled('redirect_uri')) {
            $client = OAuthClient::where('client_id', $request->query('client_id'))
                ->where('is_active', true)
                ->first();

            if ($client && $client->allowsRedirectUri($request->query('redirect_uri'))) {
                return redirect()->route('oauth.authorize', $request->only(['client_id', 'redirect_uri', 'state']));
            }
        }

        return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
    }
}
