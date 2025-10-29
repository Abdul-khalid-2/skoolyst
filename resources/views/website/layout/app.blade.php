<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <meta property="og:image" content="{{ asset('assets/assets/hero.png') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:site" content="@skoolystpk">
    <meta property="twitter:title" content="Find Best Schools in Pakistan - SKOOLYST">
    <meta property="twitter:description" content="Explore and compare the best schools across Pakistan. Find by city, curriculum, or fee structure.">
    <meta property="twitter:image" content="{{ asset('assets/assets/hero.png') }}">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">

    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => 'SKOOLYST Pakistan',
            'url' => url('/'),
            'description' => "Pakistan's leading school discovery platform. Find, compare, and connect with the best schools in Karachi, Lahore, Islamabad,  Peshawer, and more.",
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => url('/browse-schools') . '?search={search_term_string}',
                'query-input' => 'required name=search_term_string'
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

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @stack('styles')
</head>
@stack('meta')

<body>

    <!-- ==================== NAVIGATION BAR ==================== -->

    @include('website.layout.header')

    <!-- ==================== MAIN CONTENT ==================== -->
    @yield('content')


    <!-- ==================== FOOTER ==================== -->

    @include('website.layout.footer')
    <!-- ==================== JAVASCRIPT ==================== -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')

</body>

</html>