@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/mcqs.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@endpush

@push('meta')
@php
    $canonicalUrl = route('website.mcqs.index');
    $metaTitle = __('mcqs.meta.title');
    $metaDescription = __('mcqs.meta.description');

    $itemList = $testTypes->take(12)->values()->map(function ($testType, $index) {
        return [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'name' => $testType->name,
            'url' => route('website.mcqs.test-type', $testType->slug),
        ];
    })->toArray();

    $collectionSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'CollectionPage',
        'name' => $metaTitle,
        'description' => $metaDescription,
        'url' => $canonicalUrl,
        'mainEntity' => [
            '@type' => 'ItemList',
            'numberOfItems' => count($itemList),
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
                'name' => __('messages.home'),
                'item' => route('website.home'),
            ],
            [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => __('messages.mcqs'),
                'item' => route('website.mcqs.index'),
            ],
        ],
    ];

    $faqSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => [
            [
                '@type' => 'Question',
                'name' => __('mcqs.schema.faq_q1'),
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => __('mcqs.schema.faq_a1'),
                ],
            ],
            [
                '@type' => 'Question',
                'name' => __('mcqs.schema.faq_q2'),
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => __('mcqs.schema.faq_a2'),
                ],
            ],
            [
                '@type' => 'Question',
                'name' => __('mcqs.schema.faq_q3'),
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => __('mcqs.schema.faq_a3'),
                ],
            ],
        ],
    ];
@endphp
<title>{{ $metaTitle }}</title>
<meta name="description" content="{{ $metaDescription }}">
<meta name="keywords" content="{{ __('mcqs.meta.keywords') }}">
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

<script type="application/ld+json">{!! json_encode($collectionSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
<script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
<script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

@section('content')
@include('website.mcqs_system.mcqs.partials.index.hero-stats', ['stats' => $stats])
@include('website.mcqs_system.mcqs.partials.index.top-performers', ['topUsers' => $topUsers])
@include('website.mcqs_system.mcqs.partials.index.test-types', ['testTypes' => $testTypes])
@include('website.mcqs_system.mcqs.partials.index.subjects', ['subjects' => $subjects])
@include('website.mcqs_system.mcqs.partials.index.how-it-works')
@include('website.mcqs_system.mcqs.partials.index.recent-mcqs', ['recentMcqs' => $recentMcqs])
@include('website.mcqs_system.mcqs.partials.index.cta')

@endsection
