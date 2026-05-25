@extends('website.layout.app')

@php $pageSetsOwnCanonical = true; @endphp
@push('meta')
@php
    $topicName     = $topic->name;
    $subjectName   = $subject->name;
    $questionCount = method_exists($mcqs, 'total') ? $mcqs->total() : $mcqs->count();

    $metaTitle = str_replace(
        [':topic', ':subject'],
        [$topicName, $subjectName],
        __('mcqs.meta.topic_title')
    );
    $metaDescription = str_replace(
        [':topic', ':subject', ':count'],
        [$topicName, $subjectName, $questionCount],
        __('mcqs.meta.topic_description')
    );
    $metaKeywords = str_replace(
        [':topic', ':subject'],
        [$topicName, $subjectName],
        __('mcqs.meta.topic_keywords')
    );
    $canonicalUrl = route('website.mcqs.topic', [
        'subject' => $subject->slug,
        'topic'   => $topic->slug,
    ]);
    $ogImage = asset('assets/assets/hero1.png');

    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => __('messages.home'), 'item' => route('website.home')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => __('messages.mcqs'), 'item' => route('website.mcqs.index')],
            ['@type' => 'ListItem', 'position' => 3, 'name' => $subjectName,        'item' => route('website.mcqs.subject', $subject->slug)],
            ['@type' => 'ListItem', 'position' => 4, 'name' => $topicName,          'item' => $canonicalUrl],
        ],
    ];

    // Build hasPart with up to 5 questions, defensive against shape variations
    $hasPart = [];
    foreach ($mcqs->take(5) as $i => $mcq) {
        $options = is_string($mcq->options) ? json_decode($mcq->options, true) : ($mcq->options ?? []);
        $correctIdx = is_array($mcq->correct_answers) ? ($mcq->correct_answers[0] ?? null) : $mcq->correct_answers;
        $answerText = $correctIdx !== null && isset($options[$correctIdx]) ? $options[$correctIdx] : '';
        $hasPart[] = [
            '@type' => 'Question',
            'position' => $i + 1,
            'name' => \Illuminate\Support\Str::limit(strip_tags((string) $mcq->question), 200),
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => \Illuminate\Support\Str::limit(strip_tags((string) $answerText), 200),
            ],
        ];
    }

    $quizSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Quiz',
        'name' => $topicName . ' MCQs — ' . $subjectName,
        'description' => $metaDescription,
        'url' => $canonicalUrl,
        'educationalAlignment' => [
            '@type' => 'AlignmentObject',
            'alignmentType' => 'educationalSubject',
            'targetName' => $subjectName,
        ],
        'provider' => ['@type' => 'Organization', 'name' => 'Skoolyst', 'url' => route('website.home')],
        'numberOfQuestions' => (int) $questionCount,
        'educationalLevel' => 'All Levels',
        'inLanguage' => app()->getLocale(),
        'isAccessibleForFree' => true,
        'hasPart' => $hasPart,
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
<script type="application/ld+json">{!! json_encode($quizSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
@include('website.mcqs_system.mcqs.partials.topic.styles-links')
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@endpush

@section('content')

@include('website.mcqs_system.mcqs.partials.topic.hero')
@include('website.mcqs_system.mcqs.partials.topic.section-practice')
@endsection

@push('scripts')
@include('website.mcqs_system.mcqs.partials.topic.scripts')
@endpush
