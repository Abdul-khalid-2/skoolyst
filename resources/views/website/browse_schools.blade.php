@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/browse_schools.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/browse_schools-inline.css') }}?v={{ filemtime(public_path('assets/css/browse_schools-inline.css')) }}">
@endpush

@push('meta')
@php
    $activeFilters = array_filter([
        request('search'),
        request('location'),
        request('type'),
        request('curriculum'),
    ]);

    $isFiltered = count($activeFilters) > 0;
    $currentPage = $schools->currentPage();
    $totalSchools = $schools->total();
    $canonicalUrl = request()->fullUrlWithoutQuery(['page']);
    if ($currentPage > 1) {
        $canonicalUrl .= (str_contains($canonicalUrl, '?') ? '&' : '?') . 'page=' . $currentPage;
    }

    $metaTitle = $isFiltered
        ? 'Filtered School Search Results | Skoolyst Pakistan'
        : 'Browse All Schools in Pakistan | Compare Top Schools | Skoolyst';
    if ($currentPage > 1) {
        $metaTitle .= ' - Page ' . $currentPage;
    }

    $metaDescription = $isFiltered
        ? 'Explore filtered school results on Skoolyst. Compare schools by city, curriculum, and type to find the right match for your child.'
        : 'Browse and compare schools in Pakistan by city, curriculum, and school type. Read reviews, explore profiles, and find the best school with Skoolyst.';
    if ($currentPage > 1) {
        $metaDescription .= ' Showing page ' . $currentPage . ' of school listings.';
    }

    $itemList = [];
    foreach ($schools as $index => $school) {
        $position = (($schools->currentPage() - 1) * $schools->perPage()) + $index + 1;
        $itemList[] = [
            '@type' => 'ListItem',
            'position' => $position,
            'url' => route('browseSchools.show', $school['uuid']),
            'name' => $school['name'],
        ];
    }

    $collectionSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'CollectionPage',
        'name' => $metaTitle,
        'description' => $metaDescription,
        'url' => $canonicalUrl,
        'isPartOf' => [
            '@type' => 'WebSite',
            'name' => 'SKOOLYST Pakistan',
            'url' => url('/'),
        ],
        'mainEntity' => [
            '@type' => 'ItemList',
            'numberOfItems' => $totalSchools,
            'itemListElement' => $itemList,
        ],
    ];

    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
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
        ],
    ];
@endphp
<title>{{ $metaTitle }}</title>
<meta name="description" content="{{ $metaDescription }}">
<meta name="keywords" content="schools in Pakistan, compare schools, school directory, best schools, school admissions, Skoolyst schools">
<meta name="robots" content="index,follow,max-snippet:-1,max-image-preview:large,max-video-preview:-1">
<link rel="canonical" href="{{ $canonicalUrl }}">

<meta property="og:type" content="website">
<meta property="og:title" content="{{ $metaTitle }}">
<meta property="og:description" content="{{ $metaDescription }}">
<meta property="og:url" content="{{ $canonicalUrl }}">
<meta property="og:image" content="{{ asset('assets/assets/hero1.png') }}">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $metaTitle }}">
<meta name="twitter:description" content="{{ $metaDescription }}">
<meta name="twitter:image" content="{{ asset('assets/assets/hero1.png') }}">

@if ($schools->previousPageUrl())
<link rel="prev" href="{{ $schools->previousPageUrl() }}">
@endif
@if ($schools->nextPageUrl())
<link rel="next" href="{{ $schools->nextPageUrl() }}">
@endif

<script type="application/ld+json">{!! json_encode($collectionSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
<script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

@section('content')
@include('website.browse-schools.partials.page-header', ['schools' => $schools])
@include('website.browse-schools.partials.filters', ['cities' => $cities, 'schoolTypes' => $schoolTypes, 'curriculums' => $curriculums])
@include('website.browse-schools.partials.school-grid', ['schools' => $schools])
@include('website.browse-schools.partials.pagination', ['schools' => $schools])

@push('scripts')
<script src="{{ asset('assets/js/browse-schools-filters.js') }}?v={{ filemtime(public_path('assets/js/browse-schools-filters.js')) }}"></script>
@endpush

@endsection