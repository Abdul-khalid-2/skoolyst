<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * X-Robots-Tag: noindex, nofollow for auxiliary / non-HTML URLs (localized paths).
 *
 * Covers MCQ answer check, public video comments stubs, dashboard reaction POST targets, etc.
 */
class SetNoindexRobotsForMcqPracticeCheck
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $path = (string) $request->path();

        if (preg_match('#^[^/]+/mcq/practice/[^/]+/check$#', $path)
            || preg_match('#^[^/]+/videos/[^/]+/comments$#', $path)
            || preg_match('#^[^/]+/dashboard/videos/[^/]+/reactions$#', $path)) {
            $response->headers->set('X-Robots-Tag', 'noindex, nofollow', true);
        }

        return $response;
    }
}
