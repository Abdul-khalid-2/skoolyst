@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/mcqs.css') }}?v={{ filemtime(public_path('assets/css/mcqs.css')) }}">
<link rel="stylesheet" href="{{ asset('assets/css/test-results.css') }}?v={{ filemtime(public_path('assets/css/test-results.css')) }}">
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@endpush

@section('content')
<div class="subject-test-results">
    @include('website.mcqs_system.mcqs.partials.test-subject-results.hero', [
        'subject' => $subject,
        'topic' => $topic,
        'testType' => $testType,
    ])

    <div class="container py-3 py-md-4">
        <div class="row g-4 align-items-start">
            <div class="col-lg-8 order-2 order-lg-1">
                <div class="breadcrumb-wrapper mb-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('website.mcqs.index') }}">MCQs</a></li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('website.mcqs.test-type', $testType->slug) }}">{{ $testType->name }}</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('website.mcqs.subject-by-test-type', [$testType->slug, $subject->slug]) }}">{{ $subject->name }}</a>
                            </li>
                            @if($topic)
                                <li class="breadcrumb-item">
                                    <a href="{{ route('website.mcqs.topic', ['subject' => $subject->slug, 'topic' => $topic->slug]) }}">{{ $topic->title }}</a>
                                </li>
                            @endif
                            <li class="breadcrumb-item active" aria-current="page">Results</li>
                        </ol>
                    </nav>
                </div>

                <div class="review-card">
                    <div class="review-header">
                        <h5><i class="fas fa-clipboard-list me-2"></i>Question review</h5>
                    </div>
                    <div class="review-body">
                        @include('website.mcqs_system.mcqs.partials.test-subject-results.review-questions')
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-md-start mt-4">
                    <a href="{{ route('website.mcqs.subject-by-test-type', [$testType->slug, $subject->slug]) }}" class="btn btn-primary px-4">
                        <i class="fas fa-redo me-2"></i>Practice again
                    </a>
                    <a href="{{ route('website.mcqs.test-type', $testType->slug) }}" class="btn btn-outline-secondary px-4">
                        <i class="fas fa-th-large me-2"></i>{{ $testType->name }}
                    </a>
                </div>
            </div>

            <div class="col-lg-4 order-1 order-lg-2">
                @include('website.mcqs_system.mcqs.partials.test-subject-results.sidebar-analytics', [
                    'testResults' => $testResults,
                    'testType' => $testType,
                ])
            </div>
        </div>
    </div>
</div>
@endsection
