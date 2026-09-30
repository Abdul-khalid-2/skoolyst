<?php

namespace App\Http\Controllers;

use App\Models\OAuthAuthCode;
use App\Models\OAuthClient;
use App\Notifications\SkoolystOAuthVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * "Login with Skoolyst" — this app is the central identity provider for the
 * rest of the Skoolyst family (blogs, mcqs, store, ...). Lightweight OAuth2
 * "Authorization Code" flow (not full RFC 6749 — no scopes/refresh tokens,
 * by design, since every consumer is a first-party Skoolyst app).
 *
 * Flow:
 *   1. GET  /oauth/authorize   (browser, requires login — see routes/web.php)
 *   2. POST /api/oauth/token   (consumer app's SERVER, exchanges code for user + token)
 *   3. GET  /api/oauth/user    (consumer app's SERVER, optional — verify/refresh a token)
 *
 * See integrate_login_with_skoolyst.md for the consumer-side integration guide.
 */
class OAuthController extends Controller
{
    /**
     * Step 1 — the browser lands here (already authenticated, thanks to the
     * `auth` middleware on this route: Laravel stores this URL as the
     * "intended" redirect, sends a guest to login, and comes straight back
     * here after login — no custom session handling needed).
     *
     * No consent screen: every registered client is a first-party Skoolyst
     * app, so an already-logged-in user is redirected back immediately.
     */
    public function authorize(Request $request)
    {
        $request->validate([
            'client_id' => 'required|string',
            'redirect_uri' => 'required|string',
            'state' => 'nullable|string|max:255',
        ]);

        $client = OAuthClient::where('client_id', $request->client_id)
            ->where('is_active', true)
            ->first();

        if (!$client) {
            abort(400, 'Unknown or inactive client_id.');
        }

        // Exact match only — never partial/prefix match a redirect_uri.
        // This is the single most important check in the whole flow: it is
        // what stops an attacker registering their own client_id and
        // stealing codes by supplying an attacker-controlled redirect_uri.
        if (!$client->allowsRedirectUri($request->redirect_uri)) {
            abort(400, 'redirect_uri is not registered for this client.');
        }

        $code = OAuthAuthCode::create([
            'code' => OAuthAuthCode::generateCode(),
            'client_id' => $client->client_id,
            'user_id' => $request->user()->id,
            'redirect_uri' => $request->redirect_uri,
            'state' => $request->state,
            'expires_at' => now()->addMinutes(5),
        ]);

        $client->forceFill(['last_used_at' => now()])->save();

        $separator = str_contains($request->redirect_uri, '?') ? '&' : '?';
        $query = http_build_query(array_filter([
            'code' => $code->code,
            'state' => $request->state,
        ]));

        return redirect()->away($request->redirect_uri . $separator . $query);
    }

    /**
     * A consumer app lands a logged-in user here when it found a local
     * account matching this user's email but couldn't safely auto-link it
     * (email not verified on our side — see integrate_login_with_skoolyst.md
     * and the "already exists" error a consumer app shows for this case).
     *
     * Same params as /oauth/authorize. If the user is already verified we
     * just mint a code immediately (no detour needed). Otherwise we email a
     * verification link that, once clicked, sends them straight back here
     * to /oauth/authorize — completing the SSO login they started —
     * instead of landing on our own dashboard.
     */
    public function verifyRequired(Request $request)
    {
        $request->validate([
            'client_id' => 'required|string',
            'redirect_uri' => 'required|string',
            'state' => 'nullable|string|max:255',
        ]);

        $client = OAuthClient::where('client_id', $request->client_id)
            ->where('is_active', true)
            ->first();

        if (!$client) {
            abort(400, 'Unknown or inactive client_id.');
        }

        if (!$client->allowsRedirectUri($request->redirect_uri)) {
            abort(400, 'redirect_uri is not registered for this client.');
        }

        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('oauth.authorize', $request->only(['client_id', 'redirect_uri', 'state']));
        }

        $user->notify(new SkoolystOAuthVerifyEmail(
            $request->only(['client_id', 'redirect_uri', 'state'])
        ));

        return view('auth.verify-email', ['oauthReturnPending' => true]);
    }

    /**
     * Step 2 — consumer app's server calls this to exchange the one-time
     * code for the user's basic info (+ a long-lived Sanctum token it can
     * later use to call /api/oauth/user and re-verify/refresh the user).
     */
    public function token(Request $request)
    {
        $request->validate([
            'client_id' => 'required|string',
            'client_secret' => 'required|string',
            'code' => 'required|string',
            'redirect_uri' => 'required|string',
        ]);

        $client = OAuthClient::where('client_id', $request->client_id)
            ->where('is_active', true)
            ->first();

        if (!$client || !$client->checkSecret($request->client_secret)) {
            return $this->error(401, 'invalid_client', 'Unknown client_id or wrong client_secret.');
        }

        $authCode = OAuthAuthCode::where('code', $request->code)
            ->where('client_id', $client->client_id)
            ->first();

        if (!$authCode || !$authCode->isValid()) {
            return $this->error(400, 'invalid_grant', 'Code is invalid, already used, or expired.');
        }

        // redirect_uri must match exactly what /oauth/authorize issued the
        // code for — stops a stolen code being redeemed from elsewhere.
        if (!hash_equals($authCode->redirect_uri, $request->redirect_uri)) {
            return $this->error(400, 'invalid_grant', 'redirect_uri does not match the one used to request this code.');
        }

        $authCode->update(['used_at' => now()]);

        $user = $authCode->user;

        if (!$user) {
            return $this->error(400, 'invalid_grant', 'The user for this code no longer exists.');
        }

        $token = $user->createToken('oauth:' . $client->client_id, ['oauth-client:' . $client->client_id]);

        Log::info('OAuth: token issued', ['client_id' => $client->client_id, 'user_id' => $user->id]);

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $this->userPayload($user),
                'access_token' => $token->plainTextToken,
                'token_type' => 'Bearer',
            ],
        ], 201);
    }

    /**
     * Step 3 (optional) — consumer app's server can call this later with
     * the access_token from step 2 to re-verify it's still valid and pull
     * fresh user info (e.g. in case the name/email changed on this side).
     */
    public function user(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return $this->error(401, 'unauthorized', 'Missing or invalid access token.');
        }

        return response()->json([
            'success' => true,
            'data' => ['user' => $this->userPayload($user)],
        ]);
    }

    private function userPayload($user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'email_verified' => $user->email_verified_at !== null,
        ];
    }

    private function error(int $status, string $code, string $message)
    {
        return response()->json([
            'success' => false,
            'error' => ['code' => $code, 'message' => $message],
        ], $status);
    }
}
