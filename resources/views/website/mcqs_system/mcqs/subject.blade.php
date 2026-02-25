@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/subject.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@endpush

@section('content')

<!-- ==================== SUBJECT HERO SECTION ==================== -->
<section class="subject-hero" >
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1>{{ $subject->name }}</h1>
                <p>{{ $subject->description }}</p>
            </div>
            <div class="col-md-4 text-center">
                <div class="subject-icon-large">
                    <i class="{{ $subject->icon ?? 'fas fa-book' }}"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== SUBJECT STATS SECTION (Overlapping Hero) ==================== -->
<section class="subject-stats-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3 col-6">
                <div class="subject-stat-card">
                    <div class="subject-stat-number">{{ $subject->mcqs_count }}</div>
                    <div class="subject-stat-label">Total Questions</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="subject-stat-card">
                    <div class="subject-stat-number">{{ $topics->count() }}</div>
                    <div class="subject-stat-label">Topics</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="subject-stat-card">
                    <div class="subject-stat-number">{{ $subject->mcqs_count }}</div>
                    <div class="subject-stat-label">MCQs</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="subject-stat-card">
                    <div class="subject-stat-number">{{ $topics->sum('mcqs_count') }}</div>
                    <div class="subject-stat-label">Practice Questions</div>
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
                    <li class="breadcrumb-item active">{{ $subject->name }}</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Topics Section -->
                <div class="sidebar-widget mb-4">
                    <div class="card-header">
                        <h5><i class="fas fa-folder me-2"></i>Topics</h5>
                    </div>
                    <div class="card-body">
                        @if($topics->count() > 0)
                        <div class="topics-grid">
                            @foreach($topics as $topic)
                            <a href="{{ route('website.mcqs.topic', [$subject->slug, $topic->slug]) }}" 
                               class="topic-card">
                                <div class="topic-header">
                                    <h6>{{ $topic->title }}</h6>
                                    <span class="topic-count">{{ $topic->mcqs_count }}</span>
                                </div>
                                @if($topic->description)
                                <p class="topic-description">{{ Str::limit($topic->description, 80) }}</p>
                                @endif
                                <div class="topic-footer">
                                    <span class="topic-difficulty {{ $topic->difficulty_level }}">
                                        {{ ucfirst($topic->difficulty_level) }}
                                    </span>
                                    <span class="topic-time">
                                        <i class="fas fa-clock"></i> {{ $topic->estimated_time_minutes }} min
                                    </span>
                                </div>
                            </a>
                            @endforeach
                        </div>
                        @else
                        <div class="empty-state">
                            <i class="fas fa-folder-open"></i>
                            <p class="mb-0">No topics available yet</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Questions Preview -->
                @if($recentMcqs->count() > 0)
                <div class="sidebar-widget">
                    <div class="card-header">
                        <h5><i class="fas fa-clock me-2"></i>Recent Questions</h5>
                    </div>
                    <div class="card-body">
                        @foreach($recentMcqs as $index => $mcq)
                        <div class="question-preview">
                            <div class="d-flex align-items-start gap-3">
                                <div class="question-number">{{ $index + 1 }}</div>
                                <div class="flex-grow-1">
                                    <div class="question-meta">
                                        <span class="difficulty-badge {{ $mcq->difficulty_level }}">
                                            {{ ucfirst($mcq->difficulty_level) }}
                                        </span>
                                        @if($mcq->topic)
                                        <span class="topic-tag">
                                            <i class="fas fa-folder"></i> {{ $mcq->topic->title }}
                                        </span>
                                        @endif
                                    </div>
                                    <div class="question-text">
                                        {!! Str::limit(strip_tags($mcq->question), 120) !!}
                                    </div>
                                    @if($mcq->testTypes->count() > 0)
                                    <div class="question-test-types">
                                        @foreach($mcq->testTypes->take(3) as $testType)
                                        <span class="test-type-tag">{{ $testType->name }}</span>
                                        @endforeach
                                        @if($mcq->testTypes->count() > 3)
                                        <span class="test-type-tag">+{{ $mcq->testTypes->count() - 3 }}</span>
                                        @endif
                                    </div>
                                    @endif
                                    <div class="mt-3">
                                        <a href="{{ route('website.mcqs.practice', $mcq->uuid) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            Practice <i class="fas fa-arrow-right ms-1"></i>
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
                @if(isset($difficultyStats) && $difficultyStats->count() > 0)
                <div class="sidebar-widget mb-4">
                    <div class="card-header">
                        <h5><i class="fas fa-chart-pie me-2"></i>Difficulty Distribution</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $totalQuestions = $subject->mcqs_count ?: 1;
                        @endphp
                        
                        <div class="difficulty-stats">
                            <div class="difficulty-stat-item">
                                <div class="difficulty-stat-label">
                                    <span>Easy</span>
                                    <span>{{ $difficultyStats['easy']->count ?? 0 }}</span>
                                </div>
                                <div class="difficulty-progress">
                                    <div class="difficulty-progress-bar easy" 
                                         style="width: {{ ($difficultyStats['easy']->count ?? 0) / $totalQuestions * 100 }}%"></div>
                                </div>
                            </div>
                            
                            <div class="difficulty-stat-item">
                                <div class="difficulty-stat-label">
                                    <span>Medium</span>
                                    <span>{{ $difficultyStats['medium']->count ?? 0 }}</span>
                                </div>
                                <div class="difficulty-progress">
                                    <div class="difficulty-progress-bar medium" 
                                         style="width: {{ ($difficultyStats['medium']->count ?? 0) / $totalQuestions * 100 }}%"></div>
                                </div>
                            </div>
                            
                            <div class="difficulty-stat-item">
                                <div class="difficulty-stat-label">
                                    <span>Hard</span>
                                    <span>{{ $difficultyStats['hard']->count ?? 0 }}</span>
                                </div>
                                <div class="difficulty-progress">
                                    <div class="difficulty-progress-bar hard" 
                                         style="width: {{ ($difficultyStats['hard']->count ?? 0) / $totalQuestions * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Quick Actions -->
                <div class="sidebar-widget">
                    <div class="card-header">
                        <h5><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="quick-actions">
                            @if($testTypes->count() > 0)
                            <a href="{{ route('website.mcqs.subject-by-test-type', [$testTypes->first()->slug, $subject->slug]) }}" 
                               class="quick-action-btn primary">
                                <i class="fas fa-play-circle"></i> Start Practice
                            </a>
                            @endif
                            
                            @if($topics->count() > 0)
                            <a href="{{ route('website.mcqs.topic', [$subject->slug, $topics->first()->slug]) }}" 
                               class="quick-action-btn outline-primary">
                                <i class="fas fa-folder-open"></i> Browse Topics
                            </a>
                            @endif
                            
                            <button class="quick-action-btn outline-secondary" onclick="practiceRandom()">
                                <i class="fas fa-random"></i> Random Questions
                            </button>
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
    function practiceRandom() {
        @if($testTypes->count() > 0)
            const testTypes = @json($testTypes->pluck('slug')->toArray());
            const randomTestType = testTypes[Math.floor(Math.random() * testTypes.length)];
            window.location.href = `/mcqs/test-type/${randomTestType}/subject/{{ $subject->slug }}`;
        @elseif($topics->count() > 0)
            window.location.href = `/mcqs/subject/{{ $subject->slug }}/topic/{{ $topics->first()->slug }}`;
        @else
            alert('No questions available for practice.');
        @endif
    }
</script>
@endpush