<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * X-Robots-Tag: noindex, nofollow for MCQ answer-check endpoints
 * (e.g. /en/mcq/practice/{uuid}/check).
 */
class SetNoindexRobotsForMcqPracticeCheck
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (preg_match('#/mcq/practice/[^/]+/check#', (string) $request->path())) {
            $response->headers->set('X-Robots-Tag', 'noindex, nofollow', true);
        }

        return $response;
    }
}
