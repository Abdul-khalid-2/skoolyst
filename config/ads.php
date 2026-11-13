<?php

return [

    // Base URL of the central ads.skoolyst.com API (no trailing slash needed).
    'base_url' => env('ADS_API_BASE', ''),

    // Server-side only — never expose this to the browser.
    'api_key' => env('ADS_API_KEY', ''),

    // How long (seconds) a successful API response is cached on disk before
    // being re-fetched. Transport failures (timeout/DNS/curl error) are never
    // cached, so a temporary outage self-heals on the next request.
    'cache_ttl' => (int) env('ADS_CACHE_TTL', 30),

    // curl timeouts — kept generous because ads.skoolyst.com occasionally
    // takes several seconds to respond; a short timeout produces false
    // "no ad" results.
    'connect_timeout' => (int) env('ADS_CONNECT_TIMEOUT', 10),
    'timeout' => (int) env('ADS_TIMEOUT', 20),

    // Friendly slot name (used in Blade/controllers) => ads.skoolyst.com
    // placement code (from the ads.skoolyst.com admin -> Connected Apps).
    'placements' => [
        'home' => env('ADS_PLACEMENT_HOME', ''),
        'videos' => env('ADS_PLACEMENT_VIDEOS', ''),
        'browse_schools' => env('ADS_PLACEMENT_BROWSE_SCHOOLS', ''),
        'about' => env('ADS_PLACEMENT_ABOUT', ''),
    ],

    // Fallback "advertise with us" contact details shown when a placement
    // has no active ad returned by the engine.
    'contact_email' => env('SITE_ADS_CONTACT_EMAIL', 'skoolyst@gmail.com'),
    'contact_phone' => env('SITE_ADS_CONTACT_PHONE', '+92 334 0673401'),

];
