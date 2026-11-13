<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

/**
 * Server-side client for the central ads.skoolyst.com ad engine.
 *
 * The API key never reaches the browser: this service is the only thing
 * that talks to ads.skoolyst.com directly. Blade views/components only ever
 * see the resolved ad array (or null).
 */
class AdService
{
    /**
     * Fetch the ad for a friendly placement slot (e.g. "home").
     *
     * Successful responses (including a legitimate "no ad matched" result)
     * are cached to disk for config('ads.cache_ttl') seconds. Transport
     * failures (timeout/DNS/curl error) are never cached, so a temporary
     * outage on either side self-heals on the very next request instead of
     * being stuck showing "no ad" for a full cache cycle.
     */
    public function getAd(string $slot): ?array
    {
        $placementCode = $this->placementCode($slot);

        if (empty($placementCode) || empty(config('ads.base_url')) || empty(config('ads.api_key'))) {
            return null;
        }

        $cacheFile = $this->cacheFile($placementCode);
        $cached = $this->readCache($cacheFile);

        if ($cached !== null) {
            return $cached['ad'];
        }

        $result = $this->fetchFromApi($placementCode);

        if ($result === null) {
            // Transport-level failure — do not cache, just return null for
            // this request and let the next request try again fresh.
            return null;
        }

        $this->writeCache($cacheFile, $result);

        if ($result['ad'] !== null) {
            $this->trackImpression($placementCode);
        }

        return $result['ad'];
    }

    /**
     * Resolve a friendly slot name to its ads.skoolyst.com placement code.
     */
    public function placementCode(string $slot): ?string
    {
        $code = config("ads.placements.{$slot}");

        return empty($code) ? null : $code;
    }

    /**
     * Resolve a relative image_path returned by the ad engine into an
     * absolute URL against the ad app's own document root (not this app's).
     */
    public function imageUrl(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        if (preg_match('#^(https?:|data:)#i', $path)) {
            return $path;
        }

        $base = rtrim((string) config('ads.base_url'), '/');
        // Strip a trailing /api or /api/vN segment — image paths are served
        // from the ad app's public root, not its API path.
        $base = preg_replace('#/api(/v\d+)?$#i', '', $base);

        return $base . '/' . ltrim($path, '/');
    }

    /**
     * Sanity-check a click URL before ever rendering it. The ad engine has
     * occasionally been observed to return a malformed, doubled-up URL
     * (e.g. "https://x.inhttps://y.com") — never trust it blindly.
     */
    public function sanitizeClickUrl(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        if (preg_match('#^https?://#i', $url) !== 1) {
            return null;
        }

        // A second "http(s)://" appearing anywhere after the first one is
        // the doubled-URL bug — reject rather than guess which half is real.
        if (preg_match('#^https?://.*https?://#i', $url)) {
            Log::warning('AdService: rejected malformed doubled click_url', ['url' => $url]);

            return null;
        }

        return filter_var($url, FILTER_VALIDATE_URL) !== false ? $url : null;
    }

    /**
     * Best-effort impression ping. Never allowed to break page rendering —
     * any failure is caught and logged, not thrown.
     *
     * NOTE: endpoint path is a reasonable guess (`/ads/impression`) — adjust
     * to match the real ads.skoolyst.com API docs once confirmed.
     */
    public function trackImpression(string $placementCode): void
    {
        $this->fireAndForget('/ads/impression', $placementCode);
    }

    /**
     * Best-effort click ping, called server-side from the /ads/click
     * redirect route (see AdController) before redirecting the visitor on
     * to the advertiser's URL.
     *
     * NOTE: endpoint path is a reasonable guess (`/ads/click`) — adjust to
     * match the real ads.skoolyst.com API docs once confirmed.
     */
    public function trackClick(string $placementCode): void
    {
        $this->fireAndForget('/ads/click', $placementCode);
    }

    private function fireAndForget(string $path, string $placementCode): void
    {
        $baseUrl = config('ads.base_url');
        $apiKey = config('ads.api_key');

        if (empty($baseUrl) || empty($apiKey)) {
            return;
        }

        try {
            $ch = curl_init(rtrim($baseUrl, '/') . $path);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['placement' => $placementCode]));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $apiKey,
                'Content-Type: application/json',
            ]);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_exec($ch);
            curl_close($ch);
        } catch (\Throwable $e) {
            Log::warning('AdService: tracking ping failed', [
                'path' => $path,
                'placement' => $placementCode,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Direct API call — used by getAd() and by the temporary debug route.
     * Returns null on any transport-level failure; returns
     * ['ad' => array|null, ...] on any successful (2xx) response.
     */
    public function fetchFromApi(string $placementCode): ?array
    {
        $baseUrl = rtrim((string) config('ads.base_url'), '/');
        $url = $baseUrl . '/ads/serve?placement=' . urlencode($placementCode);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . config('ads.api_key')]);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, config('ads.connect_timeout'));
        curl_setopt($ch, CURLOPT_TIMEOUT, config('ads.timeout'));

        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $errno = curl_errno($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($errno !== 0) {
            Log::warning('AdService: curl transport error', [
                'placement' => $placementCode,
                'errno' => $errno,
                'error' => $error,
            ]);

            return null;
        }

        if ($status < 200 || $status >= 300) {
            Log::warning('AdService: non-2xx response', [
                'placement' => $placementCode,
                'status' => $status,
                'body' => $response,
            ]);

            return null;
        }

        $decoded = json_decode((string) $response, true);

        if (!is_array($decoded) || !array_key_exists('data', $decoded)) {
            Log::warning('AdService: unexpected response shape', [
                'placement' => $placementCode,
                'body' => $response,
            ]);

            return null;
        }

        return ['ad' => $decoded['data']['ad'] ?? null];
    }

    private function cacheFile(string $placementCode): string
    {
        return sys_get_temp_dir() . '/skoolyst_ad_' . md5($placementCode) . '.json';
    }

    private function readCache(string $file): ?array
    {
        if (!is_file($file)) {
            return null;
        }

        $raw = @file_get_contents($file);
        $decoded = $raw !== false ? json_decode($raw, true) : null;

        if (!is_array($decoded) || !isset($decoded['cached_at'])) {
            return null;
        }

        $ttl = (int) config('ads.cache_ttl');
        if ((time() - (int) $decoded['cached_at']) >= $ttl) {
            return null;
        }

        return $decoded;
    }

    private function writeCache(string $file, array $result): void
    {
        $payload = array_merge($result, ['cached_at' => time()]);

        @file_put_contents($file, json_encode($payload));
    }
}
