<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="ltr">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Resource hints: start the connection to third-party origins as early as
         possible (DNS + TCP + TLS), in parallel with HTML parsing, so the actual
         requests for Bootstrap CSS / Font Awesome / etc. don't pay that latency
         on top of the fetch itself. This is the main lever for "render-blocking
         requests" savings, since we can't avoid needing those origins. --}}
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="dns-prefetch" href="https://pagead2.googlesyndication.com">
    <link rel="dns-prefetch" href="https://embed.tawk.to">

    {{-- Pages that build their own primary meta (title/description/OG/Twitter) via
         @push('meta') should pass pageSetsOwnMeta => true so the layout does not
         emit a second, conflicting <title>/description. --}}
    @unless($pageSetsOwnMeta ?? false)
    <!-- Primary Meta Tags -->
    <title>SKOOLYST Pakistan - Find Best Schools Near You | Compare, Apply & Connect</title>
    <meta name="description" content="SKOOLYST Pakistan helps parents discover, compare, and connect with the best schools across Pakistan. Find top CBSE, Cambridge, O/A Level, and Montessori schools by city, fees, and reviews.">
    <meta name="keywords" content="best schools in Pakistan, schools near me, Lahore schools, Karachi schools, Islamabad schools, Montessori schools, O Level schools, A Level schools, school admission Pakistan, school directory Pakistan, find schools online">
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="SKOOLYST Pakistan">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="Find & Compare Top Schools in Pakistan - SKOOLYST">
    <meta property="og:description" content="Discover and compare top schools across Pakistan including Karachi, Lahore, and Islamabad. Read reviews, explore curriculums, and apply online.">
    <meta property="og:image" content="{{ asset('assets/assets/hero1.png') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:site" content="@skoolystpk">
    <meta property="twitter:title" content="Find Best Schools in Pakistan - SKOOLYST">
    <meta property="twitter:description" content="Explore and compare the best schools across Pakistan. Find by city, curriculum, or fee structure.">
    <meta property="twitter:image" content="{{ asset('assets/assets/hero1.png') }}">
    @endunless

    @unless($pageSetsOwnCanonical ?? false)
    <!-- Default canonical; pages with a full @push("meta") block that includes their own should pass pageSetsOwnCanonical -->
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- hreflang alternates so the EN and UR versions of the same page are not
         treated as canonical duplicates of each other in Google Search Console. --}}
    @php
        try {
            $hreflangEn = LaravelLocalization::getLocalizedURL('en', null, [], true);
            $hreflangUr = LaravelLocalization::getLocalizedURL('ur', null, [], true);
        } catch (\Throwable $e) {
            $hreflangEn = $hreflangUr = null;
        }
    @endphp
    @if($hreflangEn)<link rel="alternate" hreflang="en" href="{{ $hreflangEn }}">@endif
    @if($hreflangUr)<link rel="alternate" hreflang="ur" href="{{ $hreflangUr }}">@endif
    @if($hreflangEn)<link rel="alternate" hreflang="x-default" href="{{ $hreflangEn }}">@endif
    @endunless

    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => 'SKOOLYST Pakistan',
            'url' => url('/'),
            'description' => "Pakistan's leading school discovery platform. Find, compare, and connect with the best schools in Karachi, Lahore, Islamabad,  Peshawer, and more.",
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => route('browseSchools.index') . '?search={search_term_string}',
                ],
                'query-input' => 'required name=search_term_string',
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'SKOOLYST Pakistan',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('assets/images/logo.png')
                ],
            ],
            'sameAs' => [
                'https://www.facebook.com/skoolystpk',
                'https://www.instagram.com/skoolystpk',
                'https://twitter.com/skoolystpk'
            ]
        ], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT) !!}
    </script>


    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Inter: self-hosted (all @font-face use font-display: swap — see public/assets/css/fonts-inter.css) -->
    <link rel="stylesheet" href="{{ asset('assets/css/fonts-inter.css') }}">

    <!-- Font Awesome for Icons (non-critical, async) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"></noscript>

    <!-- Design System (public website — critical for first paint) -->
    <link rel="stylesheet" href="{{ asset('assets/css/design-system.css') }}">

    <!-- ================= RTL TEXT ONLY STYLES ================= -->
    <style>
        /* Only text RTL – layout remains LTR */
        .text-rtl {
            direction: rtl;
            unicode-bidi: isolate;
            /* text-align: right; */
        }

        /* Prevent RTL from breaking UI */
        nav, header, footer, .container, .row, .card, .btn {
            direction: ltr;
        }
    </style>

    @stack('styles')
    @stack('meta')
</head>

<body>

    <!-- ==================== NAVIGATION BAR ==================== -->

    @include('website.layout.header')

    <!-- ==================== FLASH TOAST (session success/error/status) ==================== -->
    @if (session('success') || session('error') || session('status'))
        @php
            $flashType = session('success') ? 'success' : (session('error') ? 'error' : 'status');
            $flashMessage = session('success') ?: (session('error') ?: session('status'));
        @endphp
        <div id="flashToast" class="flash-toast flash-toast--{{ $flashType }}" role="alert" aria-live="polite">
            <i class="fas {{ $flashType === 'error' ? 'fa-circle-exclamation' : 'fa-circle-check' }}"></i>
            <span>{{ $flashMessage }}</span>
            <button type="button" class="flash-toast__close" aria-label="Close" onclick="document.getElementById('flashToast').remove()">&times;</button>
        </div>
        <style>
            .flash-toast {
                position: fixed;
                top: 90px;
                right: 20px;
                z-index: 2000;
                display: flex;
                align-items: center;
                gap: 0.6rem;
                max-width: 380px;
                padding: 0.9rem 1.1rem;
                border-radius: 10px;
                box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
                font-size: 0.9rem;
                animation: flashToastIn 0.35s ease;
            }
            .flash-toast--success { background: #ecfdf5; border-left: 4px solid #10b981; color: #065f46; }
            .flash-toast--error   { background: #fef2f2; border-left: 4px solid #dc2626; color: #991b1b; }
            .flash-toast--status  { background: #eff6ff; border-left: 4px solid #3b82f6; color: #1e40af; }
            .flash-toast i { font-size: 1.1rem; flex-shrink: 0; }
            .flash-toast span { flex: 1; line-height: 1.4; }
            .flash-toast__close {
                background: none; border: none; font-size: 1.2rem; line-height: 1;
                cursor: pointer; opacity: 0.6; color: inherit; flex-shrink: 0;
            }
            .flash-toast__close:hover { opacity: 1; }
            @keyframes flashToastIn {
                from { transform: translateX(30px); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @media (max-width: 480px) {
                .flash-toast { right: 12px; left: 12px; max-width: none; top: 80px; }
            }
        </style>
        <script>
            setTimeout(function () {
                var toast = document.getElementById('flashToast');
                if (toast) toast.remove();
            }, 6000);
        </script>
    @endif

    <!-- ==================== MAIN CONTENT ==================== -->
    @yield('content')


    <!-- ==================== FOOTER ==================== -->

    @include('website.layout.footer')
    <!-- ==================== JAVASCRIPT ==================== -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" defer></script>
    
    <!-- Global asset base URL for JS components (e.g., product-modal.js) -->
    <script>
        window.assetBaseUrl = "{{ asset('website') }}";
    </script>
    
    @stack('scripts')

    <!-- Google AdSense (auto ads) — moved here from <head> so it never competes
         with critical CSS/fonts/LCP image for early network priority. Still
         async, so it never blocks rendering either way. -->
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2529569703326249"
     crossorigin="anonymous"></script>

    <!--Start of Tawk.to Script-->
    <!-- Chat widget is not needed for first paint/LCP and its JS is heavy
         enough to add measurable main-thread work — load it once the browser
         is idle (or after the user actually interacts), not immediately on
         page load, to keep TBT/long-tasks down during initial render. -->
    <script type="text/javascript">
    (function(){
        var loaded = false;
        function loadTawk() {
            if (loaded) return;
            loaded = true;
            var Tawk_API = window.Tawk_API = window.Tawk_API || {};
            Tawk_API.Tawk_LoadStart = new Date();
            var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
            s1.async = true;
            s1.src = 'https://embed.tawk.to/6a29afa35bdfa41c2ccf5e2a/1jqpdc6jv';
            s1.charset = 'UTF-8';
            s1.setAttribute('crossorigin', '*');
            s0.parentNode.insertBefore(s1, s0);
            ['scroll', 'mousemove', 'touchstart', 'keydown'].forEach(function (evt) {
                window.removeEventListener(evt, loadTawk, { passive: true });
            });
        }
        ['scroll', 'mousemove', 'touchstart', 'keydown'].forEach(function (evt) {
            window.addEventListener(evt, loadTawk, { passive: true, once: true });
        });
        if ('requestIdleCallback' in window) {
            requestIdleCallback(loadTawk, { timeout: 5000 });
        } else {
            setTimeout(loadTawk, 4000);
        }
    })();
    </script>
    <!--End of Tawk.to Script-->
</body>

</html>