@extends('website.layout.app')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/test-type.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@endpush
@section('content')

<!-- ==================== TEST TYPE HERO SECTION ==================== -->
<section class="test-type-hero" style="background: linear-gradient(135deg, {{ $testType->color ?? '#4361ee' }} 0%, {{ $testType->color ?? '#3a0ca3' }} 100%);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1>{{ $testType->name }}</h1>
                <p>{{ $testType->description }}</p>
            </div>
            <div class="col-md-4 text-center">
                <div class="test-type-icon-large">
                    <i class="{{ $testType->icon ?? 'fas fa-graduation-cap' }}"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== STATS SECTION (Overlapping Hero) ==================== -->
<section class="test-type-stats-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3 col-6">
                <div class="test-type-stat-card">
                    <div class="test-type-stat-number">{{ $subjects->sum('mcqs_count') }}</div>
                    <div class="test-type-stat-label">Available Questions</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="test-type-stat-card">
                    <div class="test-type-stat-number">{{ $subjects->sum('topics_count') }}</div>
                    <div class="test-type-stat-label">Total Topics</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="test-type-stat-card">
                    <div class="test-type-stat-number">{{ $subjects->count() }}</div>
                    <div class="test-type-stat-label">Subjects</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="test-type-stat-card">
                    <div class="test-type-stat-number">{{ $totalMcqs }}</div>
                    <div class="test-type-stat-label">Total MCQs</div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <!-- Breadcrumb -->
        <div class="breadcrumb-wrapper">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('website.mcqs.index') }}">MCQs</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $testType->name }}</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <!-- Subjects List -->
            <div class="col-lg-8">
                <div class="section-title-wrapper">
                    <h2 class="section-title">Subjects for {{ $testType->name }}</h2>
                    <p class="section-subtitle">Practice subject-wise questions</p>
                </div>

                @if($subjects->count() > 0)
                <div class="row g-4">
                    @foreach($subjects as $subject)
                    <div class="col-md-6">
                        <div class="subject-card">
                            <div class="subject-header">
                                <div class="subject-icon" style="background: {{ $subject->color_code ?? '#4361ee' }};">
                                    <i class="{{ $subject->icon ?? 'fas fa-book' }}"></i>
                                </div>
                                <span class="subject-badge">{{ $subject->mcqs_count }} MCQs</span>
                            </div>
                            
                            <h5>{{ $subject->name }}</h5>
                            @if($subject->description)
                            <p class="subject-description">{{ Str::limit($subject->description, 100) }}</p>
                            @endif
                            
                            <!-- Topics List -->
                            @if(isset($subject->topics) && $subject->topics->count() > 0)
                            <div class="topics-section">
                                <span class="topics-label">Topics:</span>
                                <div class="topics-list">
                                    @foreach($subject->topics->take(4) as $topic)
                                    <a href="{{ route('website.mcqs.subject-by-test-type', [$testType->slug, $subject->slug]) }}?topic={{ $topic->id }}" 
                                       class="topic-badge">
                                        {{ $topic->title }} ({{ $topic->mcqs_count }})
                                    </a>
                                    @endforeach
                                    @if($subject->topics->count() > 4)
                                    <span class="topic-badge-more">+{{ $subject->topics->count() - 4 }} more</span>
                                    @endif
                                </div>
                            </div>
                            @endif
                            
                            <div class="subject-footer">
                                <a href="{{ route('website.mcqs.subject-by-test-type', [$testType->slug, $subject->slug]) }}"
                                   class="btn btn-sm btn-primary">
                                    View All MCQs <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="empty-state-modern">
                    <i class="fas fa-book-open"></i>
                    <p>No subjects available for {{ $testType->name }}</p>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Featured Questions -->
                @php
                    // Get featured MCQs for this test type
                    $featuredMcqs = \App\Models\Mcq::whereHas('testTypes', function($query) use ($testType) {
                        $query->where('test_types.id', $testType->id);
                    })
                    ->where('status', 'published')
                    ->with(['subject', 'topic'])
                    ->inRandomOrder()
                    ->limit(3)
                    ->get();
                @endphp

                @if($featuredMcqs->count() > 0)
                <div class="sidebar-widget">
                    <div class="card-header">
                        <h5><i class="fas fa-star me-2"></i>Featured Questions</h5>
                    </div>
                    <div class="card-body">
                        @foreach($featuredMcqs as $mcq)
                        <div class="featured-mcq-card">
                            <div class="featured-mcq-header">
                                <span class="featured-mcq-subject">{{ $mcq->subject->name }}</span>
                                <span class="featured-mcq-difficulty {{ $mcq->difficulty_level }}">
                                    {{ ucfirst($mcq->difficulty_level) }}
                                </span>
                            </div>
                            <div class="featured-mcq-question">
                                {!! strip_tags($mcq->question) !!}
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="featured-mcq-meta">
                                    <span><i class="fas fa-bookmark"></i> {{ $mcq->marks }} Mark(s)</span>
                                </div>
                                <a href="{{ route('website.mcqs.practice', $mcq->uuid) }}" class="featured-mcq-link">
                                    Practice <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Popular Subjects -->
                @if($subjects->count() > 0)
                <div class="sidebar-widget">
                    <div class="card-header">
                        <h5><i class="fas fa-chart-line me-2"></i>Popular Subjects</h5>
                    </div>
                    <div class="card-body">
                        @foreach($subjects->sortByDesc('mcqs_count')->take(5) as $subject)
                        <a href="{{ route('website.mcqs.subject-by-test-type', [$testType->slug, $subject->slug]) }}" 
                           class="popular-subject-item">
                            <div class="popular-subject-info">
                                <div class="subject-icon-sm" style="background: {{ $subject->color_code ?? '#4361ee' }};">
                                    <i class="{{ $subject->icon ?? 'fas fa-book' }}"></i>
                                </div>
                                <div class="subject-details">
                                    <div class="subject-name">{{ $subject->name }}</div>
                                    <div class="subject-question-count">
                                        <i class="fas fa-question-circle"></i>
                                        {{ $subject->mcqs_count }} questions
                                    </div>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right chevron-icon"></i>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Other Test Types -->
                @php
                    $relatedTestTypes = \App\Models\TestType::where('status', 'active')
                        ->where('id', '!=', $testType->id)
                        ->withCount(['mcqs' => function($query) {
                            $query->where('status', 'published');
                        }])
                        ->orderBy('mcqs_count', 'desc')
                        ->limit(4)
                        ->get();
                @endphp

                @if($relatedTestTypes->count() > 0)
                <div class="sidebar-widget">
                    <div class="card-header">
                        <h5><i class="fas fa-exchange-alt me-2"></i>Other Test Types</h5>
                    </div>
                    <div class="card-body">
                        @foreach($relatedTestTypes as $otherTestType)
                        <a href="{{ route('website.mcqs.test-type', $otherTestType->slug) }}" 
                           class="test-type-item">
                            <div class="test-type-info">
                                <div class="test-type-icon">
                                    <i class="{{ $otherTestType->icon ?? 'fas fa-graduation-cap' }}"></i>
                                </div>
                                <div class="test-type-details">
                                    <div class="test-type-name">{{ $otherTestType->name }}</div>
                                    <div class="test-type-count">
                                        <i class="fas fa-question-circle"></i>
                                        {{ $otherTestType->mcqs_count }} questions
                                    </div>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right chevron-icon"></i>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush