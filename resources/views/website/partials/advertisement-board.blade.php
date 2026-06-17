@php
$ads = [
    // TO ADD A CLIENT AD, uncomment and fill one block:
    // ['media_type' => 'image', 'media' => 'public\website\school\dominic-barker\logo\634139d0-9209-4374-a7cf-659b5ee0bb5f.webp', 'title' => 'Title Here', 'description' => 'Full Description Here', 'url' => 'https://...', 'cta' => 'Visit Website'],
    // Paths under public/ — use website/... (no "public/" prefix), full https:// URL, or Google Images link (imgurl is auto-extracted):
    // ['media_type' => 'image', 'media' => 'https://www.google.com/imgres?q=image&imgurl=https%3A%2F%2Fupload.wikimedia.org%2Fwikipedia%2Fcommons%2Fb%2Fb6%2FImage_created_with_a_mobile_phone.png&imgrefurl=https%3A%2F%2Fen.wikipedia.org%2Fwiki%2FImage&docid=0JWe7yDOKrVFAM&tbnid=Q53WJiavu6IJtM&vet=12ahUKEwjB-t2h-46VAxVZV6QEHWwnLQQQnPAOegQIFxAB..i&w=4000&h=3000&hcb=2&ved=2ahUKEwjB-t2h-46VAxVZV6QEHWwnLQQQnPAOegQIFxAB', 'title' => 'Title Here', 'description' => 'Full Description Here', 'url' => 'https://...', 'cta' => 'Visit Website'],
    // ['media_type' => 'video', 'media' => 'https://...mp4', 'title' => '...', 'description' => '...', 'url' => 'https://...', 'cta' => 'Learn More'],
];
$contactEmail = 'skoolyst@gmail.com';
$contactWhatsApp = '+92 334 0673401';
$contactWhatsAppUrl = 'https://wa.me/' . preg_replace('/\D+/', '', $contactWhatsApp);

$resolveAdMedia = function (?string $media): ?string {
    if (empty($media)) {
        return null;
    }

    if (preg_match('#^(https?:|data:)#i', $media)) {
        // Google Image Search pages are not direct image URLs — extract ?imgurl=
        if (preg_match('#google\.[a-z.]+/imgres#i', $media)) {
            $query = [];
            parse_str(parse_url($media, PHP_URL_QUERY) ?? '', $query);

            if (!empty($query['imgurl'])) {
                return urldecode($query['imgurl']);
            }
        }

        return $media;
    }

    $path = str_replace('\\', '/', $media);
    $path = ltrim($path, '/');

    if (str_starts_with($path, 'public/')) {
        $path = substr($path, 7);
    }

    return asset($path);
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
