<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Mail\AdminUserActivityMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Notify admin on successful login
        // if ($request->user()) {
        //     Mail::to('skoolyst@gmail.com')->send(
        //         new AdminUserActivityMail($request->user(), 'login')
        //     );
        // }

        $default = route('website.home', absolute: false);
        $intended = $request->session()->pull('url.intended', $default);

        // When the session expired, a background fetch to JSON endpoints (e.g. contact-inquiry
        // notification count) can be saved as "intended". Never redirect the browser there after login.
        if ($this->isUrlNotSafeForIntendedRedirect($intended, $default)) {
            $intended = $default;
        }

        return redirect()->to($intended);
    }

    /**
     * @param  string  $intended  Full URL or path from session (Laravel may store fullUrl()).
     */
    private function isUrlNotSafeForIntendedRedirect(string $intended, string $default): bool
    {
        if ($intended === $default) {
            return false;
        }
        $path = parse_url($intended, PHP_URL_PATH) ?? $intended;
        if (str_contains($path, 'notification-count')) {
            return true;
        }
        if (str_contains($path, '/api/') || str_ends_with($path, '/api') || $path === '/api') {
            return true;
        }

        return false;
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
