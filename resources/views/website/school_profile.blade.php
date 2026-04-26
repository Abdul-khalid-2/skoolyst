@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/school-profile.css') }}">
@if($school->custom_style)
<link rel="stylesheet" href="{{ asset('assets/css/schools/' . $school->custom_style . '.css') }}">
@endif
@endpush

@php
    $locName = $school->localized('name');
    $locBannerTitle = $school->localized('banner_title');
    $locTagline = $school->localized('banner_tagline');
    $hasHero = ($locBannerTitle !== '' && $locBannerTitle !== null) || ($locTagline !== '' && $locTagline !== null) || $school->banner_image;
@endphp

@section('content')
    @include('website.school_profile.partials.hero-header')
    @include('website.school_profile.partials.navigation')

    <main class="school-main-content school-profile-page">
        <div class="container school-profile-container">
            <div class="content-grid">
                @include('website.school_profile.partials.main-column')
                @include('website.school_profile.partials.sidebar')
            </div>
        </div>
    </main>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/school-profile.js') }}"></script>
<script src="{{ asset('assets/js/contact-form.js') }}"></script>
@endpush
