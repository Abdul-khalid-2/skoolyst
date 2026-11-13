<?php

namespace App\Http\Controllers;

use App\Models\OAuthClient;
use Illuminate\Http\Request;

/**
 * Dashboard CRUD for "Connected Apps" — the Skoolyst-family apps (blogs,
 * mcqs, store, ...) allowed to use "Login with Skoolyst". See
 * app/Http/Controllers/OAuthController.php for the actual login flow, and
 * integrate_login_with_skoolyst.md for what to hand the other app's dev.
 */
class OAuthClientController extends Controller
{
    public function index()
    {
        $clients = OAuthClient::orderBy('name')->get();

        // Read-and-remove, not just peek: guarantees the one-time secret
        // can never reappear on a later page view (a plain session flash
        // can linger into the request after next depending on timing —
        // pull() deletes it from the session the instant we read it here).
        $newCredentials = session()->pull('new_client_credentials');

        return view('dashboard.oauth-clients.index', compact('clients', 'newCredentials'));
    }

    public function create()
    {
        return view('dashboard.oauth-clients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'redirect_uris' => 'required|string',
        ]);

        $redirectUris = $this->parseRedirectUris($validated['redirect_uris']);

        if (empty($redirectUris)) {
            return back()->withInput()->with('error', 'Enter at least one valid redirect URL.');
        }

        $plainSecret = OAuthClient::generateClientSecret();

        $client = new OAuthClient([
            'name' => $validated['name'],
            'client_id' => OAuthClient::generateClientId(),
            'redirect_uris' => $redirectUris,
            'is_active' => true,
        ]);
        $client->setPlainSecret($plainSecret);
        $client->save();

        return redirect()->route('oauth-clients.index')
            ->with('new_client_credentials', [
                'name' => $client->name,
                'client_id' => $client->client_id,
                'client_secret' => $plainSecret,
            ])
            ->with('success', "Connected app \"{$client->name}\" created.");
    }

    public function edit(OAuthClient $oauthClient)
    {
        return view('dashboard.oauth-clients.edit', ['client' => $oauthClient]);
    }

    public function update(Request $request, OAuthClient $oauthClient)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'redirect_uris' => 'required|string',
            'is_active' => 'nullable|boolean',
        ]);

        $redirectUris = $this->parseRedirectUris($validated['redirect_uris']);

        if (empty($redirectUris)) {
            return back()->withInput()->with('error', 'Enter at least one valid redirect URL.');
        }

        $oauthClient->update([
            'name' => $validated['name'],
            'redirect_uris' => $redirectUris,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('oauth-clients.index')
            ->with('success', "\"{$oauthClient->name}\" updated.");
    }

    public function regenerateSecret(OAuthClient $oauthClient)
    {
        $plainSecret = OAuthClient::generateClientSecret();
        $oauthClient->setPlainSecret($plainSecret);
        $oauthClient->save();

        return redirect()->route('oauth-clients.index')
            ->with('new_client_credentials', [
                'name' => $oauthClient->name,
                'client_id' => $oauthClient->client_id,
                'client_secret' => $plainSecret,
            ])
            ->with('success', "Secret regenerated for \"{$oauthClient->name}\". The old secret stopped working immediately.");
    }

    public function destroy(OAuthClient $oauthClient)
    {
        $name = $oauthClient->name;
        $oauthClient->delete();

        return redirect()->route('oauth-clients.index')
            ->with('success', "\"{$name}\" removed. It can no longer log users in.");
    }

    /**
     * One URL per line (or comma-separated) -> clean array, https:// enforced
     * except for localhost/127.0.0.1 (local dev).
     */
    private function parseRedirectUris(string $raw): array
    {
        $lines = preg_split('/[\r\n,]+/', $raw);
        $uris = [];

        foreach ($lines as $line) {
            $uri = trim($line);
            if ($uri === '' || filter_var($uri, FILTER_VALIDATE_URL) === false) {
                continue;
            }

            $isLocal = preg_match('#^https?://(localhost|127\.0\.0\.1)(:\d+)?(/|$)#i', $uri) === 1;
            if (!$isLocal && !str_starts_with($uri, 'https://')) {
                continue;
            }

            $uris[] = $uri;
        }

        return array_values(array_unique($uris));
    }
}
