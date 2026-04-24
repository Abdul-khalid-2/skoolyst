@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@endpush

@php
    $avatarUrl = $user->profile_picture_url
        ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=256&background=15a362&color=fff';
@endphp

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <h1 class="h3 mb-4">My profile</h1>

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-body p-4 text-center">
                    <div class="mb-3">
                        <img
                            src="{{ $avatarUrl }}"
                            alt="{{ $user->name }}"
                            class="rounded-circle border"
                            style="width: 120px; height: 120px; object-fit: cover;">
                    </div>
                    <h2 class="h5 card-title mb-1">{{ $user->name }}</h2>
                    <p class="text-muted mb-4">{{ $user->email }}</p>
                    <a href="{{ LaravelLocalization::localizeUrl(route('user_profile.edit', [], false)) }}" class="btn btn-primary">
                        Edit profile
                    </a>
                </div>
            </div>

            <p class="text-muted small mt-4 mb-0">
                Reviews, comments, and MCQ activity will appear here in a future update.
            </p>
        </div>
    </div>
</div>
@endsection
