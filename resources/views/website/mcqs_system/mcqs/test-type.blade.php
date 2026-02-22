@extends('website.layout.app')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<style>
    /* Test Type Header */
    .test-type-header {
        background: linear-gradient(135deg, {{ $testType->color ?? '#4361ee' }} 0%, {{ $testType->color ?? '#3a0ca3' }} 100%);
        color: white;
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 30px;
    }

    .test-type-icon-large {
        width: 100px;
        height: 100px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
    }

    /* Subject Cards */
    .subject-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #e9ecef;
        box-shadow: 0 3px 15px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        height: 100%;
    }

    .subject-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        border-color: #4361ee;
    }

    .subject-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 15px;
        color: white;
    }

    .subject-meta {
        display: flex;
        justify-content: space-between;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #e9ecef;
    }

    /* Featured Questions */
    .featured-mcq {
        background: white;
        border-radius: 10px;
        padding: 15px;
        border-left: 4px solid #4361ee;
        margin-bottom: 15px;
        transition: all 0.3s ease;
    }

    .featured-mcq:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .mcq-preview {
        line-height: 1.5;
    }

    /* Topics List */
    .topic-list-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 15px;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        margin-bottom: 8px;
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
    }

    .topic-list-item:hover {
        background: #f8f9fa;
        border-color: #4361ee;
    }

    .topic-subject {
        font-size: 0.8rem;
        color: #6c757d;
    }

    /* Stats Cards */
    .stats-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        border: 1px solid #e9ecef;
        box-shadow: 0 3px 10px rgba(0,0,0,0.05);
    }

    .stats-number {
        font-size: 2rem;
        font-weight: 700;
        color: #4361ee;
        margin-bottom: 5px;
    }

    .stats-label {
        font-size: 0.9rem;
        color: #6c757d;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .test-type-header {
            padding: 20px;
        }
        
        .test-type-icon-large {
            width: 70px;
            height: 70px;
            font-size: 2rem;
        }
    }
</style>
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
            </div>
        </div>

        <!-- Test Type Header -->
        <div class="test-type-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="h2 mb-3">{{ $testType->name }}</h1>
                    <p class="mb-4">{{ $testType->description }}</p>
                    <div class="d-flex gap-4">
                        <div class="stats-card" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px);">
                            <div class="stats-number">{{ $subjects->count() }}</div>
                            <div class="stats-label text-white">Subjects</div>
                        </div>
                        <div class="stats-card" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px);">
                            <div class="stats-number">{{ $totalMcqs }}</div>
                            <div class="stats-label text-white">Total MCQs</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="test-type-icon-large">
                        <i class="{{ $testType->icon ?? 'fas fa-graduation-cap' }}"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row mb-5">
            <div class="col-md-3 col-6">
                <div class="stats-card">
                    <div class="stats-number">{{ $subjects->sum('mcqs_count') }}</div>
                    <div class="stats-label">Available Questions</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stats-card">
                    <div class="stats-number">{{ $subjects->sum('topics_count') }}</div>
                    <div class="stats-label">Total Topics</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stats-card">
                    <div class="stats-number">{{ $subjects->count() }}</div>
                    <div class="stats-label">Subjects</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stats-card">
                    <div class="stats-number">{{ $totalMcqs }}</div>
                    <div class="stats-label">MCQs</div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Subjects List -->
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">Subjects for {{ $testType->name }}</h4>
                        <p class="text-muted mb-0">Practice subject-wise questions</p>
                    </div>
                    <div class="card-body">
                        @if($subjects->count() > 0)
                        <div class="row g-4">
                            @foreach($subjects as $subject)
                            <div class="col-md-6">
                                <div class="subject-card">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="subject-icon" style="background: {{ $subject->color_code ?? '#4361ee' }};">
                                            <i class="{{ $subject->icon ?? 'fas fa-book' }}"></i>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-primary">{{ $subject->mcqs_count }} MCQs</span>
                                        </div>
                                    </div>
                                    <h5 class="h5 mb-2">{{ $subject->name }}</h5>
                                    <p class="text-muted small mb-3">{{ Str::limit($subject->description ?? '', 100) }}</p>
                                    
                                    <!-- Topics List -->
                                    @if(isset($subject->topics) && $subject->topics->count() > 0)
                                    <div class="mb-3">
                                        <h6 class="small fw-bold text-muted mb-2">Topics:</h6>
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($subject->topics->take(4) as $topic)
                                            <a href="{{ route('website.mcqs.subject-by-test-type', [$testType->slug, $subject->slug]) }}?topic={{ $topic->id }}" 
                                               class="badge bg-light text-dark text-decoration-none">
                                                {{ $topic->title }} ({{ $topic->mcqs_count }})
                                            </a>
                                            @endforeach
                                            @if($subject->topics->count() > 4)
                                            <span class="badge bg-secondary">+{{ $subject->topics->count() - 4 }} more</span>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                    
                                    <div class="subject-meta">
                                        <div>
                                            <a href="{{ route('website.mcqs.subject-by-test-type', [$testType->slug, $subject->slug]) }}"
                                               class="btn btn-sm btn-primary">
                                                View All MCQs
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-4">
                            <i class="fas fa-book fa-2x text-muted mb-3"></i>
                            <p class="text-muted mb-0">No subjects available for {{ $testType->name }}</p>
                        </div>
                        @endif
                    </div>
                </div>
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


                <!-- Popular Subjects -->
                @if($subjects->count() > 0)
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Popular Subjects</h5>
                    </div>
                    <div class="card-body">
                        @foreach($subjects->sortByDesc('mcqs_count')->take(5) as $subject)
                        <a href="{{ route('website.mcqs.subject-by-test-type', [$testType->slug, $subject->slug]) }}" 
                           class="d-block mb-3 text-decoration-none">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="subject-icon-sm me-3" 
                                         style="width: 30px; height: 30px; border-radius: 6px; background: {{ $subject->color_code ?? '#4361ee' }}; display: flex; align-items: center; justify-content: center; color: white;">
                                        <i class="{{ $subject->icon ?? 'fas fa-book' }} fa-xs"></i>
                                    </div>
                                    <div>
                                        <div class="fw-medium">{{ $subject->name }}</div>
                                        <small class="text-muted">{{ $subject->mcqs_count }} questions</small>
                                    </div>
                                </div>
                                <i class="fas fa-chevron-right text-muted"></i>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Topics -->
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
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-exchange-alt me-2"></i>Other Test Types</h5>
                    </div>
                    <div class="card-body">
                        @foreach($relatedTestTypes as $otherTestType)
                        <a href="{{ route('website.mcqs.test-type', $otherTestType->slug) }}" 
                           class="d-block mb-2 text-decoration-none">
                            <div class="d-flex justify-content-between align-items-center p-2 rounded hover-bg-light">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="{{ $otherTestType->icon ?? 'fas fa-graduation-cap' }} text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-medium">{{ $otherTestType->name }}</div>
                                        <small class="text-muted">{{ $otherTestType->mcqs_count }} questions</small>
                                    </div>
                                </div>
                                <i class="fas fa-chevron-right text-muted"></i>
                            </div>
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