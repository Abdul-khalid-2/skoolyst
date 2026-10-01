# "Login with Gmail" — Integration Guide

This guide is for any Skoolyst app (schools, blogs, ads, mcqs, store, docs,
...) that wants its own **independent** "Continue with Google" button —
separate from, and not routed through, `integrate_login_with_skoolyst.md`.
Each app that follows this guide creates and manages its own Google OAuth2
client directly with Google; there is no shared Skoolyst infrastructure
involved here (unlike "Login with Skoolyst", where skoolyst.com is a
middleman). This app (skoolyst.com) has already implemented this guide
end-to-end — see `app/Http/Controllers/Auth/GoogleAuthController.php` for a
working reference if you get stuck.

This is standard OAuth2 **Authorization Code** flow against Google's own
identity platform — no Skoolyst-specific infrastructure, just Google.

---

## 0. Decisions every app makes differently — answer these first

Same philosophy as the Skoolyst SSO guide: the flow (§1-§8) is identical
for every app, but how it plugs into *your* app varies.

### A. Library or raw HTTP calls?

- **Laravel app → use [Laravel Socialite](https://laravel.com/docs/socialite).**
  It's the official package, handles the OAuth2 dance, state/CSRF
  protection, and token exchange for you. §3 below is the Socialite path.
- **Non-Laravel / plain PHP app → raw `curl` against Google's OAuth2
  endpoints.** No official lightweight PHP library is required; Google's
  endpoints are plain REST, same shape as any OAuth2 provider. §6 gives a
  framework-agnostic example, structured the same way as
  `integrate_login_with_skoolyst.md` §9.

### B. Does this replace your existing login, or sit alongside it?

- **Recommended for most apps**: add alongside your existing email/password
  form (and alongside "Login with Skoolyst", if you also have that). Each
  login method is independent — a user could have a password, a Skoolyst
  SSO link, and a Google link on the same local account, all pointing at
  one `users` row.
- Full replacement is rarely right here — Google Sign-In requires a Google
  account, which not every user will have.

### C. What does a brand-new Google-signed-in user get in *your* app?

Same rule as the Skoolyst SSO guide (§0.C there): **match your existing
self-signup default role.** Don't grant more or less access via Google
Sign-In than a normal signup would. On this app (skoolyst.com), a
first-time Google login creates a plain account with no special role —
exactly what `RegisteredUserController` does for a normal signup.

### D. One Google Cloud project, or one per app?

Either works, but **one Google Cloud project per app** is simpler to
reason about — each app's OAuth client, consent screen, and quota are
fully independent, and revoking/regenerating one app's credentials can't
affect another's. Share a project only if you specifically want shared
quota/billing across apps.

---

## 1. Get a Google OAuth2 client (Client ID / Client Secret)

1. Go to [Google Cloud Console](https://console.cloud.google.com/) and
   create a new project (or pick an existing one — see §0.D).
2. **APIs & Services → OAuth consent screen** — fill in your app name,
   support email, and (for production) submit for verification if you'll
   request anything beyond basic profile/email scopes (this guide only
   needs basic profile/email, which doesn't require verification for
   reasonable user volumes).
3. **APIs & Services → Credentials → Create Credentials → OAuth client ID**
   — type **"Web application"**.
4. Under **Authorized redirect URIs**, add the *exact* callback URL your
   app will use (see §2 — this must be byte-for-byte identical to what
   your app sends, same rule as `redirect_uri` in the Skoolyst SSO guide).
   Add one entry for local dev and one for production, e.g.:
   ```
   http://localhost/auth/google/callback
   https://yourapp.skoolyst.com/auth/google/callback
   ```
5. Save, then copy the **Client ID** and **Client Secret** shown.

Store both in your app's server-side config. **Never put the Client Secret
in browser/client-side code** — same rule as every other credential in
this guide family.

```
GOOGLE_CLIENT_ID=xxxxxxxxxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GOCSPX-xxxxxxxxxxxxxxxxxxxxxxxx
GOOGLE_REDIRECT_URI=https://yourapp.skoolyst.com/auth/google/callback
```

---

## 2. The flow, end to end

```
┌──────────┐          ┌──────────────┐          ┌──────────────────┐
│  Browser │──(1)────▶│  Your App    │──(2)────▶│  accounts.google  │
│          │          │ /auth/google │          │  .com/o/oauth2/   │
│          │◀─────────│  (redirect)  │◀─────────│  auth             │
│          │──(3)────▶│ /auth/google │          │                   │
│          │          │   /callback  │──(4)────▶│ oauth2.googleapis │
│          │          │  (server)    │◀─────────│  .com/token        │
│          │◀─(5) session cookie set │          │  { id_token, ... } │
└──────────┘          └──────────────┘          └──────────────────┘
```

1. User clicks "Continue with Google" on your site.
2. Your server redirects the browser to Google's consent screen with your
   `client_id`, `redirect_uri`, requested scopes, and a random `state`.
3. Google authenticates the user (if needed) and redirects back to
   **your** `redirect_uri` with a one-time `code`.
4. Your **server** exchanges that `code` for tokens and the user's basic
   profile (name, email, whether Google considers the email verified).
5. You create/update a local user record keyed by Google's stable account
   id, and start your own local session — exactly like a normal login.

---

## 3. Laravel apps — using Socialite

```bash
composer require laravel/socialite
```

`config/services.php`:

```php
'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URI'),
],
```

Routes:

```php
Route::get('auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('auth.google.redirect');
Route::get('auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');
```

Controller — this is (trimmed) what `GoogleAuthController` in this app
does; see the real file for the full version with error handling and the
admin-notification email:

```php
use Laravel\Socialite\Facades\Socialite;

public function redirect(): RedirectResponse
{
    return Socialite::driver('google')->redirect();
}

public function callback(): RedirectResponse
{
    $googleUser = Socialite::driver('google')->user();

    $email = strtolower(trim($googleUser->getEmail()));
    $emailVerified = (bool) ($googleUser->user['email_verified'] ?? true);

    $user = User::where('google_id', $googleUser->getId())->first();

    if (!$user) {
        $existing = User::where('email', $email)->first();

        if ($existing) {
            if (!$emailVerified) {
                return redirect()->route('login')
                    ->with('error', 'An account with this email already exists. Please sign in with your password.');
            }
            $existing->update(['google_id' => $googleUser->getId()]);
            $user = $existing;
        } else {
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $email,
                'google_id' => $googleUser->getId(),
                'password' => Hash::make(Str::random(32)), // never used — Google is the only credential
                'email_verified_at' => now(),
            ]);
        }
    }

    Auth::login($user, remember: true);

    return redirect()->intended('/');
}
```

Add a `google_id` column (nullable, unique) to your `users` table via a
migration — same pattern as `skoolyst_id` in the Skoolyst SSO guide.

---

## 4. Account linking — same lesson as "Login with Skoolyst" §6

This case is identical in shape to §6 of `integrate_login_with_skoolyst.md`
— reread that section if anything here is unclear, the reasoning is the
same, just with Google as the identity source instead of skoolyst.com.

**Never auto-link on email match alone.** The `email_verified` field
Google returns exists specifically so you don't have to make this
mistake:

| Local account for this email? | `google_id` already set? | `email_verified` | What to do |
|---|---|---|---|
| No | — | — | First-time Google login — provision a new account (§0.C). |
| Yes | Set, and it's **this** Google account id | — | Normal return login — sign in. |
| Yes | Set, but to a **different** Google account id | — | Reject. This email is already linked elsewhere; tell the user to sign in with their password instead. |
| Yes | Not set yet | `true` | Safe to auto-link: store the `google_id` on the existing local account, then sign in. |
| Yes | Not set yet | `false` | Do not link. Tell the user to sign in with their password and link Google from account settings instead (there is no Google-side "resend verification" detour like `/oauth/verify-required` in the Skoolyst SSO guide — Google's own account recovery is Google's problem, not yours). |

In practice, Google almost always reports `email_verified: true` for a
normal consent-screen sign-in — but check it explicitly rather than assume
it, the same discipline this app applies everywhere else.

---

## 5. What you get back from Google

A successful token exchange (§3's `Socialite::driver('google')->user()`,
or §6's manual `userinfo` call) gives you:

| Field | Notes |
|---|---|
| `id` (`sub` in raw OAuth2) | Google's **stable** account identifier. Key your local user by this, not by email — same rule as `user.id` in the Skoolyst SSO guide. |
| `email` | May change if the user changes their Google account email. |
| `email_verified` | `true`/`false` — see §4. |
| `name` | Display name — treat as a convenience default, not immutable. |
| `picture` | Profile photo URL, if you want it. |

---

## 6. Framework-agnostic PHP (no Socialite, raw `curl`)

```php
// --- Step 2: redirect the user ---
function redirectToGoogleLogin(): void
{
    $state = bin2hex(random_bytes(16));
    $_SESSION['google_oauth_state'] = $state;

    $query = http_build_query([
        'client_id'     => getenv('GOOGLE_CLIENT_ID'),
        'redirect_uri'  => getenv('GOOGLE_REDIRECT_URI'),
        'response_type' => 'code',
        'scope'         => 'openid email profile',
        'state'         => $state,
        'prompt'        => 'select_account',
    ]);

    header('Location: https://accounts.google.com/o/oauth2/v2/auth?' . $query);
    exit;
}

// --- Step 3 + 4: your redirect_uri route calls this ---
function handleGoogleCallback(string $code, string $state): array
{
    if (!hash_equals($_SESSION['google_oauth_state'] ?? '', $state)) {
        throw new RuntimeException('OAuth state mismatch — possible CSRF, aborting.');
    }
    unset($_SESSION['google_oauth_state']);

    // Exchange the code for tokens.
    $ch = curl_init('https://oauth2.googleapis.com/token');
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => http_build_query([
            'code'          => $code,
            'client_id'     => getenv('GOOGLE_CLIENT_ID'),
            'client_secret' => getenv('GOOGLE_CLIENT_SECRET'),
            'redirect_uri'  => getenv('GOOGLE_REDIRECT_URI'),
            'grant_type'    => 'authorization_code',
        ]),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 15,
    ]);
    $tokenResponse = json_decode((string) curl_exec($ch), true);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($status !== 200 || empty($tokenResponse['access_token'])) {
        error_log('Google token exchange failed: ' . json_encode($tokenResponse));
        throw new RuntimeException('Google login failed. Please try again.');
    }

    // Fetch the user's profile with the access token.
    $ch = curl_init('https://www.googleapis.com/oauth2/v3/userinfo');
    curl_setopt_array($ch, [
        CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $tokenResponse['access_token']],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 15,
    ]);
    $profile = json_decode((string) curl_exec($ch), true);
    curl_close($ch);

    if (empty($profile['sub']) || empty($profile['email'])) {
        throw new RuntimeException('Could not read your Google profile. Please try again.');
    }

    return [
        'id' => $profile['sub'],
        'name' => $profile['name'] ?? strstr($profile['email'], '@', true),
        'email' => strtolower(trim($profile['email'])),
        'email_verified' => (bool) ($profile['email_verified'] ?? false),
    ];
}

// In your callback route:
// $identity = handleGoogleCallback($_GET['code'], $_GET['state']);
// ... apply the §4 account-linking table, then log the user into YOUR app's session
```

---

## 7. Security checklist

- [ ] `client_secret` lives only in server-side config, never in JS/mobile app code.
- [ ] `redirect_uri` you send in step 2 is registered **exactly** in Google Cloud Console (protocol, host, path — no trailing slash surprises).
- [ ] You generate and verify `state` yourself for CSRF protection (Socialite does this for you automatically; the raw-PHP path in §6 does it manually).
- [ ] All URLs are `https://` in production (`localhost`/`127.0.0.1` is fine for local dev registration).
- [ ] You key local users by Google's `id`/`sub`, not by email.
- [ ] The token exchange happens on **your server**, never via a browser `fetch()`/AJAX call — that would expose `client_secret`.
- [ ] You never auto-link an existing local account to an incoming Google identity on email match alone — only when `email_verified` is `true` (§4).

---

## 8. If something's not working

- **`redirect_uri_mismatch`** → the `redirect_uri` you're sending doesn't exactly match one of the URIs registered in Google Cloud Console for this client (§1 step 4). Check for trailing slashes, `http` vs `https`, and `www` vs no `www`.
- **`invalid_client`** → `client_id`/`client_secret` wrong, or you're using credentials from the wrong Google Cloud project. Re-check your `.env`.
- **`access_denied`** → the user cancelled on Google's consent screen. Treat like any other cancelled-login case — send them back to your normal login page, no error needed.
- **State mismatch error on your callback** → usually means the user's session was lost between step 2 and step 3 (e.g. cookies blocked, or a load balancer routing them to a different app server mid-flow without sticky sessions). Not a Google-side issue.
- **Consent screen shows "unverified app" warning** → expected until you submit your OAuth consent screen for Google's verification (§1 step 2); harmless for internal testing, but fix before wide public launch if you're asking for anything beyond basic profile/email scopes.
