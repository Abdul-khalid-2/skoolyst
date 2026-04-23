@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/blog.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/blog-inline.css') }}?v={{ filemtime(public_path('assets/css/blog-inline.css')) }}">
@endpush

@push('meta')
@php
    $currentPage = $posts->currentPage();
    $searchTerm = trim((string) request('search', ''));
    $sort = request('sort', 'latest');
    $queryForCanonical = array_filter([
        'search' => $searchTerm ?: null,
        'sort' => $sort !== 'latest' ? $sort : null,
    ]);
    $canonicalUrl = route('website.blog.index') . (count($queryForCanonical) ? '?' . http_build_query($queryForCanonical) : '');
    if ($currentPage > 1) {
        $canonicalUrl .= (str_contains($canonicalUrl, '?') ? '&' : '?') . 'page=' . $currentPage;
    }

    $baseTitle = 'Education Blog Pakistan | School Insights, Parenting Tips & Trends | Skoolyst';
    if ($searchTerm !== '') {
        $baseTitle = 'Search Results for "' . $searchTerm . '" | Skoolyst Blog';
    } elseif ($sort === 'popular') {
        $baseTitle = 'Most Popular Education Articles | Skoolyst Blog';
    } elseif ($sort === 'featured') {
        $baseTitle = 'Featured Education Articles | Skoolyst Blog';
    }
    $metaTitle = $currentPage > 1 ? ($baseTitle . ' - Page ' . $currentPage) : $baseTitle;

    $metaDescription = $searchTerm !== ''
        ? 'Browse educational blog posts matching "' . $searchTerm . '". Explore expert school advice, parent guides, and learning insights on Skoolyst.'
        : 'Read Skoolyst education blog posts about schools in Pakistan, admissions, curriculum choices, parenting guidance, and modern learning trends.';
    if ($currentPage > 1) {
        $metaDescription .= ' Viewing page ' . $currentPage . '.';
    }

    $itemList = [];
    foreach ($posts as $index => $post) {
        $position = (($posts->currentPage() - 1) * $posts->perPage()) + $index + 1;
        $itemList[] = [
            '@type' => 'ListItem',
            'position' => $position,
            'url' => route('website.blog.show', $post->slug),
            'name' => $post->title,
        ];
    }

    $collectionSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'CollectionPage',
        'name' => $metaTitle,
        'description' => $metaDescription,
        'url' => $canonicalUrl,
        'mainEntity' => [
            '@type' => 'ItemList',
            'numberOfItems' => $posts->total(),
            'itemListElement' => $itemList,
        ],
    ];

    $blogSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Blog',
        'name' => 'Skoolyst Blog',
        'description' => 'Educational insights, school guidance, and parent resources from Skoolyst.',
        'url' => route('website.blog.index'),
        'publisher' => [
            '@type' => 'Organization',
            'name' => 'SKOOLYST Pakistan',
            'url' => url('/'),
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
                'name' => 'Blog',
                'item' => route('website.blog.index'),
            ],
        ],
    ];
@endphp
<title>{{ $metaTitle }}</title>
<meta name="description" content="{{ $metaDescription }}">
<meta name="keywords" content="education blog Pakistan, school admissions, parent tips, curriculum guides, school comparison, Skoolyst blog">
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

@if ($posts->previousPageUrl())
<link rel="prev" href="{{ $posts->previousPageUrl() }}">
@endif
@if ($posts->nextPageUrl())
<link rel="next" href="{{ $posts->nextPageUrl() }}">
@endif

<script type="application/ld+json">{!! json_encode($blogSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
<script type="application/ld+json">{!! json_encode($collectionSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
<script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

@section('content')

@include('website.blog.partials.index.hero-search')

<!-- ==================== BLOG CONTENT SECTION ==================== -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                @include('website.blog.partials.index.toolbar', ['posts' => $posts])
                @include('website.blog.partials.index.posts-grid', ['posts' => $posts])
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                @include('website.blog.partials.sidebar', [
                    'categories' => $categories,
                    'popularPosts' => $popularPosts,
                    'tags' => $tags
                ])
            </div>
        </div>
    </div>
</section>

@endsection