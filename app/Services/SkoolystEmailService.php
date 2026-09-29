<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

/**
 * Server-side client for the central Skoolyst Email API
 * (ads.skoolyst.com /api/v1/email/send). API key never reaches the browser.
 */
class SkoolystEmailService
{
    /**
     * Send one email. Returns a normalized result array — never throws for
     * an ordinary API error response (401/403/422/429/503), only for a
     * hard transport failure (timeout/DNS/curl error), so callers can
     * decide what to do (retry, log, surface to user) without try/catch.
     *
     * @return array{
     *     success: bool,
     *     status: int,
     *     message_id: int|null,
     *     sent_via: string|null,
     *     error_code: string|null,
     *     error_message: string|null,
     * }
     */
    public function send(string $to, string $subject, string $body): array
    {
        $baseUrl = config('skoolyst_email.base_url');
        $apiKey = config('skoolyst_email.api_key');

        if (empty($baseUrl) || empty($apiKey)) {
            return $this->failure(0, 'not_configured', 'SKOOLYST_EMAIL_API_KEY is not set in .env.');
        }

        // API limits: subject 255 chars, body 100,000 chars.
        $subject = mb_substr($subject, 0, 255);
        $body = mb_substr($body, 0, 100000);

        $payload = json_encode([
            'api_key' => $apiKey,
            'source_app' => config('skoolyst_email.source_app'),
            'to' => $to,
            'subject' => $subject,
            'body' => $body,
        ]);

        $ch = curl_init(rtrim($baseUrl, '/') . '/email/send');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apiKey,
        ]);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, (int) config('skoolyst_email.timeout'));

        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $errno = curl_errno($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($errno !== 0) {
            Log::warning('SkoolystEmailService: curl transport error', [
                'to' => $to,
                'errno' => $errno,
                'error' => $error,
            ]);

            return $this->failure(0, 'transport_error', $error ?: "curl errno {$errno}");
        }

        $decoded = json_decode((string) $response, true);

        if ($status === 201 && is_array($decoded) && !empty($decoded['success'])) {
            return [
                'success' => true,
                'status' => $status,
                'message_id' => $decoded['data']['message_id'] ?? null,
                'sent_via' => $decoded['data']['sent_via'] ?? null,
                'error_code' => null,
                'error_message' => null,
            ];
        }

        $errorCode = $decoded['error']['code'] ?? 'unexpected_response';
        $errorMessage = $decoded['error']['message'] ?? ('Unexpected response (HTTP ' . $status . '): ' . $response);

        Log::warning('SkoolystEmailService: API error response', [
            'to' => $to,
            'status' => $status,
            'code' => $errorCode,
            'message' => $errorMessage,
        ]);

        return $this->failure($status, $errorCode, $errorMessage);
    }

    private function failure(int $status, string $code, string $message): array
    {
        return [
            'success' => false,
            'status' => $status,
            'message_id' => null,
            'sent_via' => null,
            'error_code' => $code,
            'error_message' => $message,
        ];
    }
}
