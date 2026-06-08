@php
    $location = $location ?? request('location');
    $search = $search ?? request('search');
    $type = $type ?? request('type');
    $ownership = $ownership ?? request('ownership');
    $curriculumCode = $curriculum ?? request('curriculum');

    $curriculumName = null;
    if ($curriculumCode && isset($curriculums)) {
        $match = $curriculums->firstWhere('code', $curriculumCode);
        $curriculumName = $match?->name ?? strtoupper($curriculumCode);
    }

    $typeLabel = $schoolGenderTypes[$type] ?? null;
    $ownershipLabel = $schoolOwnershipTypes[$ownership] ?? null;

    $currentPage = $schools->currentPage();
    $totalSchools = $schools->total();
    $canonicalUrl = request()->fullUrlWithoutQuery(['page']);
    if ($currentPage > 1) {
        $canonicalUrl .= (str_contains($canonicalUrl, '?') ? '&' : '?') . 'page=' . $currentPage;
    }

    // Dynamic, intent-matched title & description (city / curriculum / type aware).
    $titleParts = [];
    if ($typeLabel) {
        $titleParts[] = $typeLabel . ' Schools';
    }
    if ($curriculumName) {
        $titleParts[] = $curriculumName . ' Schools';
    }
    if ($ownershipLabel) {
        $titleParts[] = $ownershipLabel . ' Schools';
    }
    if ($location) {
        $titleParts[] = 'in ' . $location;
    } elseif ($search) {
        $titleParts[] = 'matching "' . $search . '"';
    }

    if (empty($titleParts)) {
        $metaTitle = 'Find Best Schools in Pakistan Near You | Compare Schools by City | SKOOLYST';
        $metaDescription = 'SKOOLYST is Pakistan\'s school discovery platform. Find and compare the best schools near you in Karachi, Lahore, Islamabad, Rawalpindi, Hyderabad and more. Filter by Cambridge, Federal Board, Montessori, O/A Levels, fees, reviews and admissions.';
        $h1 = 'Find the Best Schools in Pakistan';
        $intro = 'Browse ' . number_format($totalSchools) . '+ verified school profiles across Pakistan. Compare curriculum, location, reviews and facilities — then shortlist the right school for your child.';
    } else {
        $heading = implode(' ', $titleParts);
        $metaTitle = $heading . ' | Compare & Reviews | SKOOLYST Pakistan';
        $metaDescription = 'Discover ' . number_format($totalSchools) . '+ ' . strtolower($heading) . ' on SKOOLYST. Compare ratings, curriculum, facilities and parent reviews. Find the best school near you and explore admission details.';
        $h1 = $heading;
        $intro = 'Showing ' . number_format($totalSchools) . ' schools' . ($location ? ' in ' . $location : '') . '. Use filters to narrow by curriculum, school type and ownership.';
    }

    if ($currentPage > 1) {
        $metaTitle .= ' - Page ' . $currentPage;
        $metaDescription .= ' Page ' . $currentPage . ' of results.';
    }

    $metaKeywords = collect([
        'schools in Pakistan',
        'best schools near me',
        'find schools by city',
        'compare schools Pakistan',
        'school directory Pakistan',
        'school admission Pakistan',
        $location ? 'schools in ' . $location : null,
        $location ? 'best schools in ' . $location : null,
        $curriculumName ? strtolower($curriculumName) . ' schools Pakistan' : null,
        'Cambridge schools Pakistan',
        'O Level schools',
        'Montessori schools',
        'SKOOLYST',
    ])->filter()->unique()->implode(', ');

    $ogImage = asset('assets/assets/hero1.png');
    $siteUrl = url('/');

    // ItemList for CollectionPage
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

    // EducationalOrganization entries for rich results / AI entity understanding
    $schoolEntities = [];
    foreach ($schools->take(12) as $school) {
        $entity = [
            '@type' => 'EducationalOrganization',
            '@id' => route('browseSchools.show', $school['uuid']) . '#school',
            'name' => $school['name'],
            'url' => route('browseSchools.show', $school['uuid']),
            'description' => Str::limit(strip_tags($school['description'] ?? ''), 200),
        ];
        if (! empty($school['location'])) {
            $entity['address'] = [
                '@type' => 'PostalAddress',
                'addressLocality' => $school['location'],
                'addressCountry' => 'PK',
            ];
        }
        if (($school['rating'] ?? 0) > 0) {
            $entity['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => (string) $school['rating'],
                'reviewCount' => (string) ($school['review_count'] ?? 0),
                'bestRating' => '5',
            ];
        }
        $schoolEntities[] = $entity;
    }

    $faqItems = [
        [
            'q' => 'What is SKOOLYST and how does it help parents find schools?',
            'a' => 'SKOOLYST is a school discovery and comparison platform for Pakistan. Parents can search schools by city, curriculum (Cambridge, Federal Board, Montessori, O/A Levels), school type and ownership, read parent reviews, compare profiles, and find the best school near them.',
        ],
        [
            'q' => 'How do I find the best school near me in Pakistan?',
            'a' => 'Visit SKOOLYST\'s school directory, select your city (e.g. Karachi, Lahore, Islamabad), and filter by curriculum or school type. Each school profile includes location, facilities, reviews and contact details so you can compare options near you.',
        ],
        [
            'q' => 'Which cities are covered on SKOOLYST?',
            'a' => 'SKOOLYST lists schools across major Pakistani cities including Karachi, Lahore, Islamabad, Rawalpindi, Hyderabad, Peshawar, Quetta, Faisalabad, Multan and more. Use the city filter on the browse schools page to see schools in your area.',
        ],
        [
            'q' => 'Can I compare schools by curriculum on SKOOLYST?',
            'a' => 'Yes. SKOOLYST lets you filter and compare schools by curriculum such as Cambridge International, Federal Board, Sindh Board, Montessori, O Levels and A Levels, alongside gender type and ownership (private, government, semi-government).',
        ],
        [
            'q' => 'Is SKOOLYST free for parents looking for schools?',
            'a' => 'Yes. Browsing schools, reading profiles, comparing options and using filters on SKOOLYST is free for parents and students across Pakistan.',
        ],
    ];

    $faqSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => collect($faqItems)->map(fn ($item) => [
            '@type' => 'Question',
            'name' => $item['q'],
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => $item['a'],
            ],
        ])->values()->all(),
    ];

    $webPageSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'WebPage',
        '@id' => $canonicalUrl . '#webpage',
        'url' => $canonicalUrl,
        'name' => $metaTitle,
        'description' => $metaDescription,
        'inLanguage' => app()->getLocale() === 'ur' ? 'ur-PK' : 'en-PK',
        'isPartOf' => [
            '@type' => 'WebSite',
            '@id' => $siteUrl . '#website',
            'name' => 'SKOOLYST Pakistan',
            'url' => $siteUrl,
        ],
        'about' => [
            '@type' => 'Thing',
            'name' => 'School discovery in Pakistan',
            'description' => 'Finding and comparing schools by city, curriculum and reviews',
        ],
        'speakable' => [
            '@type' => 'SpeakableSpecification',
            'cssSelector' => ['.seo-intro-text', '.seo-faq-answer'],
        ],
        'mainEntity' => [
            '@type' => 'ItemList',
            'numberOfItems' => $totalSchools,
            'itemListElement' => $itemList,
        ],
    ];

    $collectionSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'CollectionPage',
        '@id' => $canonicalUrl . '#collection',
        'name' => $metaTitle,
        'description' => $metaDescription,
        'url' => $canonicalUrl,
        'isPartOf' => ['@id' => $siteUrl . '#website'],
        'mainEntity' => $webPageSchema['mainEntity'],
    ];

    $organizationSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        '@id' => $siteUrl . '#organization',
        'name' => 'SKOOLYST Pakistan',
        'url' => $siteUrl,
        'logo' => asset('assets/images/logo.png'),
        'description' => 'Pakistan\'s school discovery platform — find, compare and connect with the best schools by city, curriculum and reviews.',
        'areaServed' => [
            '@type' => 'Country',
            'name' => 'Pakistan',
        ],
        'knowsAbout' => [
            'School admissions Pakistan',
            'Cambridge schools',
            'O Level and A Level schools',
            'Montessori education',
            'School comparison by city',
        ],
        'sameAs' => [
            'https://www.facebook.com/skoolystpk',
            'https://www.instagram.com/skoolystpk',
            'https://twitter.com/skoolystpk',
        ],
    ];

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
            $location ? [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => 'Schools in ' . $location,
                'item' => $canonicalUrl,
            ] : null,
        ])),
    ];

    try {
        $hreflangEn = LaravelLocalization::getLocalizedURL('en', null, [], true);
        $hreflangUr = LaravelLocalization::getLocalizedURL('ur', null, [], true);
    } catch (\Throwable $e) {
        $hreflangEn = $hreflangUr = null;
    }

    // Expose for page-header & seo-content partials
    View::share('seoH1', $h1);
    View::share('seoIntro', $intro);
    View::share('seoFaqItems', $faqItems);
@endphp

<title>{{ $metaTitle }}</title>
<meta name="description" content="{{ $metaDescription }}">
<meta name="keywords" content="{{ $metaKeywords }}">
<meta name="robots" content="index,follow,max-snippet:-1,max-image-preview:large,max-video-preview:-1">
<meta name="author" content="SKOOLYST Pakistan">
<meta name="geo.region" content="PK">
<meta name="geo.placename" content="Pakistan">
<link rel="canonical" href="{{ $canonicalUrl }}">

@if($hreflangEn)<link rel="alternate" hreflang="en" href="{{ $hreflangEn }}">@endif
@if($hreflangUr)<link rel="alternate" hreflang="ur" href="{{ $hreflangUr }}">@endif
@if($hreflangEn)<link rel="alternate" hreflang="x-default" href="{{ $hreflangEn }}">@endif

<meta property="og:type" content="website">
<meta property="og:site_name" content="SKOOLYST Pakistan">
<meta property="og:locale" content="{{ app()->getLocale() === 'ur' ? 'ur_PK' : 'en_PK' }}">
<meta property="og:title" content="{{ $metaTitle }}">
<meta property="og:description" content="{{ $metaDescription }}">
<meta property="og:url" content="{{ $canonicalUrl }}">
<meta property="og:image" content="{{ $ogImage }}">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@skoolystpk">
<meta name="twitter:title" content="{{ $metaTitle }}">
<meta name="twitter:description" content="{{ $metaDescription }}">
<meta name="twitter:image" content="{{ $ogImage }}">

@if ($schools->previousPageUrl())
<link rel="prev" href="{{ $schools->previousPageUrl() }}">
@endif
@if ($schools->nextPageUrl())
<link rel="next" href="{{ $schools->nextPageUrl() }}">
@endif

<script type="application/ld+json">{!! json_encode($organizationSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
<script type="application/ld+json">{!! json_encode($webPageSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
<script type="application/ld+json">{!! json_encode($collectionSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
<script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
<script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@if (! empty($schoolEntities))
<script type="application/ld+json">{!! json_encode(['@context' => 'https://schema.org', '@graph' => $schoolEntities], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endif
