<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Single canonical host + HTTPS. Uses config('app.url') as the target host and scheme.
 * Skips in local / testing. Set APP_URL in production, e.g. https://www.skoolyst.com
 */
class ForceCanonicalUrl
{
    public function handle(Request $request, Closure $next): Response
    {
        if (app()->environment(['local', 'testing'])) {
            return $next($request);
        }

        $appUrl = rtrim((string) config('app.url'), '/');
        if ($appUrl === '') {
            return $next($request);
        }

        $parts = parse_url($appUrl);
        $canonicalHost = $parts['host'] ?? null;
        if (! $canonicalHost) {
            return $next($request);
        }

        $canonicalScheme = ($parts['scheme'] ?? 'https') === 'http' ? 'http' : 'https';
        $host = $request->getHost();
        $pathAndQuery = $request->getRequestUri();
        $isSecure = $request->isSecure() || $request->header('X-Forwarded-Proto') === 'https';

        if (! $isSecure) {
            return redirect()->away($canonicalScheme.'://'.$canonicalHost.$pathAndQuery, 301);
        }

        if (strtolower($host) !== strtolower($canonicalHost)) {
            return redirect()->away($canonicalScheme.'://'.$canonicalHost.$pathAndQuery, 301);
        }

        return $next($request);
    }
}
