@php
// Ads are now served live by the central ads.skoolyst.com engine
// (App\Services\AdService) instead of a hardcoded array here. $placement
// must be passed in by the including view (e.g. ['placement' => 'home']) —
// see config/ads.php for the slot -> placement-code mapping.
$adService = app(\App\Services\AdService::class);
$liveAd = isset($placement) ? $adService->getAd($placement) : null;

$ads = [];
if ($liveAd) {
    $validClickUrl = $adService->sanitizeClickUrl($liveAd['click_url'] ?? $liveAd['url'] ?? null);

    $ads[] = [
        'media_type' => ($liveAd['media_type'] ?? 'image') === 'video' ? 'video' : 'image',
        'media' => $adService->imageUrl($liveAd['image_path'] ?? $liveAd['media'] ?? null),
        'title' => $liveAd['title'] ?? null,
        'description' => $liveAd['description'] ?? null,
        // Route the CTA through our own tracked-click redirect (never the
        // raw click_url) so AdController::click() records the click and
        // re-validates the URL server-side before sending the visitor on.
        'url' => $validClickUrl ? route('ads.click', $placement) : null,
        'cta' => $liveAd['cta'] ?? $liveAd['cta_text'] ?? 'Learn More',
    ];
}

$contactEmail = config('ads.contact_email');
$contactWhatsApp = config('ads.contact_phone');
$contactWhatsAppUrl = 'https://wa.me/' . preg_replace('/\D+/', '', $contactWhatsApp);

// Media URLs from the ad engine already come back as absolute URLs (see
// AdService::imageUrl()) — this just passes them through unchanged.
$resolveAdMedia = function (?string $media): ?string {
    return $media;
};
@endphp

@once
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/advertisement-board.css') }}?v={{ filemtime(public_path('assets/css/advertisement-board.css')) }}">
@endpush
@endonce

<section id="ad-board-section" class="ad-board-section" aria-label="Advertisement board">
    <div class="container">
        <button type="button" class="ad-board-section__dismiss" aria-label="Dismiss advertisement board" data-ad-board-dismiss>&times;</button>

        @if (count($ads) > 0)
            <div class="ad-board-list">
                @foreach ($ads as $ad)
                    @php $mediaUrl = $resolveAdMedia($ad['media'] ?? null); @endphp
                    <article class="ad-board-card">
                        <div class="ad-board-card__media">
                            @if (($ad['media_type'] ?? 'image') === 'video')
                                <video
                                    class="ad-board-card__video"
                                    src="{{ $mediaUrl }}"
                                    autoplay
                                    muted
                                    loop
                                    playsinline
                                ></video>
                            @else
                                <img
                                    class="ad-board-card__image"
                                    src="{{ $mediaUrl }}"
                                    alt="{{ $ad['title'] ?? 'Sponsored advertisement' }}"
                                    loading="lazy"
                                >
                            @endif
                        </div>
                        <div class="ad-board-card__body">
                            <span class="ad-board-card__badge">Sponsored</span>
                            @if (!empty($ad['title']))
                                <h3 class="ad-board-card__title">{{ $ad['title'] }}</h3>
                            @endif
                            @if (!empty($ad['description']))
                                <p class="ad-board-card__text">{{ $ad['description'] }}</p>
                            @endif
                            @if (!empty($ad['url']))
                                <a
                                    class="ad-board-card__cta"
                                    href="{{ $ad['url'] }}"
                                    target="_blank"
                                    rel="noopener sponsored"
                                >
                                    {{ $ad['cta'] ?? 'Learn More' }}
                                    <i class="fas fa-arrow-right" aria-hidden="true"></i>
                                </a>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="ad-board-notice">
                <div class="ad-board-notice__messages">
                    <div class="ad-board-notice__block">
                        <!-- <span class="ad-board-notice__lang">EN</span> -->
                        <h3 class="ad-board-notice__title">Want to advertise your business on SKOOLYST?</h3>
                        <p class="ad-board-notice__body">
                            Promote your shop, school, website, or service to parents, students, and educators across Pakistan.
                            SKOOLYST offers premium placement on our homepage, shop, MCQs, blog, and about pages.
                        </p>
                        <p class="ad-board-notice__cta-text">Contact us to run your advertisement</p>
                    </div>

                    <div class="ad-board-notice__divider" aria-hidden="true"></div>

                    <div class="ad-board-notice__block ad-board-notice__block--rtl">
                        <!-- <span class="ad-board-notice__lang">UR</span> -->
                        <h3 class="ad-board-notice__title text-rtl">کیا آپ SKOOLYST پر اپنے کاروبار کی تشہیر کرنا چاہتے ہیں؟</h3>
                        <p class="ad-board-notice__body text-rtl">
                            اپنی دکان، سکول، ویب سائٹ یا سروس کو پاکستان بھر کے والدین، طلباء اور اساتذہ تک پہنچائیں۔
                            SKOOLYST ہوم پیج، شاپ، MCQs، بلاگ اور About صفحات پر پریمیم جگہ فراہم کرتا ہے۔
                        </p>
                        <p class="ad-board-notice__cta-text text-rtl">تشہیر کے لیے ہم سے رابطہ کریں</p>
                    </div>
                </div>

                <div class="ad-board-notice__actions">
                    <a class="ad-board-notice__btn ad-board-notice__btn--primary" href="mailto:{{ $contactEmail }}">
                        <i class="fas fa-envelope" aria-hidden="true"></i>
                        {{ $contactEmail }}
                    </a>
                    <a class="ad-board-notice__btn ad-board-notice__btn--secondary" href="{{ $contactWhatsAppUrl }}" target="_blank" rel="noopener noreferrer">
                        <i class="fab fa-whatsapp" aria-hidden="true"></i>
                        {{ $contactWhatsApp }}
                    </a>
                </div>
            </div>
        @endif
    </div>
</section>

@once
@push('scripts')
<script>
(function () {
    var STORAGE_KEY = 'adBoardDismissed';
    var TTL_MS = 5 * 60 * 1000; // hide for 5 minutes, then show again
    var section = document.getElementById('ad-board-section');

    if (!section) {
        return;
    }

    function isDismissed() {
        try {
            var raw = localStorage.getItem(STORAGE_KEY);
            if (!raw) {
                return false;
            }

            var dismissedAt = parseInt(raw, 10);
            if (isNaN(dismissedAt)) {
                localStorage.removeItem(STORAGE_KEY);
                return false;
            }

            if ((Date.now() - dismissedAt) >= TTL_MS) {
                localStorage.removeItem(STORAGE_KEY);
                return false;
            }

            return true;
        } catch (e) {
            return false;
        }
    }

    function hideSection() {
        section.style.display = 'none';
    }

    if (isDismissed()) {
        hideSection();
        return;
    }

    var dismissBtn = section.querySelector('[data-ad-board-dismiss]');
    if (dismissBtn) {
        dismissBtn.addEventListener('click', function () {
            hideSection();
            try {
                localStorage.setItem(STORAGE_KEY, String(Date.now()));
            } catch (e) {}
        });
    }
})();
</script>
@endpush
@endonce
