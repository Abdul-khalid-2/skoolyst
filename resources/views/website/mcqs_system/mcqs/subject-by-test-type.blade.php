@extends('website.layout.app')

@php $pageSetsOwnCanonical = true; @endphp
@push('meta')
@php
    $metaTitle = str_replace(
        [':subject', ':test_type'],
        [$subject->name, $testType->name],
        __('mcqs.meta.subject_by_test_title')
    );
    $metaDescription = str_replace(
        [':subject', ':test_type'],
        [$subject->name, $testType->name],
        __('mcqs.meta.subject_by_test_description')
    );
    $metaKeywords = str_replace(
        [':subject', ':test_type'],
        [$subject->name, $testType->name],
        __('mcqs.meta.subject_by_test_keywords')
    );
    $canonicalUrl = route('website.mcqs.subject-by-test-type', [
        'test_type' => $testType->slug,
        'subject'   => $subject->slug,
    ]);
    $ogImage = asset('assets/assets/hero1.png');

    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => __('messages.home'), 'item' => route('website.home')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => __('messages.mcqs'), 'item' => route('website.mcqs.index')],
            ['@type' => 'ListItem', 'position' => 3, 'name' => $testType->name,     'item' => route('website.mcqs.test-type', $testType->slug)],
            ['@type' => 'ListItem', 'position' => 4, 'name' => $subject->name,      'item' => $canonicalUrl],
        ],
    ];
@endphp
<title>{{ $metaTitle }}</title>
<meta name="description" content="{{ $metaDescription }}">
<meta name="keywords"    content="{{ $metaKeywords }}">
<meta name="robots"      content="index,follow,max-snippet:-1,max-image-preview:large,max-video-preview:-1">
<link rel="canonical"    href="{{ $canonicalUrl }}">

<meta property="og:type"        content="website">
<meta property="og:title"       content="{{ $metaTitle }}">
<meta property="og:description" content="{{ $metaDescription }}">
<meta property="og:url"         content="{{ $canonicalUrl }}">
<meta property="og:image"       content="{{ $ogImage }}">
<meta property="og:locale"      content="{{ app()->getLocale() == 'ur' ? 'ur_PK' : 'en_PK' }}">

<meta name="twitter:card"        content="summary_large_image">
<meta name="twitter:title"       content="{{ $metaTitle }}">
<meta name="twitter:description" content="{{ $metaDescription }}">
<meta name="twitter:image"       content="{{ $ogImage }}">

<link rel="alternate" hreflang="en"        href="{{ LaravelLocalization::getLocalizedURL('en', null, [], true) }}">
<link rel="alternate" hreflang="ur"        href="{{ LaravelLocalization::getLocalizedURL('ur', null, [], true) }}">
<link rel="alternate" hreflang="x-default" href="{{ LaravelLocalization::getLocalizedURL('en', null, [], true) }}">

<script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/subject-practice.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@include('website.mcqs_system.mcqs.partials.subject-by-test-type.page-inline-styles')
@endpush

@section('content')

@include('website.mcqs_system.mcqs.partials.subject-by-test-type.hero')

<section class="py-5">
    <div class="container">

        @include('website.mcqs_system.mcqs.partials.subject-by-test-type.filters-section')

        @include('website.mcqs_system.mcqs.partials.subject-by-test-type.breadcrumb')

        <div class="row">
            @include('website.mcqs_system.mcqs.partials.subject-by-test-type.main-column')
            @include('website.mcqs_system.mcqs.partials.subject-by-test-type.sidebar-question-palette')
        </div>
    </div>
</section>
@endsection

@push('scripts')
@include('website.mcqs_system.mcqs.partials.subject-by-test-type.scripts')
@endpush
