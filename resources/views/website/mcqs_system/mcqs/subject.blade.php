@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<style>
    /* Subject Header Styles */
    .subject-header-card {
        background: linear-gradient(135deg, {{ $subject->color_code ?? '#4361ee' }} 0%, #3a0ca3 100%);
        color: white;
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 30px;
    }

    .subject-stats {
        display: flex;
        gap: 30px;
        flex-wrap: wrap;
    }

    .subject-stat {
        text-align: center;
    }

    .subject-stat-value {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .subject-stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    /* Test Type Cards */
    .test-type-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #e9ecef;
        box-shadow: 0 3px 15px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        height: 100%;
    }

    .test-type-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        border-color: #4361ee;
    }

    .test-type-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        background: #4361ee;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        margin-bottom: 15px;
    }

    .test-type-stats {
        display: flex;
        justify-content: space-between;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #e9ecef;
    }

    .stat-item {
        text-align: center;
    }

    .stat-value {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2c3e50;
    }

    .stat-label {
        font-size: 0.8rem;
        color: #6c757d;
    }

    /* Topics Cards */
    .topic-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
        height: 100%;
    }

    .topic-card:hover {
        border-color: #4361ee;
        box-shadow: 0 5px 15px rgba(67, 97, 238, 0.1);
    }

    .topics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 15px;
        margin-top: 20px;
    }

    .topic-difficulty {
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
    }

    .difficulty-beginner { background: #d4edda; color: #155724; }
    .difficulty-intermediate { background: #fff3cd; color: #856404; }
    .difficulty-advanced { background: #f8d7da; color: #721c24; }

    /* Question Preview */
    .question-preview {
        background: white;
        border-radius: 10px;
        padding: 20px;
        border: 1px solid #e9ecef;
        margin-bottom: 15px;
    }

    .mcq-counter {
        width: 30px;
        height: 30px;
        background: #4361ee;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        margin-right: 15px;
        flex-shrink: 0;
    }

    .mcq-meta {
        display: flex;
        gap: 10px;
        margin-bottom: 10px;
        flex-wrap: wrap;
    }

    .difficulty-badge {
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .difficulty-easy { background: #d4edda; color: #155724; }
    .difficulty-medium { background: #fff3cd; color: #856404; }
    .difficulty-hard { background: #f8d7da; color: #721c24; }

    .question-text {
        line-height: 1.6;
    }

    /* Stats Cards */
    .stats-summary {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }

    /* Difficulty Stats */
    .difficulty-item {
        margin-bottom: 15px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .subject-header-card {
            padding: 20px;
        }
        
        .subject-stat-value {
            font-size: 1.5rem;
        }
        
        .topics-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@endpush

@section('content')
<section class="py-5">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('website.mcqs.index') }}">MCQs</a></li>
                <li class="breadcrumb-item active">{{ $subject->name }}</li>
            </ol>
        </nav>

        <!-- Subject Header -->
        <div class="subject-header-card">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center mb-4">
                        <div class="subject-icon-large me-4" 
                             style="width: 80px; height: 80px; border-radius: 15px; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; background: rgba(255,255,255,0.2);">
                            <i class="{{ $subject->icon ?? 'fas fa-book' }}"></i>
                        </div>
                        <div>
                            <h1 class="h2 mb-2">{{ $subject->name }}</h1>
                            <p class="mb-0 opacity-75">{{ $subject->description }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="subject-stats">
                        <div class="subject-stat">
                            <div class="subject-stat-value">{{ $subject->mcqs_count }}</div>
                            <div class="subject-stat-label">Total Questions</div>
                        </div>
                        <div class="subject-stat">
                            <div class="subject-stat-value">{{ $topics->count() }}</div>
                            <div class="subject-stat-label">Topics</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Topics Section -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">Topics</h4>
                            <p class="text-muted mb-0">Browse questions by specific topics</p>
                        </div>
                        <span class="badge bg-primary">{{ $topics->count() }} Topics</span>
                    </div>
                    <div class="card-body">
                        @if($topics->count() > 0)
                        <div class="topics-grid">
                            @foreach($topics as $topic)
                            <a href="{{ route('website.mcqs.topic', [$subject->slug, $topic->slug]) }}" 
                               class="text-decoration-none">
                                <div class="topic-card">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="mb-0">{{ $topic->title }}</h6>
                                        <span class="badge bg-light text-dark">{{ $topic->mcqs_count }}</span>
                                    </div>
                                    @if($topic->description)
                                    <p class="text-muted small mb-2">{{ Str::limit($topic->description, 100) }}</p>
                                    @endif
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="topic-difficulty difficulty-{{ $topic->difficulty_level }}">
                                            {{ ucfirst($topic->difficulty_level) }}
                                        </span>
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>{{ $topic->estimated_time_minutes }} min
                                        </small>
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-4">
                            <i class="fas fa-folder fa-2x text-muted mb-3"></i>
                            <p class="text-muted mb-0">No topics available yet</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Questions Preview -->
                @if($recentMcqs->count() > 0)
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">Recent Questions Preview</h4>
                        <p class="text-muted mb-0">Try these questions to get started</p>
                    </div>
                    <div class="card-body">
                        @foreach($recentMcqs as $index => $mcq)
                        <div class="question-preview mb-3">
                            <div class="d-flex align-items-start">
                                <div class="mcq-counter">{{ $index + 1 }}</div>
                                <div class="flex-grow-1">
                                    <div class="mcq-meta">
                                        <span class="difficulty-badge difficulty-{{ $mcq->difficulty_level }}">
                                            {{ ucfirst($mcq->difficulty_level) }}
                                        </span>
                                        @if($mcq->topic)
                                        <span class="badge bg-light text-dark">
                                            <i class="fas fa-folder me-1"></i>{{ $mcq->topic->title }}
                                        </span>
                                        @endif
                                    </div>
                                    <div class="question-text">
                                        {!! Str::limit(strip_tags($mcq->question), 150) !!}
                                    </div>
                                    <div class="mt-2">
                                        @if($mcq->testTypes->count() > 0)
                                        <div class="d-flex flex-wrap gap-1 mb-2">
                                            @foreach($mcq->testTypes->take(3) as $testType)
                                            <span class="badge bg-light text-dark small">
                                                {{ $testType->name }}
                                            </span>
                                            @endforeach
                                            @if($mcq->testTypes->count() > 3)
                                            <span class="badge bg-secondary small">+{{ $mcq->testTypes->count() - 3 }}</span>
                                            @endif
                                        </div>
                                        @endif
                                        <a href="{{ route('website.mcqs.practice', $mcq->uuid) }}" class="btn btn-sm btn-outline-primary">
                                            Practice This Question <i class="fas fa-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Difficulty Stats -->
                @if($difficultyStats->count() > 0)
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Difficulty Distribution</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $totalQuestions = $subject->mcqs_count ?: 1;
                        @endphp
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Easy</span>
                                <span>{{ $difficultyStats['easy']->count ?? 0 }}</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-success" 
                                     style="width: {{ ($difficultyStats['easy']->count ?? 0) / $totalQuestions * 100 }}%"></div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Medium</span>
                                <span>{{ $difficultyStats['medium']->count ?? 0 }}</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-warning" 
                                     style="width: {{ ($difficultyStats['medium']->count ?? 0) / $totalQuestions * 100 }}%"></div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Hard</span>
                                <span>{{ $difficultyStats['hard']->count ?? 0 }}</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-danger" 
                                     style="width: {{ ($difficultyStats['hard']->count ?? 0) / $totalQuestions * 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Quick Actions -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="mb-3">Quick Actions</h5>
                        @if($testTypes->count() > 0)
                        <a href="{{ route('website.mcqs.subject-by-test-type', [$testTypes->first()->slug, $subject->slug]) }}" 
                           class="btn btn-primary btn-lg w-100 mb-3">
                            <i class="fas fa-play-circle me-2"></i>Start Practice
                        </a>
                        @endif
                        
                        @if($topics->count() > 0)
                        <a href="{{ route('website.mcqs.topic', [$subject->slug, $topics->first()->slug]) }}" 
                           class="btn btn-outline-primary btn-lg w-100 mb-3">
                            <i class="fas fa-folder-open me-2"></i>Browse Topics
                        </a>
                        @endif
                        
                        <button class="btn btn-outline-secondary btn-lg w-100" onclick="practiceRandom()">
                            <i class="fas fa-random me-2"></i>Random Questions
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Random practice function
    function practiceRandom() {
        @if($testTypes->count() > 0)
            const testTypes = @json($testTypes->pluck('slug')->toArray());
            const randomTestType = testTypes[Math.floor(Math.random() * testTypes.length)];
            window.location.href = `/mcqs/test-type/${randomTestType}/subject/{{ $subject->slug }}`;
        @else
            window.location.href = `/mcqs/subject/{{ $subject->slug }}/topic/{{ $topics->first()->slug ?? '' }}`;
        @endif
    }

    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush