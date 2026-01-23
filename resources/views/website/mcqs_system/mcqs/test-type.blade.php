@extends('website.layout.app')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@endpush
@section('content')
<section class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('website.mcqs.index') }}">MCQs</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $testType->name }}</li>
                    </ol>
                </nav>
                <h1 class="h2">{{ $testType->name }} Practice Questions</h1>
                <p class="text-muted">{{ $testType->description }}</p>
            </div>
        </div>

        <div class="row">
            <!-- Subjects List -->
            <div class="col-lg-8">
                <div class="row g-4">
                    @foreach($subjects as $subject)
                    <div class="col-md-6">
                        <div class="subject-card">
                            <div class="subject-icon" style="background: {{ $subject->color_code }}; color: white;">
                                <i class="{{ $subject->icon ?? 'fas fa-book' }}"></i>
                            </div>
                            <h4 class="h5 mb-2">{{ $subject->name }}</h4>
                            <p class="text-muted small">{{ $subject->description }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-light text-dark">{{ $subject->mcqs_count }} Questions</span>
                                <a href="{{ route('website.mcqs.subject.test-type', ['test_type' => $testType->slug, 'subject' => $subject->slug]) }}"
                                    class="btn btn-sm btn-primary">
                                    Start Practice
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Featured Questions -->
                @if($featuredMcqs->count() > 0)
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-star me-2"></i>Featured Questions</h5>
                    </div>
                    <div class="card-body">
                        @foreach($featuredMcqs as $mcq)
                        <div class="mb-3 pb-3 border-bottom">
                            <h6 class="mb-2">
                                <a href="{{ route('website.mcqs.practice', $mcq->uuid) }}" class="text-decoration-none">
                                    {!! Str::limit(strip_tags($mcq->question), 80) !!}
                                </a>
                            </h6>
                            <div class="d-flex justify-content-between">
                                <span class="badge bg-light text-dark">{{ $mcq->subject->name }}</span>
                                <small class="text-muted">{{ $mcq->difficulty_level }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Topics -->
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-folder me-2"></i>Topics</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            @foreach($topics as $topic)
                            <a href="{{ route('website.mcqs.topic', ['test_type' => $testType->slug, 'subject' => $topic->subject->slug, 'topic' => $topic->slug]) }}"
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span>{{ $topic->title }}</span>
                                <span class="badge bg-primary rounded-pill">{{ $topic->mcqs_count }}</span>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection