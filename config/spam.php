<?php

/*
|--------------------------------------------------------------------------
| Anti-spam settings (registration forms)
|--------------------------------------------------------------------------
| Lightweight, captcha-free protection. Safe to tune from here.
*/

return [

    // Hidden field humans never fill. Bots usually do.
    'honeypot_field' => 'nickname_confirm',

    // Hidden field carrying an encrypted "form rendered at" timestamp.
    'time_field' => 'form_token',

    // Humans cannot fill a registration form faster than this (seconds).
    'min_seconds' => 3,

    // Domains/TLDs treated as "a link" when found inside name fields.
    'link_tlds' => [
        'com', 'net', 'org', 'info', 'biz', 'cc', 'ru', 'xyz', 'top', 'club',
        'site', 'online', 'shop', 'store', 'link', 'click', 'live', 'icu',
        'vip', 'pw', 'tk', 'ml', 'ga', 'cf', 'gq', 'work', 'fun', 'life',
    ],

    // Throwaway / disposable email providers. Add more as you notice them.
    'disposable_domains' => [
        'merepost.com',
        'mailinator.com',
        'guerrillamail.com', 'guerrillamail.net', 'guerrillamail.org',
        'sharklasers.com', 'grr.la',
        'tempmail.com', 'temp-mail.org', 'temp-mail.io', 'tempmailo.com',
        '10minutemail.com', '10minutemail.net',
        'yopmail.com', 'yopmail.net',
        'trashmail.com', 'trashmail.net',
        'throwawaymail.com', 'getnada.com', 'nada.email',
        'dispostable.com', 'maildrop.cc', 'mailnesia.com',
        'fakeinbox.com', 'mohmal.com', 'emailondeck.com',
        'burnermail.io', 'spamgourmet.com', 'moakt.com',
        'mintemail.com', 'mytemp.email', 'tmpmail.org', 'tmpmail.net',
    ],
];
