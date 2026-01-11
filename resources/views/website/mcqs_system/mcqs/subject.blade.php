@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<style>
    .difficulty-filter {
        display: inline-block;
        padding: 8px 20px;
        margin: 5px;
        border-radius: 25px;
        border: 2px solid #dee2e6;
        background: white;
        color: #495057;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .difficulty-filter:hover {
        border-color: #4361ee;
        color: #4361ee;
    }

    .difficulty-filter.active {
        background: #4361ee;
        border-color: #4361ee;
        color: white;
    }

    .question-preview {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 15px;
        border-left: 4px solid #4361ee;
        transition: all 0.3s ease;
    }

    .question-preview:hover {
        background: #e9ecef;
        transform: translateX(5px);
    }

    .mcq-counter {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 35px;
        height: 35px;
        background: #4361ee;
        color: white;
        border-radius: 50%;
        font-weight: bold;
        margin-right: 10px;
    }

    .topic-filter {
        padding: 8px 15px;
        margin: 3px;
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 20px;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }

    .topic-filter:hover {
        background: #e9ecef;
        border-color: #4361ee;
    }

    .topic-filter.active {
        background: #4361ee;
        color: white;
        border-color: #4361ee;
    }

    .stats-badge {
        padding: 10px 15px;
        border-radius: 10px;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .stats-badge.easy { background: #d4edda; color: #155724; }
    .stats-badge.medium { background: #fff3cd; color: #856404; }
    .stats-badge.hard { background: #f8d7da; color: #721c24; }
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
                <li class="breadcrumb-item"><a href="{{ route('website.mcqs.test-type', $testType->slug) }}">{{ $testType->name }}</a></li>
                <li class="breadcrumb-item active">{{ $subject->name }}</li>
            </ol>
        </nav>

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Subject Header -->
                <div class="d-flex align-items-center mb-4">
                    <div class="subject-icon-large me-4" style="background: {{ $subject->color_code }}; color: white; width: 80px; height: 80px; border-radius: 15px; display: flex; align-items: center; justify-content: center; font-size: 2rem;">
                        <i class="{{ $subject->icon ?? 'fas fa-book' }}"></i>
                    </div>
                    <div>
                        <h1 class="h2 mb-2">{{ $subject->name }}</h1>
                        <p class="text-muted mb-0">{{ $subject->description }}</p>
                    </div>
                </div>

                <!-- Difficulty Filters -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Filter by Difficulty</h5>
                        <div class="text-center">
                            <a href="{{ request()->fullUrlWithQuery(['difficulty' => null]) }}" 
                               class="difficulty-filter {{ !request('difficulty') ? 'active' : '' }}">
                                All ({{ $difficultyStats->sum('count') }})
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['difficulty' => 'easy']) }}" 
                               class="difficulty-filter {{ request('difficulty') === 'easy' ? 'active' : '' }}">
                                Easy ({{ $difficultyStats['easy']->count ?? 0 }})
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['difficulty' => 'medium']) }}" 
                               class="difficulty-filter {{ request('difficulty') === 'medium' ? 'active' : '' }}">
                                Medium ({{ $difficultyStats['medium']->count ?? 0 }})
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['difficulty' => 'hard']) }}" 
                               class="difficulty-filter {{ request('difficulty') === 'hard' ? 'active' : '' }}">
                                Hard ({{ $difficultyStats['hard']->count ?? 0 }})
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Topic Filters -->
                @if($topics->count() > 0)
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Topics</h5>
                        <div class="d-flex flex-wrap">
                            <a href="{{ request()->fullUrlWithQuery(['topic' => null]) }}" 
                               class="topic-filter {{ !request('topic') ? 'active' : '' }}">
                                All Topics
                            </a>
                            @foreach($topics as $topic)
                            <a href="{{ request()->fullUrlWithQuery(['topic' => $topic->id]) }}" 
                               class="topic-filter {{ request('topic') == $topic->id ? 'active' : '' }}">
                                {{ $topic->title }} ({{ $topic->mcqs_count }})
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Questions List -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Practice Questions</h5>
                        <span class="badge bg-primary">
                            {{ $mcqs->total() }} Questions
                        </span>
                    </div>
                    <div class="card-body">
                        @if($mcqs->count() > 0)
                            @foreach($mcqs as $index => $mcq)
                            <div class="question-preview">
                                <div class="d-flex align-items-start mb-2">
                                    <div class="mcq-counter">{{ ($mcqs->currentPage() - 1) * $mcqs->perPage() + $index + 1 }}</div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-2">
                                            <a href="{{ route('website.mcqs.practice', $mcq->uuid) }}" class="text-decoration-none text-dark">
                                                {!! Str::limit(strip_tags($mcq->question), 200) !!}
                                            </a>
                                        </h6>
                                        <div class="d-flex align-items-center flex-wrap">
                                            <span class="badge {{ $mcq->difficulty_level == 'easy' ? 'bg-success' : ($mcq->difficulty_level == 'medium' ? 'bg-warning text-dark' : 'bg-danger') }} me-2 mb-1">
                                                {{ ucfirst($mcq->difficulty_level) }}
                                            </span>
                                            @if($mcq->topic)
                                            <span class="badge bg-light text-dark me-2 mb-1">
                                                <i class="fas fa-folder me-1"></i>{{ $mcq->topic->title }}
                                            </span>
                                            @endif
                                            <span class="badge bg-light text-dark mb-1">
                                                <i class="fas fa-star me-1"></i>{{ $mcq->marks }} Mark{{ $mcq->marks > 1 ? 's' : '' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="far fa-clock me-1"></i>
                                        @if($mcq->time_limit_seconds)
                                            {{ ceil($mcq->time_limit_seconds / 60) }} min
                                        @else
                                            Untimed
                                        @endif
                                    </small>
                                    <a href="{{ route('website.mcqs.practice', $mcq->uuid) }}" class="btn btn-sm btn-outline-primary">
                                        Practice <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                            @endforeach

                            <!-- Pagination -->
                            @if($mcqs->hasPages())
                            <div class="mt-4">
                                {{ $mcqs->links('pagination::bootstrap-5') }}
                            </div>
                            @endif
                        @else
                        <div class="text-center py-5">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No questions found</h5>
                            <p class="text-muted">Try adjusting your filters</p>
                            <a href="{{ request()->fullUrlWithQuery(['difficulty' => null, 'topic' => null]) }}" class="btn btn-primary">
                                Clear Filters
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Quick Stats -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Quick Stats</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="text-center p-3 bg-light rounded">
                                    <div class="h3 mb-1">{{ $difficultyStats->sum('count') }}</div>
                                    <div class="text-muted small">Total Questions</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-3 bg-light rounded">
                                    <div class="h3 mb-1">{{ $topics->count() }}</div>
                                    <div class="text-muted small">Topics</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="stats-badge easy mb-2">
                                    <i class="fas fa-check-circle me-2"></i>
                                    Easy: {{ $difficultyStats['easy']->count ?? 0 }} questions
                                </div>
                                <div class="stats-badge medium mb-2">
                                    <i class="fas fa-circle me-2"></i>
                                    Medium: {{ $difficultyStats['medium']->count ?? 0 }} questions
                                </div>
                                <div class="stats-badge hard">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    Hard: {{ $difficultyStats['hard']->count ?? 0 }} questions
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recommended Practice -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-fire me-2"></i>Recommended Practice</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            @foreach($topics->take(5) as $topic)
                            <a href="{{ route('website.mcqs.topic', ['test_type' => $testType->slug, 'subject' => $subject->slug, 'topic' => $topic->slug]) }}" 
                               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span>{{ $topic->title }}</span>
                                <span class="badge bg-primary rounded-pill">{{ $topic->mcqs_count }}</span>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="mb-3">Quick Actions</h5>
                        <a href="{{ route('website.mcqs.mock-tests', ['test_type' => $testType->slug]) }}" class="btn btn-primary btn-lg w-100 mb-3">
                            <i class="fas fa-clipboard-list me-2"></i>Take Mock Test
                        </a>
                        <a href="#" class="btn btn-outline-primary btn-lg w-100">
                            <i class="fas fa-random me-2"></i>Random Questions
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection