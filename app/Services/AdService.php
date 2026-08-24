<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AdService
{
    /**
     * Get ads for a given placement (e.g. "home", "shop", "mcqs", "blog", "about").
     *
     * Always returns an array shaped like:
     * [
     *   ['media_type' => 'image'|'video', 'media' => 'https://...', 'title' => '...',
     *    'description' => '...', 'url' => '...', 'cta' => '...'],
     *   ...
     * ]
     *
     * Never throws — on any failure it returns the static fallback (or empty
     * array) so the calling view can decide what to show instead.
     */
    public function forPlacement(string $placement, int $limit = 3): array
    {
        if (! config('ads.enabled')) {
            return config('ads.fallback_ads', []);
        }

        $cacheKey = "ads:placement:{$placement}:{$limit}";

        return Cache::remember($cacheKey, config('ads.cache_ttl', 300), function () use ($placement, $limit) {
            try {
                $response = Http::withHeaders([
                        'X-API-Key' => config('ads.api_key'),
                        'Accept' => 'application/json',
                    ])
                    ->timeout((int) config('ads.timeout', 2))
                    ->get(rtrim(config('ads.api_url'), '/') . '/ads', [
                        'placement' => $placement,
                        'limit' => $limit,
                    ]);

                if ($response->successful()) {
                    $ads = $response->json('data', []);

                    return is_array($ads) && count($ads) > 0
                        ? $ads
                        : config('ads.fallback_ads', []);
                }

                Log::warning('AdEngine API returned a non-successful response.', [
                    'placement' => $placement,
                    'status' => $response->status(),
                ]);
            } catch (\Throwable $e) {
                Log::warning('AdEngine API unreachable, using fallback ads.', [
                    'placement' => $placement,
                    'error' => $e->getMessage(),
                ]);
            }

            return config('ads.fallback_ads', []);
        });
    }

    /**
     * Clear cached ads for a placement (call this from an artisan command / webhook
     * later if you want instant refresh instead of waiting out the TTL).
     */
    public function forget(string $placement, int $limit = 3): void
    {
        Cache::forget("ads:placement:{$placement}:{$limit}");
    }
}
