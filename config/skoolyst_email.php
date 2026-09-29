<?php

return [

    // Same host as the Ads API.
    'base_url' => env('SKOOLYST_EMAIL_API_BASE', 'https://ads.skoolyst.com/api/v1'),

    // Server-side only — never expose this to the browser. From
    // Admin -> Email Accounts -> API Clients on ads.skoolyst.com.
    'api_key' => env('SKOOLYST_EMAIL_API_KEY', ''),

    // Must match the app name the key was issued for, or every send gets a 403.
    'source_app' => env('SKOOLYST_EMAIL_SOURCE_APP', 'skoolyst-schools'),

    'timeout' => (int) env('SKOOLYST_EMAIL_TIMEOUT', 30),

];
