<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Ads Microservice Base URL
    |--------------------------------------------------------------------------
    |
    | The AdEngine app (e.g. https://adds.skoolyst.com) that serves approved
    | client advertisements over a JSON API. Leave this pointed at the real
    | subdomain once that app is deployed.
    |
    */
    'api_url' => env('ADS_API_URL', 'https://adds.skoolyst.com/api/v1'),

    /*
    |--------------------------------------------------------------------------
    | API Key
    |--------------------------------------------------------------------------
    |
    | Skoolyst's own key issued by the AdEngine app. Every consumer app
    | (Skoolyst, and future apps) gets its own key so the AdEngine can
    | track/rate-limit per app and know which "placement" it's asking for.
    |
    */
    'api_key' => env('ADS_API_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Request Timeout (seconds)
    |--------------------------------------------------------------------------
    |
    | Kept short on purpose — if the AdEngine app is slow/down, Skoolyst's
    | own pages must never hang waiting on it.
    |
    */
    'timeout' => env('ADS_API_TIMEOUT', 2),

    /*
    |--------------------------------------------------------------------------
    | Cache TTL (seconds)
    |--------------------------------------------------------------------------
    |
    | Ads are cached so we don't hit the remote API on every page load.
    |
    */
    'cache_ttl' => env('ADS_CACHE_TTL', 300),

    /*
    |--------------------------------------------------------------------------
    | Enabled
    |--------------------------------------------------------------------------
    |
    | Master switch. When false, the ad board always renders the static
    | fallback (or the "advertise with us" notice) and never calls the API.
    | Useful while the AdEngine app doesn't exist yet.
    |
    */
    'enabled' => env('ADS_API_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | Static Fallback Ads
    |--------------------------------------------------------------------------
    |
    | Used when the API is disabled, unreachable, or returns no ads for the
    | requested placement. Keeps the board looking populated instead of
    | breaking or going blank. Remove/replace once the AdEngine app is live.
    |
    */
    'fallback_ads' => [
        [
            'media_type' => 'image',
            'media' => 'assets/images/ads/placeholder-ad-1.jpg',
            'title' => 'Advertise Your School Here',
            'description' => 'Reach thousands of parents, students, and teachers across Pakistan. Premium placement available.',
            'url' => 'mailto:skoolyst@gmail.com',
            'cta' => 'Get Started',
        ],
    ],

];
