<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Captcha-free bot filter for public forms.
 *  - Honeypot: a hidden field that only bots fill  -> silent fake success.
 *  - Time trap: form submitted impossibly fast / token missing or forged.
 * Pair with the <x-spam-guard /> Blade component inside the form.
 */
class RejectSpamBots
{
    public function handle(Request $request, Closure $next): Response
    {
        $honeypot = config('spam.honeypot_field');
        $timeField = config('spam.time_field');

        // Honeypot filled => bot. Pretend everything worked so it doesn't adapt.
        if ($request->filled($honeypot)) {
            Log::warning('Spam bot blocked (honeypot)', [
                'ip' => $request->ip(),
                'path' => $request->path(),
                'ua' => substr((string) $request->userAgent(), 0, 120),
            ]);

            return redirect()->route('website.home');
        }

        // Time trap
        $renderedAt = null;
        try {
            $renderedAt = (int) decrypt((string) $request->input($timeField));
        } catch (\Throwable $e) {
            $renderedAt = null;
        }

        if (! $renderedAt || (now()->timestamp - $renderedAt) < (int) config('spam.min_seconds', 3)) {
            Log::warning('Spam bot blocked (time trap)', [
                'ip' => $request->ip(),
                'path' => $request->path(),
                'token_ok' => (bool) $renderedAt,
            ]);

            return back()
                ->withInput($request->except(['password', 'password_confirmation', 'admin_password', 'admin_password_confirmation']))
                ->withErrors(['spam' => 'Please wait a moment and submit the form again.']);
        }

        return $next($request);
    }
}
