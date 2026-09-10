<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * X-Robots-Tag: noindex, nofollow for auxiliary / non-HTML URLs (localized paths).
 *
 * Covers public video comments stubs, dashboard reaction POST targets, etc.
 * (The MCQ practice-check condition this class was originally named for was
 * removed with the public MCQ module — Step 3, 2026-09-10 — but this
 * middleware is still shared/registered globally for the video conditions
 * below, so the class itself is kept.)
 */
class SetNoindexRobotsForMcqPracticeCheck
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $path = (string) $request->path();

        if (preg_match('#^[^/]+/videos/[^/]+/comments$#', $path)
            || preg_match('#^[^/]+/dashboard/videos/[^/]+/reactions$#', $path)) {
            $response->headers->set('X-Robots-Tag', 'noindex, nofollow', true);
        }

        return $response;
    }
}
