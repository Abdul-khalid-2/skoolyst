@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/school-profile.css') }}">
@if($school->custom_style)
<link rel="stylesheet" href="{{ asset('assets/css/schools/' . $school->custom_style . '.css') }}">
@endif
@endpush

@php
    $locName = $school->localized('name');
    $locBannerTitle = $school->localized('banner_title');
    $locTagline = $school->localized('banner_tagline');
    $hasHero = ($locBannerTitle !== '' && $locBannerTitle !== null) || ($locTagline !== '' && $locTagline !== null) || $school->banner_image;

    $pageSetsOwnMeta = true;
    $pageSetsOwnCanonical = true;

    $locDescription = $school->localized('description') ?: "Find fees, admissions, curriculum, and verified reviews for {$locName}" . ($school->city ? " in {$school->city}" : '') . " on SKOOLYST Pakistan.";
    $metaTitle = $locName . ($school->city ? " - {$school->city}" : '') . ' | Fees, Reviews & Admissions | SKOOLYST';
    $metaDescription = \Illuminate\Support\Str::limit(strip_tags($locDescription), 155);
    $ogImage = $school->banner_image ? asset('website/' . $school->banner_image) : asset('assets/assets/hero.png');

    $averageRating = round($school->reviews->avg('rating') ?? 0, 1);
    $reviewCount = $school->reviews->count();
    $hasRealRating = $averageRating > 0 && $reviewCount > 0;

    $schoolSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'School',
        'name' => $locName,
        'description' => strip_tags($locDescription),
        'url' => url()->current(),
        'image' => $ogImage,
    ];

    if ($school->address || $school->city) {
        $schoolSchema['address'] = array_filter([
            '@type' => 'PostalAddress',
            'streetAddress' => $school->address ?: null,
            'addressLocality' => $school->city ?: null,
            'addressCountry' => 'PK',
        ]);
    }

    if ($school->contact_number) {
        $schoolSchema['telephone'] = $school->contact_number;
    }

    if ($school->website) {
        $schoolSchema['sameAs'] = [$school->website];
    }

    if ($hasRealRating) {
        $schoolSchema['aggregateRating'] = [
            '@type' => 'AggregateRating',
            'ratingValue' => $averageRating,
            'reviewCount' => $reviewCount,
        ];
    }

    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => array_values(array_filter([
            [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => 'Home',
                'item' => route('website.home'),
            ],
            [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => 'All Schools',
                'item' => route('browseSchools.index'),
            ],
            $school->city ? [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => 'Schools in ' . $school->city,
                'item' => route('browseSchools.index', ['location' => $school->city]),
            ] : null,
            [
                '@type' => 'ListItem',
                'position' => $school->city ? 4 : 3,
                'name' => $locName,
                'item' => url()->current(),
            ],
        ])),
    ];
@endphp

@push('meta')
<title>{{ $metaTitle }}</title>
<meta name="description" content="{{ $metaDescription }}">
<link rel="canonical" href="{{ url()->current() }}">
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

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:site_name" content="SKOOLYST Pakistan">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:title" content="{{ $metaTitle }}">
<meta property="og:description" content="{{ $metaDescription }}">
<meta property="og:image" content="{{ $ogImage }}">

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:site" content="@skoolystpk">
<meta property="twitter:title" content="{{ $metaTitle }}">
<meta property="twitter:description" content="{{ $metaDescription }}">
<meta property="twitter:image" content="{{ $ogImage }}">

<script type="application/ld+json">
{!! json_encode($schoolSchema, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT) !!}
</script>
<script type="application/ld+json">
{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT) !!}
</script>
@endpush

@section('content')
    <nav class="school-profile-breadcrumb" aria-label="Breadcrumb">
        <div class="container">
            <ol class="breadcrumb-list">
                <li><a href="{{ route('website.home') }}">Home</a></li>
                <li><a href="{{ route('browseSchools.index') }}">All Schools</a></li>
                @if($school->city)
                <li><a href="{{ route('browseSchools.index', ['location' => $school->city]) }}">Schools in {{ $school->city }}</a></li>
                @endif
                <li aria-current="page">{{ $locName }}</li>
            </ol>
        </div>
    </nav>

    @include('website.school_profile.partials.hero-header')
    @include('website.school_profile.partials.navigation')

    <main class="school-main-content school-profile-page">
        <div class="container school-profile-container">
            <div class="content-grid">
                @include('website.school_profile.partials.main-column')
                @include('website.school_profile.partials.sidebar')
            </div>
        </div>
    </main>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/school-profile.js') }}"></script>
<script src="{{ asset('assets/js/contact-form.js') }}"></script>
@endpush
