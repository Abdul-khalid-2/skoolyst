<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\AdminUserActivityMail;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

/**
 * "Login with Gmail" — Google OAuth2 via Laravel Socialite. Sits alongside
 * the normal email/password login (RegisteredUserController /
 * AuthenticatedSessionController), which keeps working unchanged.
 *
 * Account matching, in order (same policy as this app's own OAuth2 provider
 * — see OAuthController@verifyRequired and §6 of integrate_login_with_skoolyst.md):
 *  1. A local account already linked to this Google account id → sign in.
 *  2. A local account with the same email → linked and signed in ONLY when
 *     Google reports the email as verified (Google always does for a normal
 *     Google Sign-In, but we check explicitly rather than assume it).
 *  3. Nobody → a new account is created (matches the normal self-registration
 *     role: none/plain "Parent, Student" account — see RegisteredUserController).
 */
class GoogleAuthController extends Controller
{
    /**
     * Step 1 — send the browser to Google's consent screen.
     */
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Step 2 — Google sends the browser back here with the user's profile.
     */
    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (InvalidStateException $e) {
            return redirect()->route('login')->with('error', 'Your Google sign-in could not be verified. Please try again.');
        } catch (\Throwable $e) {
            Log::warning('Google login failed', ['error' => $e->getMessage()]);
            return redirect()->route('login')->with('error', 'Could not sign you in with Google right now. Please try again in a moment.');
        }

        $email = strtolower(trim((string) $googleUser->getEmail()));
        $emailVerified = (bool) ($googleUser->user['email_verified'] ?? $googleUser->user['verified_email'] ?? true);

        if ($email === '') {
            return redirect()->route('login')->with('error', 'Your Google account has no email address we can sign you in with.');
        }

        $user = User::where('google_id', $googleUser->getId())->first();

        if (!$user) {
            $existing = User::where('email', $email)->first();

            if ($existing) {
                if (!$emailVerified) {
                    return redirect()->route('login')->with('error', 'An account with this email already exists here. Please sign in with your password instead.');
                }

                $existing->forceFill(['google_id' => $googleUser->getId()])->save();
                $user = $existing;
            } else {
                $user = User::create([
                    'uuid' => Str::uuid(),
                    'name' => trim((string) $googleUser->getName()) ?: strstr($email, '@', true),
                    'email' => $email,
                    'google_id' => $googleUser->getId(),
                    'password' => Hash::make(Str::random(32)), // never used to sign in — Google is the only credential
                    'email_verified_at' => now(),
                ]);

                event(new Registered($user));

                try {
                    Mail::to('skoolyst@gmail.com')->send(
                        new AdminUserActivityMail($user, 'registered')
                    );
                } catch (\Throwable $e) {
                    Log::warning('Failed to email admin about Google registration', [
                        'user_id' => $user->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }

        if ($user->email_verified_at === null && $emailVerified) {
            $user->forceFill(['email_verified_at' => now()])->save();
        }

        Auth::login($user, remember: true);

        return redirect()->intended(route('website.home', absolute: false));
    }
}
