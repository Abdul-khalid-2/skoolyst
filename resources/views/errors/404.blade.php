@extends('website.layout.app')

@push('meta')
<title>Page not found (404) | SKOOLYST</title>
<meta name="description" content="The page you are looking for may have been moved or removed.">
<meta name="robots" content="noindex, nofollow">
@endpush

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@endpush

@section('content')
<div class="container py-5 my-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 text-center">
            <h1 class="display-1 fw-bold text-primary">404</h1>
            <h2 class="h4 text-muted mb-3">We couldn&rsquo;t find that page</h2>
            <p class="text-muted mb-4">
                The link may be broken, the page may have been removed, or the address was mistyped. Try the links below or return to the homepage.
            </p>
            <div class="d-flex flex-wrap gap-2 justify-content-center">
                <a class="btn btn-primary" href="{{ route('website.home') }}">Back to home</a>
                <a class="btn btn-outline-primary" href="{{ route('browseSchools.index') }}">Browse schools</a>
                <a class="btn btn-outline-primary" href="{{ route('website.videos.index') }}">EduVideos</a>
                <a class="btn btn-outline-primary" href="{{ route('website.blog.index') }}">Blog</a>
            </div>
        </div>
    </div>
</div>
@endsection
