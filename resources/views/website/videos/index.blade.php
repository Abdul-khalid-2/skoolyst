@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/videos.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/videos-inline.css') }}?v={{ filemtime(public_path('assets/css/videos-inline.css')) }}">
@endpush

@push('meta')
@php
    $currentPage = $videos->currentPage();
    $searchTerm = trim((string) request('search', ''));
    $filter = request('filter', 'all');

    $queryForCanonical = array_filter([
        'search' => $searchTerm !== '' ? $searchTerm : null,
        'category' => (request('category') && request('category') != 'all') ? request('category') : null,
        'school' => (request('school') && request('school') != 'all') ? request('school') : null,
        'shop' => (request('shop') && request('shop') != 'all') ? request('shop') : null,
        'filter' => ($filter && $filter != 'all') ? $filter : null,
    ]);
    $canonicalUrl = route('website.videos.index') . (count($queryForCanonical) ? '?' . http_build_query($queryForCanonical) : '');
    if ($currentPage > 1) {
        $canonicalUrl .= (str_contains($canonicalUrl, '?') ? '&' : '?') . 'page=' . $currentPage;
    }

    $selectedCategory = (request('category') && request('category') != 'all')
        ? $categories->firstWhere('id', (int) request('category'))
        : null;

    $baseTitle = 'Educational Videos Pakistan | School & Learning Videos | SKOOLYST EduVideos';
    if ($searchTerm !== '') {
        $baseTitle = 'Search: "'.Str::limit($searchTerm, 50).'" | EduVideos | Skoolyst';
    } elseif ($selectedCategory) {
        $baseTitle = $selectedCategory->name.' | Educational Videos | Skoolyst';
    } elseif ($filter === 'featured') {
        $baseTitle = 'Featured Educational Videos | Skoolyst EduVideos';
    } elseif ($filter === 'popular') {
        $baseTitle = 'Most Popular Educational Videos | Skoolyst';
    } elseif ($filter === 'recent') {
        $baseTitle = 'Recent Educational Videos | Skoolyst';
    }
    $metaTitle = $currentPage > 1 ? ($baseTitle.' - Page '.$currentPage) : $baseTitle;

    $metaDescription = 'Watch educational videos on Skoolyst: school stories, learning tips, and content from schools and education partners in Pakistan.';
    if ($searchTerm !== '') {
        $metaDescription = 'Video search results for "'.$searchTerm.'" on Skoolyst. Discover school and learning videos matched to your query.';
    } elseif ($selectedCategory) {
        $metaDescription = 'Browse '.$selectedCategory->name.' videos on Skoolyst. Educational content for students and parents in Pakistan.';
    } elseif ($filter === 'featured') {
        $metaDescription = 'Hand-picked featured educational videos on Skoolyst — school highlights, expert tips, and inspiring learning content.';
    } elseif ($filter === 'popular') {
        $metaDescription = 'Most-watched educational videos on Skoolyst, ranked by views. Discover what parents and students are watching.';
    } elseif ($filter === 'recent') {
        $metaDescription = 'Recently published educational videos on Skoolyst. Stay up to date with the latest school and learning content.';
    }
    if ($currentPage > 1) {
        $metaDescription .= ' Page '.$currentPage.' of the video library.';
    }

    $itemList = [];
    foreach ($videos as $index => $video) {
        $position = (($videos->currentPage() - 1) * $videos->perPage()) + $index + 1;
        $itemList[] = [
            '@type' => 'ListItem',
            'position' => $position,
            'url' => route('website.videos.show', $video->slug),
            'name' => $video->title,
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
            'numberOfItems' => $videos->total(),
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
                'name' => 'EduVideos',
                'item' => route('website.videos.index'),
            ],
        ],
    ];
@endphp
<title>{{ $metaTitle }}</title>
<meta name="description" content="{{ $metaDescription }}">
<meta name="keywords" content="educational videos Pakistan, school videos, learning videos, parent tips video, Skoolyst EduVideos, O Level, curriculum">
<meta name="robots" content="index,follow,max-snippet:-1,max-image-preview:large,max-video-preview:-1">
<link rel="canonical" href="{{ $canonicalUrl }}">

<meta property="og:type" content="website">
<meta property="og:title" content="{{ $metaTitle }}">
<meta property="og:description" content="{{ $metaDescription }}">
<meta property="og:url" content="{{ $canonicalUrl }}">
<meta property="og:image" content="{{ asset('assets/assets/hero1.png') }}">
<meta property="og:site_name" content="Skoolyst">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $metaTitle }}">
<meta name="twitter:description" content="{{ $metaDescription }}">
<meta name="twitter:image" content="{{ asset('assets/assets/hero1.png') }}">

@if ($videos->previousPageUrl())
<link rel="prev" href="{{ $videos->previousPageUrl() }}">
@endif
@if ($videos->nextPageUrl())
<link rel="next" href="{{ $videos->nextPageUrl() }}">
@endif

<script type="application/ld+json">{!! json_encode($collectionSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
<script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

@section('content')

@include('website.videos.partials.index.hero-search')

<!-- ==================== VIDEOS CONTENT SECTION ==================== -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                @include('website.videos.partials.index.filters', [
                    'categories' => $categories,
                    'schools' => $schools,
                    'shops' => $shops,
                ])
                @include('website.videos.partials.index.video-grid', ['videos' => $videos])
            </div>
            <div class="col-lg-4">
                @include('website.videos.partials.index.sidebar', [
                    'categories' => $categories,
                    'featuredVideos' => $featuredVideos,
                    'popularVideos' => $popularVideos,
                    'schools' => $schools,
                ])
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script src="{{ asset('assets/js/videos-index.js') }}?v={{ filemtime(public_path('assets/js/videos-index.js')) }}"></script>
@endpush
