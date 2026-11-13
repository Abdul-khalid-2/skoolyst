<?php

namespace App\Http\Controllers;

use App\Services\AdService;
use Illuminate\Http\Request;

class AdController extends Controller
{
    public function __construct(private AdService $ads)
    {
    }

    /**
     * Server-side click redirect: records the click against the ad engine,
     * then sends the visitor on to the advertiser's URL. The click_url is
     * never rendered directly in Blade — it's always re-resolved here from
     * a fresh getAd() call, sanitized, and only then redirected to.
     */
    public function click(string $placement)
    {
        $ad = $this->ads->getAd($placement);
        $url = $ad ? $this->ads->sanitizeClickUrl($ad['click_url'] ?? $ad['url'] ?? null) : null;

        if (!$url) {
            return redirect()->route('website.home');
        }

        $placementCode = $this->ads->placementCode($placement);
        if ($placementCode) {
            $this->ads->trackClick($placementCode);
        }

        return redirect()->away($url);
    }

    /**
     * TEMPORARY diagnostic route — DELETE (or remove from routes/web.php)
     * once ads.skoolyst.com credentials are confirmed working in production.
     *
     * Usage: /ads-debug/{placement}  e.g. /ads-debug/home
     */
    public function debug(string $placement)
    {
        if (!app()->environment(['local', 'staging'])) {
            abort(404);
        }

        $lines = [];
        $lines[] = '=== 1. Config (as loaded by config/ads.php) ===';
        $lines[] = 'ADS_API_BASE: ' . config('ads.base_url');
        $apiKey = (string) config('ads.api_key');
        $lines[] = 'ADS_API_KEY (first 12 chars): ' . substr($apiKey, 0, 12) . '... (' . strlen($apiKey) . ' chars total)';
        $placementCode = $this->ads->placementCode($placement);
        $lines[] = "Placement slot '{$placement}' resolves to code: " . ($placementCode ?? '(EMPTY — check .env)');

        if (!$placementCode) {
            $lines[] = '';
            $lines[] = '=== Verdict ===';
            $lines[] = "No placement code configured for slot '{$placement}'. Set ADS_PLACEMENT_" . strtoupper($placement) . " in .env.";

            return response(implode("\n", $lines), 200, ['Content-Type' => 'text/plain']);
        }

        // Always clear the cache file first so a stale cached null/ad never
        // masks what the API is actually returning right now.
        $this->clearCacheFile($placementCode);

        $lines[] = '';
        $lines[] = '=== 2. Direct API call (cache cleared first) ===';
        $start = microtime(true);
        $result = $this->ads->fetchFromApi($placementCode);
        $elapsed = round(microtime(true) - $start, 2);
        $lines[] = "Took: {$elapsed}s";
        $lines[] = 'Raw result: ' . json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        $lines[] = '';
        $lines[] = '=== 3. What AdService::getAd() returns (fresh, cache still cleared) ===';
        $this->clearCacheFile($placementCode);
        $ad = $this->ads->getAd($placement);
        $lines[] = json_encode($ad, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        $lines[] = '';
        $lines[] = '=== Verdict ===';
        if ($result === null) {
            $lines[] = 'Transport-level failure (timeout/DNS/curl error) — check firewall/antivirus/base URL, see storage/logs/laravel.log for the curl errno.';
        } elseif ($result['ad'] === null) {
            $lines[] = "API call succeeded but returned ad: null — no ACTIVE ad is currently matched to placement code '{$placementCode}'. Check ads.skoolyst.com admin -> Connected Apps for this exact code, and that an ad is active + in date range for it.";
        } else {
            $lines[] = 'SUCCESS — a real ad was returned. If it does not appear on the live page, check that the page passes the right placement slot to the advertisement-board partial.';
        }

        return response(implode("\n", $lines), 200, ['Content-Type' => 'text/plain']);
    }

    private function clearCacheFile(string $placementCode): void
    {
        $file = sys_get_temp_dir() . '/skoolyst_ad_' . md5($placementCode) . '.json';
        if (is_file($file)) {
            @unlink($file);
        }
    }
}
