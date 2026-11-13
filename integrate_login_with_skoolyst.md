# "Login with Skoolyst" — Integration Guide

This app (the Skoolyst School Listing platform — skoolyst.com) is the
**central identity provider** for the whole Skoolyst family (blogs, mcqs,
store, docs, ...). Any Skoolyst app lets a user "Login with Skoolyst"
instead of building its own signup/login — this service authenticates the
user and hands back their basic identity (id, name, email).

This is a lightweight OAuth2 **Authorization Code** flow — not a
generic/public OAuth provider, so there are no scopes, no refresh tokens,
no third-party consent screen. Every client is a trusted first-party
Skoolyst app, so an already-logged-in user is bounced straight back with no
extra click.

Base URL (same host as the Ads / Email APIs): `https://skoolyst.com`

---

## 0. Decisions every app makes differently — answer these first

This guide's flow (§1-§11) is the same for every app, but **each app is
built differently**, so before touching code, decide these three things for
*your* app specifically. There's no universal right answer — what's right
for a PHP/MySQL blog is wrong for a static site, and what's right for an
admin-only tool is wrong for a public signup app.

### A. Where does the callback live?

Your `redirect_uri` (§3, §5) must point at *something* in your app that can
run server-side code to call `POST /api/oauth/token`. Two common shapes:

- **A dedicated route/file** (e.g. `/auth/skoolyst-callback` or
  `/auth/skoolyst-callback.php`) — cleanest separation, keeps the OAuth
  logic in one place. Register *that exact URL* as the redirect_uri in
  **Dashboard → Connected Apps** on skoolyst.com.
- **An existing entry point your app already has registered** (e.g. a
  plain PHP app's `dashboard/index.php`, or a framework's existing
  front controller) — no change needed on the skoolyst.com side if that
  URL is already what's registered; you handle the code-exchange at the
  top of that file, gated behind `if (isset($_GET['code']))` before your
  normal routing/rendering runs.

Whichever you pick, the URL you actually redirect to in §3 must be
**byte-for-byte identical** to whatever's registered in Connected Apps —
if you change your mind later, update the registration to match (an admin
can edit it any time under Dashboard → Connected Apps → Edit).

### B. Does this replace your existing login, or sit alongside it?

- **Recommended for most apps**: add alongside. Put a "Login with Skoolyst"
  button next to your existing email/password form. Least disruptive,
  keeps both paths working, and either can be removed later once you see
  how many users actually use SSO.
- **Full replacement**: only do this once you're confident every existing
  user can/will sign in via Skoolyst — e.g. a brand-new app with no
  existing user base yet, or one where every user already has a Skoolyst
  account for other reasons.

### C. What does a brand-new SSO user get in *your* app?

The first time a given Skoolyst `user.id` (§5) signs in via SSO, you're
auto-provisioning a local account for them. Decide what that account gets:

- **Match your existing self-signup default.** If your own signup form
  normally creates a "member"/"customer"/whatever role, a first-time SSO
  login should get the same — don't accidentally grant more or less access
  than your normal signup path would. *Example: Skoolyst Ads' own signup
  (`POST /api/v1/auth/register`) creates "Advertiser" accounts, so a
  first-time SSO login there provisions an Advertiser too — an admin can
  promote them manually afterwards, same as any other user.*
- **Don't auto-provision at all**, if your app is admin-only or requires
  manual vetting before granting access — hold a first-time SSO login in a
  "pending approval" state instead of creating a fully-privileged account
  automatically. This is the exception, not the default — most consumer
  apps should auto-provision matching their normal signup role.

---

## 1. Get a client_id / client_secret

An admin on skoolyst.com opens **Dashboard → Connected Apps → Add
App**, enters your app's name and one or more exact redirect URL(s) (e.g.
`https://blogs.skoolyst.com/auth/skoolyst/callback`), and copies the
`client_id` and `client_secret` shown **once**. The secret is stored hashed
on our side — if it's lost, an admin has to regenerate it (which
immediately invalidates the old one).

Store both in your app's server-side config (e.g. `.env`). **Never put
`client_secret` in browser/client-side code** — the whole flow is designed
so the secret only ever travels server-to-server.

```
SKOOLYST_AUTH_BASE=https://skoolyst.com
SKOOLYST_AUTH_CLIENT_ID=skl_client_xxxxxxxxxxxxxxxxxxxxxxxx
SKOOLYST_AUTH_CLIENT_SECRET=skl_secret_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
SKOOLYST_AUTH_REDIRECT_URI=https://blogs.skoolyst.com/auth/skoolyst/callback
```

---

## 2. The flow, end to end

```
┌──────────┐          ┌──────────────┐          ┌──────────────────┐
│  Browser │──(1)────▶│  Your App    │──(2)────▶│  skoolyst.com     │
│          │          │ /auth/skoolyst│          │ /oauth/authorize  │
│          │◀─────────│  (redirect)  │◀─────────│  (302 redirect)   │
│          │──(3)────▶│ /auth/skoolyst│          │                   │
│          │          │   /callback  │──(4)────▶│ POST /api/oauth/  │
│          │          │  (server)    │◀─────────│  token            │
│          │◀─(5) session cookie set │          │  { user, token }  │
└──────────┘          └──────────────┘          └──────────────────┘
```

1. User clicks "Login with Skoolyst" on your site.
2. Your server redirects the browser to `GET {SKOOLYST_AUTH_BASE}/oauth/authorize`
   with your `client_id`, `redirect_uri`, and a random `state` value.
3. skoolyst.com checks if the user is logged in there (if not, it
   shows its own login page first), then redirects the browser back to
   **your** `redirect_uri` with a one-time `code` and the same `state`.
4. Your **server** (never the browser) calls
   `POST {SKOOLYST_AUTH_BASE}/api/oauth/token` with the `code`, your
   `client_id`, and `client_secret`, and gets back the user's basic info
   plus an `access_token`.
5. You create/update a local user record keyed by the Skoolyst `id`, and
   start your own local session — exactly like a normal login.

The `code` from step 3 is single-use and expires after 5 minutes. The
`access_token` from step 4 is long-lived — keep it if you want to re-verify
the user or pull fresh info later (see §8), but it's optional.

---

## 3. Step 2 — redirect the user to authorize

```
GET https://skoolyst.com/oauth/authorize
    ?client_id=skl_client_xxxxxxxxxxxxxxxxxxxxxxxx
    &redirect_uri=https%3A%2F%2Fblogs.skoolyst.com%2Fauth%2Fskoolyst%2Fcallback
    &state=<random, unguessable, stored in your session>
```

- `redirect_uri` must be **byte-for-byte identical** to one of the URLs
  registered for your client — no trailing slash differences, no query
  string differences. A mismatch is rejected before any redirect happens.
- `state` is yours to generate and verify — store it in the user's session
  before redirecting, and confirm it matches on the way back in step 3.
  This is your CSRF protection; skoolyst.com just echoes it back
  unchanged.

---

## 4. Step 3 — handle the callback

Your `redirect_uri` receives `?code=...&state=...` (or `?error=...` — see
§7 for what can go wrong before this point). First thing: **compare `state`
against what you stored in the session**; if it doesn't match, abort — do
not proceed to step 4.

---

## 5. Step 4 — exchange the code for the user (server-side)

`POST /api/oauth/token` — `Content-Type: application/json`

| Field           | Required | Notes |
|-----------------|----------|-------|
| `client_id`     | yes      | |
| `client_secret` | yes      | Never sent by the browser — this call is server-to-server. |
| `code`          | yes      | From step 3. Single-use. |
| `redirect_uri`  | yes      | Must exactly match the one used in step 2. |

```bash
curl -X POST https://skoolyst.com/api/oauth/token \
  -H "Content-Type: application/json" \
  -d '{
    "client_id": "skl_client_xxxxxxxxxxxxxxxxxxxxxxxx",
    "client_secret": "skl_secret_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
    "code": "the-code-from-the-callback",
    "redirect_uri": "https://blogs.skoolyst.com/auth/skoolyst/callback"
  }'
```

Success `201`:

```json
{
  "success": true,
  "data": {
    "user": {
      "id": 42,
      "name": "Ayesha Khan",
      "email": "ayesha@example.com",
      "email_verified": true
    },
    "access_token": "2|abcdef123456...",
    "token_type": "Bearer"
  }
}
```

Use `user.id` as the stable key to find-or-create your local user record
(don't key on email alone — it can change).

### Error responses

All errors: `{ "success": false, "error": { "code": "...", "message": "..." } }`

| HTTP | code             | Meaning / what to do |
|------|------------------|-----------------------|
| 401  | `invalid_client` | Wrong `client_id`/`client_secret`. Check your config; don't retry blindly. |
| 400  | `invalid_grant`  | `code` is wrong, already used, expired (>5 min old), or `redirect_uri` doesn't match. Send the user through the flow again from step 2 — a code can't be reused. |
| 422  | `validation_error` (via `errors` key, standard Laravel shape) | Missing a required field. Fix the request. |
| 429  | —                | Rate limited (30 req/min per IP on this endpoint). Back off. |

---

## 6. Account linking — when the SSO email matches an existing local account

This case is easy to miss in testing (it only shows up once your app has
*both* SSO users and password users), so it gets its own section. It comes
up whenever someone who already has a local account on your app — created
the normal way, with email/password — later tries "Login with Skoolyst"
using a Skoolyst identity that happens to share that same email address.

**Never auto-link on email match alone.** If you silently attach the
incoming Skoolyst `user.id` to whichever local account has a matching
email, anyone who controls *an unverified* email address on skoolyst.com
could sign in as someone else on your app just by registering that email
there — a full account takeover. The `email_verified` field in the §5
response exists specifically so you don't have to make this mistake:

| Local account for this email? | `skoolyst_id` already set? | `email_verified` | What to do |
|---|---|---|---|
| No | — | — | First-time SSO login — provision a new account (§0.C). |
| Yes | Set, and it's **this** Skoolyst `user.id` | — | Normal return login — sign in. |
| Yes | Set, but to a **different** Skoolyst `user.id` | — | Reject. This email is already linked elsewhere; tell the user to sign in with their password instead. |
| Yes | Not set yet | `true` | Safe to auto-link: store the `skoolyst_id` on the existing local account, then sign in. skoolyst.com is vouching that this person controls the email. |
| Yes | Not set yet | `false` | **Do not link and do not just show an error.** Send the user to `/oauth/verify-required` (below) so skoolyst.com can get them verified and bounce them straight back — see the flow under this table. |

### Resolving the "not verified yet" case without a dead end

A plain error message ("please verify your email") is a dead end — the
user has no way to act on it without leaving your app and hunting for a
"resend verification" option on skoolyst.com themselves. Instead, redirect
them to a second skoolyst.com endpoint that closes the loop automatically:

```
GET https://skoolyst.com/oauth/verify-required
    ?client_id=skl_client_xxxxxxxxxxxxxxxxxxxxxxxx
    &redirect_uri=https%3A%2F%2Fblogs.skoolyst.com%2Fauth%2Fskoolyst%2Fcallback
    &state=<a fresh state, generated and stored the same way as §3>
```

Same `client_id`/`redirect_uri` rules as `/oauth/authorize` (§3) — exact
match required, and it requires the user to be logged in on skoolyst.com
(they already are, at this point in the flow, from §2 step 3). What
happens next, entirely on skoolyst.com's side:

1. **Already verified by the time they land here** (e.g. they verified
   from an earlier attempt) → immediately redirected to `/oauth/authorize`
   with the same params, which mints a fresh `code` and sends them straight
   back to your `redirect_uri` — no email, no extra step.
2. **Not verified** → skoolyst.com emails them a verification link. That
   link, once clicked, marks the email verified *and* redirects the
   browser back through `/oauth/authorize` — landing on your `redirect_uri`
   with a fresh `code`, exactly as if they'd just completed step 3 of the
   normal flow (§2). Your callback code doesn't need to know or care that a
   verification detour happened in between.

```php
// Wherever you currently do:
//   flash('error', 'An account with this email already exists...');
//   redirect(url('/login'));
// do this instead:
function redirectToSkoolystVerifyRequired(): void
{
    $state = bin2hex(random_bytes(16));
    $_SESSION['skoolyst_oauth_state'] = $state; // same session slot §3's state check reads

    $query = http_build_query([
        'client_id'    => getenv('SKOOLYST_AUTH_CLIENT_ID'),
        'redirect_uri' => getenv('SKOOLYST_AUTH_REDIRECT_URI'),
        'state'        => $state,
    ]);

    header('Location: ' . rtrim(getenv('SKOOLYST_AUTH_BASE'), '/') . '/oauth/verify-required?' . $query);
    exit;
}
```

Your existing callback handler (§4, §9) needs no changes for this — the
browser comes back to the exact same `redirect_uri` with a `code` either
way, so the "already verified, mint a fresh code" and "verified via email,
then mint a fresh code" paths both look like an ordinary successful
callback by the time your code sees them.

---

## 7. What can go wrong before the callback

If `client_id`/`redirect_uri` themselves are invalid, skoolyst.com
returns an **HTTP 400 directly** (it does NOT redirect to an
unverified/unregistered `redirect_uri` — that would be an open-redirect
security hole). This only happens if your own config is wrong (typo'd
`client_id`, or a `redirect_uri` that isn't registered exactly) — it's not
something to handle per-request, just get it right once during setup and
test it.

---

## 8. Step 5 (optional) — verify / refresh a token later

If you kept the `access_token` from step 4, you can call this anytime to
re-check it's still valid and pull fresh name/email (e.g. the user changed
their name on skoolyst.com since they last logged into your app):

```bash
curl https://skoolyst.com/api/oauth/user \
  -H "Authorization: Bearer 2|abcdef123456..."
```

`200` with the same `user` shape as above, or `401 {"message":"Unauthenticated."}`
if the token was revoked/invalid. This call is entirely optional — most
integrations only need steps 1-5 above for the login itself.

---

## 9. Example: PHP (framework-agnostic)

```php
// --- Step 2: redirect the user ---
function redirectToSkoolystLogin(): void
{
    $state = bin2hex(random_bytes(16));
    $_SESSION['skoolyst_oauth_state'] = $state; // use your framework's session instead

    $query = http_build_query([
        'client_id'    => getenv('SKOOLYST_AUTH_CLIENT_ID'),
        'redirect_uri' => getenv('SKOOLYST_AUTH_REDIRECT_URI'),
        'state'        => $state,
    ]);

    header('Location: ' . rtrim(getenv('SKOOLYST_AUTH_BASE'), '/') . '/oauth/authorize?' . $query);
    exit;
}

// --- Step 3 + 4: your redirect_uri route calls this ---
function handleSkoolystCallback(string $code, string $state): array
{
    if (!hash_equals($_SESSION['skoolyst_oauth_state'] ?? '', $state)) {
        throw new RuntimeException('OAuth state mismatch — possible CSRF, aborting.');
    }
    unset($_SESSION['skoolyst_oauth_state']);

    $ch = curl_init(rtrim(getenv('SKOOLYST_AUTH_BASE'), '/') . '/api/oauth/token');
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
        CURLOPT_POSTFIELDS     => json_encode([
            'client_id'     => getenv('SKOOLYST_AUTH_CLIENT_ID'),
            'client_secret' => getenv('SKOOLYST_AUTH_CLIENT_SECRET'),
            'code'          => $code,
            'redirect_uri'  => getenv('SKOOLYST_AUTH_REDIRECT_URI'),
        ]),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 15,
    ]);
    $response = json_decode((string) curl_exec($ch), true);
    $status   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($status !== 201 || empty($response['success'])) {
        error_log('Skoolyst login failed: ' . json_encode($response));
        throw new RuntimeException($response['error']['message'] ?? 'Login failed.');
    }

    return $response['data']; // ['user' => [...], 'access_token' => '...', 'token_type' => 'Bearer']
}

// In your callback route:
// $data = handleSkoolystCallback($_GET['code'], $_GET['state']);
// $localUser = User::firstOrCreate(['skoolyst_id' => $data['user']['id']], [
//     'name'  => $data['user']['name'],
//     'email' => $data['user']['email'],
// ]);
// log the user into YOUR app's own session here (your framework's normal login call)
```

### Laravel-specific note

If your app is also Laravel, `firstOrCreate` + `Auth::login($localUser)`
inside the callback route is all you need — no package required. Store
`skoolyst_id` as a unique column on your `users` table so re-logins match
the existing local account instead of creating duplicates.

---

## 10. Security checklist

- [ ] `client_secret` lives only in server-side config, never in JS/mobile app code.
- [ ] `redirect_uri` you send in step 2 is registered **exactly** (protocol, host, path, no trailing slash surprises) on skoolyst.com.
- [ ] You generate and verify `state` yourself — skoolyst.com does not protect you from CSRF, only from its own side (redirect-URI validation, single-use codes).
- [ ] All URLs are `https://` in production (localhost/127.0.0.1 is allowed only for local dev registration).
- [ ] You key local users by `user.id` from the response, not by email.
- [ ] The token exchange (step 4) happens on **your server**, never via a browser `fetch()`/AJAX call — that would expose `client_secret`.
- [ ] You never auto-link an existing local account to an incoming Skoolyst identity on email match alone — only when `email_verified` is `true` (§6).

---

## 11. If something's not working

- **400 on `/oauth/authorize`** → your `client_id` or `redirect_uri` is wrong/not registered. Check for typos and exact-match issues (trailing slash, http vs https).
- **`invalid_grant` on token exchange** → the code was already used (can only be redeemed once), expired (>5 minutes since redirect), or your `redirect_uri` in step 4 doesn't exactly match step 2's.
- **`invalid_client`** → `client_id`/`client_secret` mismatch — re-check your `.env`, or ask an admin to regenerate the secret if it may have been lost.
- **User stuck on an "account already exists" message with no way forward** → you're showing `email_verified: false` as a dead-end error instead of redirecting to `/oauth/verify-required` (§6). This is the most common integration gap once an app has both SSO and password users.
- Ask a skoolyst.com admin to check **Dashboard → Connected Apps** — they can see your app's `last_used_at` timestamp there, which tells you whether requests are reaching us at all.
