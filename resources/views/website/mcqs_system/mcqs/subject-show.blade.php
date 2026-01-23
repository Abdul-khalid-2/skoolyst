@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<style>
    /* Add new styles for test type cards */
    .test-type-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 20px;
        border: 1px solid #e9ecef;
        box-shadow: 0 3px 15px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }

    .test-type-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        border-color: #4361ee;
    }

    .test-type-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 20px;
    }

    .test-type-badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
        margin-bottom: 10px;
    }

    .test-type-badge.exam { background: #e8f4ff; color: #4361ee; }
    .test-type-badge.practice { background: #d4edda; color: #155724; }
    .test-type-badge.quiz { background: #fff3cd; color: #856404; }

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
        font-size: 1.2rem;
        font-weight: 600;
        color: #2c3e50;
    }

    .stat-label {
        font-size: 0.8rem;
        color: #6c757d;
    }

    /* Subject header styles */
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

    /* Topics section */
    .topics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 15px;
        margin-top: 20px;
    }

    .topic-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .topic-card:hover {
        border-color: #4361ee;
        box-shadow: 0 5px 15px rgba(67, 97, 238, 0.1);
    }

    /* Keep your existing styles from the original file */
    /* ... (copy all the existing styles from your current file) ... */
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
                        <div class="subject-icon-large me-4" style="width: 80px; height: 80px; border-radius: 15px; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; background: rgba(255,255,255,0.2);">
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
                            <div class="subject-stat-value">{{ $difficultyStats->sum('count') }}</div>
                            <div class="subject-stat-label">Total Questions</div>
                        </div>
                        <div class="subject-stat">
                            <div class="subject-stat-value">{{ $topics->count() }}</div>
                            <div class="subject-stat-label">Topics</div>
                        </div>
                        <div class="subject-stat">
                            <div class="subject-stat-value">{{ $testTypes->count() }}</div>
                            <div class="subject-stat-label">Test Types</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Test Types Section -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">Available Test Types</h4>
                        <p class="text-muted mb-0">Choose a test type to start practicing</p>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            @foreach($testTypes as $testType)
                                <div class="col-md-6">
                                    <a href="{{ route('website.mcqs.subject.test-type', ['test_type' => $testType->slug, 'subject' => $subject->slug]) }}" 
                                       class="text-decoration-none">
                                        <div class="test-type-card">
                                            <div class="test-type-icon">
                                                <i class="{{ $testType->icon ?? 'fas fa-clipboard-list' }}"></i>
                                            </div>
                                            <span class="test-type-badge {{ $testType->slug }}">
                                                {{ ucfirst($testType->name) }}
                                            </span>
                                            <h5 class="h5 mb-3">{{ $testType->name }} Practice</h5>
                                            <p class="text-muted small mb-3">
                                                Practice {{ $subject->name }} questions specifically for {{ $testType->name }}
                                            </p>
                                            <div class="test-type-stats">
                                                <div class="stat-item">
                                                    <div class="stat-value">{{ $testType->mcqs_count ?? 0 }}</div>
                                                    <div class="stat-label">Questions</div>
                                                </div>
                                                <div class="stat-item">
                                                    <div class="stat-value">{{ $topics->count() }}</div>
                                                    <div class="stat-label">Topics</div>
                                                </div>
                                                <div class="stat-item">
                                                    <button class="btn btn-sm btn-primary">
                                                        Start <i class="fas fa-arrow-right ms-1"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Topics Section -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">Topics</h4>
                            <p class="text-muted mb-0">Browse questions by topic</p>
                        </div>
                        <span class="badge bg-primary">{{ $topics->count() }} Topics</span>
                    </div>
                    <div class="card-body">
                        <div class="topics-grid">
                            @foreach($topics as $topic)
                                <a href="{{ route('website.mcqs.subject.test-type', [
                                    'test_type' => $testTypes->first()->slug ?? 'general',
                                    'subject' => $subject->slug
                                ]) }}?topic={{ $topic->id }}" 
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
                                            <span class="badge topic-difficulty difficulty-{{ $topic->difficulty_level }}">
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
                    </div>
                </div>

                <!-- Recent Questions Preview -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">Recent Questions Preview</h4>
                        <p class="text-muted mb-0">Try these questions to get started</p>
                    </div>
                    <div class="card-body">
                        @if($recentMcqs->count() > 0)
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
                                            <a href="{{ route('website.mcqs.subject.test-type', [
                                                'test_type' => $testTypes->first()->slug ?? 'general',
                                                'subject' => $subject->slug
                                            ]) }}" class="btn btn-sm btn-outline-primary mt-2">
                                                Practice This Question <i class="fas fa-arrow-right ms-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-search fa-2x text-muted mb-3"></i>
                                <p class="text-muted mb-0">No questions available yet</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Difficulty Stats -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Difficulty Distribution</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Easy</span>
                                <span>{{ $difficultyStats['easy']->count ?? 0 }}</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-success" style="width: {{ ($difficultyStats['easy']->count ?? 0) / max($difficultyStats->sum('count'), 1) * 100 }}%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Medium</span>
                                <span>{{ $difficultyStats['medium']->count ?? 0 }}</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-warning" style="width: {{ ($difficultyStats['medium']->count ?? 0) / max($difficultyStats->sum('count'), 1) * 100 }}%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Hard</span>
                                <span>{{ $difficultyStats['hard']->count ?? 0 }}</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-danger" style="width: {{ ($difficultyStats['hard']->count ?? 0) / max($difficultyStats->sum('count'), 1) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="mb-3">Quick Actions</h5>
                        @if($testTypes->count() > 0)
                            <a href="{{ route('website.mcqs.subject.test-type', [
                                'test_type' => $testTypes->first()->slug,
                                'subject' => $subject->slug
                            ]) }}" class="btn btn-primary btn-lg w-100 mb-3">
                                <i class="fas fa-play-circle me-2"></i>Start Practice
                            </a>
                        @endif
                        <a href="{{ route('website.mcqs.mock-tests', ['subject' => $subject->slug]) }}" class="btn btn-outline-primary btn-lg w-100 mb-3">
                            <i class="fas fa-clipboard-list me-2"></i>Take Mock Test
                        </a>
                        <a href="#" class="btn btn-outline-secondary btn-lg w-100" onclick="practiceAllRandom()">
                            <i class="fas fa-random me-2"></i>Random Questions
                        </a>
                    </div>
                </div>

                <!-- Subject Info -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>About This Subject</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">{{ $subject->description ?? 'No description available.' }}</p>
                        <div class="mt-3">
                            <small class="text-muted d-block mb-1">
                                <i class="fas fa-hashtag me-2"></i>Subject Code: {{ $subject->code ?? 'N/A' }}
                            </small>
                            <small class="text-muted d-block mb-1">
                                <i class="fas fa-calendar me-2"></i>Last Updated: {{ $subject->updated_at->format('M d, Y') }}
                            </small>
                            <small class="text-muted d-block">
                                <i class="fas fa-tags me-2"></i>
                                @if($subject->tags)
                                    Tags: {{ $subject->tags }}
                                @else
                                    No tags
                                @endif
                            </small>
                        </div>
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
    function practiceAllRandom() {
        const randomTestType = @json($testTypes->random()->slug ?? 'general');
        window.location.href = `/mcqs/${randomTestType}/{{ $subject->slug }}?difficulty=random`;
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