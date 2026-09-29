<?php

namespace App\Http\Controllers;

use App\Services\SkoolystEmailService;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    public function __construct(private SkoolystEmailService $service)
    {
    }

    /**
     * TEMPORARY diagnostic route — DELETE (or remove from routes/web.php)
     * once the Skoolyst Email API is confirmed working in production.
     *
     * Usage: /email-debug?to=you@example.com
     */
    public function debug(Request $request)
    {
        if (!app()->environment(['local', 'staging'])) {
            abort(404);
        }

        $to = $request->query('to');

        $lines = [];
        $lines[] = '=== 1. Config (as loaded by config/skoolyst_email.php) ===';
        $lines[] = 'SKOOLYST_EMAIL_API_BASE: ' . config('skoolyst_email.base_url');
        $apiKey = (string) config('skoolyst_email.api_key');
        $lines[] = 'SKOOLYST_EMAIL_API_KEY (first 12 chars): ' . substr($apiKey, 0, 12) . '... (' . strlen($apiKey) . ' chars total)';
        $lines[] = 'SKOOLYST_EMAIL_SOURCE_APP: ' . config('skoolyst_email.source_app');
        $lines[] = 'Current default mailer (MAIL_MAILER): ' . config('mail.default') . ' — must be "skoolyst" for real app emails to actually use this.';

        if (!$to || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
            $lines[] = '';
            $lines[] = 'Pass a real recipient: /email-debug?to=you@example.com';

            return response(implode("\n", $lines), 200, ['Content-Type' => 'text/plain']);
        }

        $lines[] = '';
        $lines[] = "=== 2. Sending a real test email to {$to} ===";
        $start = microtime(true);
        $result = $this->service->send(
            $to,
            'Skoolyst Email API test',
            "This is a test email sent from the /email-debug route.\nSent at: " . now()->toDateTimeString()
        );
        $elapsed = round(microtime(true) - $start, 2);
        $lines[] = "Took: {$elapsed}s";
        $lines[] = 'Result: ' . json_encode($result, JSON_PRETTY_PRINT);

        $lines[] = '';
        $lines[] = '=== Verdict ===';
        if ($result['success']) {
            $lines[] = "SUCCESS — check {$to}'s inbox. Sent via: " . ($result['sent_via'] ?? '(unknown)');
        } elseif ($result['error_code'] === 'not_configured') {
            $lines[] = 'SKOOLYST_EMAIL_API_KEY is empty in .env — add it from ads.skoolyst.com Admin -> Email Accounts -> API Clients.';
        } elseif ($result['status'] === 401) {
            $lines[] = 'Unauthorized — API key is missing/invalid/disabled. Regenerate it in the admin panel.';
        } elseif ($result['status'] === 403) {
            $lines[] = "Forbidden — SKOOLYST_EMAIL_SOURCE_APP ('" . config('skoolyst_email.source_app') . "') does not match the app name this key was issued for.";
        } elseif ($result['status'] === 422) {
            $lines[] = 'Validation error from the API: ' . $result['error_message'];
        } elseif ($result['status'] === 429) {
            $lines[] = 'Rate limited (60 req/min per key) — back off and retry.';
        } elseif ($result['status'] === 503) {
            $lines[] = 'Service temporarily unavailable (' . $result['error_code'] . ') — every sender account may be exhausted for today, or SMTP failed on all of them. Retry later.';
        } else {
            $lines[] = 'Transport-level failure (timeout/DNS/curl error) — check firewall/antivirus/base URL. See storage/logs/laravel.log.';
        }

        return response(implode("\n", $lines), 200, ['Content-Type' => 'text/plain']);
    }
}
