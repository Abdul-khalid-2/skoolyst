@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/home-inline.css') }}?v={{ filemtime(public_path('assets/css/home-inline.css')) }}">
@endpush
@push('meta')
    <title>Find & Compare the Best Schools in Pakistan | SKOOLYST</title>
    <meta name="description" content="Discover, explore, and compare schools in Pakistan with SKOOLYST. Search by country, city, curriculum, and type to find the perfect school for your child anywhere in the world.">
    <meta name="keywords" content="best schools in Pakistan, international schools, global school search, O Level schools, Montessori schools, A Level schools, IB schools, school admissions, compare schools">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph for social sharing -->
    <meta property="og:title" content="Find & Compare the Best Schools in Pakistan | SKOOLYST">
    <meta property="og:description" content="Discover and compare schools around the world by location, curriculum, and type with SKOOLYST.">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ asset('assets/assets/hero1.png') }}">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Find & Compare the Best Schools in Pakistan | SKOOLYST">
    <meta name="twitter:description" content="Discover and compare schools around the world by location, curriculum, and type with SKOOLYST.">
    <meta name="twitter:image" content="{{ asset('assets/assets/hero1.png') }}">

    <!-- Schema.org JSON-LD -->
    <script type="application/ld+json">
    @php
        $jsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => 'Find Best Schools in Pakistan',
            'description' => 'Search and compare top schools in Pakistan by location, curriculum, and reviews.',
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'SKOOLYST',
                'url' => url('/'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('assets/assets/hero.png')
                ],
            ],
            'mainEntity' => [
                '@type' => 'ItemList',
                'itemListElement' => []
            ],
        ];
        
        // Add schools to the itemListElement if they exist
        if(isset($schools) && count($schools) > 0) {
            foreach ($schools as $index => $school) {
                $jsonLd['mainEntity']['itemListElement'][] = [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'url' => route('browseSchools.show', $school['id'] ?? $school->id),
                    'name' => $school['name'] ?? $school->name,
                    'image' => isset($school['banner_image']) ? asset('website/'.$school['banner_image']) : (isset($school->banner_image) ? asset('website/'.$school->banner_image) : asset('assets/images/default-school.jpg')),
                    'address' => $school['location'] ?? ($school->city ?? 'Location not specified'),
                    'aggregateRating' => [
                        '@type' => 'AggregateRating',
                        'ratingValue' => number_format($school['rating'] ?? ($school->reviews->avg('rating') ?? 0), 1),
                        'reviewCount' => $school['review_count'] ?? ($school->reviews->count() ?? 0),
                    ],
                ];
            }
        }
    @endphp
    {!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

@section('content')
@include('website.home.partials.hero-search')
@include('website.home.partials.filters', ['cities' => $cities, 'schoolTypes' => $schoolTypes, 'curriculums' => $curriculums])
@include('website.home.partials.school-directory', ['schools' => $schools])
@include('website.home.partials.testimonials', ['testimonials' => $testimonials])
@include('website.home.partials.cta')

@push('testemonial')
@include('website.home.partials.testimonial-form')

@endpush

@push('scripts')
<script src="{{ asset('assets/js/home.js') }}"></script>
<script src="{{ asset('assets/js/home-testimonial-form.js') }}?v={{ filemtime(public_path('assets/js/home-testimonial-form.js')) }}"></script>
@endpush

@endsection