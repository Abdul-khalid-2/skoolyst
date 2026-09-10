@extends('website.layout.app')

@push('meta')
<title>Page removed (410) | SKOOLYST</title>
<meta name="description" content="This page has been permanently removed.">
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
            <h1 class="display-1 fw-bold text-primary">410</h1>
            <h2 class="h4 text-muted mb-3">This page has been permanently removed</h2>
            <p class="text-muted mb-4">
                The content you're looking for is no longer available. Try the links below or return to the homepage.
            </p>
            <div class="d-flex flex-wrap gap-2 justify-content-center">
                <a class="btn btn-primary" href="{{ route('website.home') }}">Back to home</a>
                <a class="btn btn-outline-primary" href="{{ route('browseSchools.index') }}">Browse schools</a>
                <a class="btn btn-outline-primary" href="{{ route('website.videos.index') }}">EduVideos</a>
            </div>
        </div>
    </div>
</div>
@endsection
