@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<style>
    .topic-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 60px 0;
        border-radius: 15px;
        margin-bottom: 40px;
        position: relative;
        overflow: hidden;
    }

    .topic-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
    }

    .topic-content {
        font-size: 1.1rem;
        line-height: 1.8;
        background: white;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }

    .difficulty-distribution {
        height: 10px;
        border-radius: 5px;
        overflow: hidden;
        margin: 20px 0;
    }

    .difficulty-easy-bar { background: #28a745; }
    .difficulty-medium-bar { background: #ffc107; }
    .difficulty-hard-bar { background: #dc3545; }

    .question-card {
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
    }

    .question-card:hover {
        border-color: #4361ee;
        box-shadow: 0 5px 15px rgba(67, 97, 238, 0.1);
    }

    .estimated-time {
        display: inline-flex;
        align-items: center;
        padding: 5px 10px;
        background: #e9ecef;
        border-radius: 15px;
        font-size: 0.9rem;
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
                <li class="breadcrumb-item"><a href="{{ route('website.mcqs.test-type', $testType->slug) }}">{{ $testType->name }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('website.mcqs.subject', ['test_type' => $testType->slug, 'subject' => $subject->slug]) }}">{{ $subject->name }}</a></li>
                <li class="breadcrumb-item active">{{ $topic->title }}</li>
            </ol>
        </nav>

        <!-- Topic Header -->
        <div class="topic-header">
            <div class="container position-relative">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="h2 mb-3">{{ $topic->title }}</h1>
                        <p class="mb-0 opacity-75">{{ $topic->description }}</p>
                        <div class="d-flex align-items-center mt-3">
                            <span class="estimated-time me-3">
                                <i class="far fa-clock me-1"></i>
                                {{ $topic->estimated_time_minutes }} min
                            </span>
                            <span class="badge bg-light text-dark me-2">
                                <i class="fas fa-graduation-cap me-1"></i>{{ ucfirst($topic->difficulty_level) }}
                            </span>
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-question-circle me-1"></i>{{ $mcqs->total() }} Questions
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <a href="#start-practice" class="btn btn-light btn-lg">
                            <i class="fas fa-play me-2"></i>Start Practice
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                @if($topic->content)
                <div class="topic-content">
                    <h4 class="mb-4">Topic Overview</h4>
                    {!! $topic->content !!}
                </div>
                @endif

                <!-- Questions List -->
                <div class="card shadow-sm" id="start-practice">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Practice Questions</h5>
                        <div>
                            <span class="badge bg-primary me-2">{{ $mcqs->total() }} Questions</span>
                            @if($topic->estimated_time_minutes > 0)
                            <span class="badge bg-info text-dark">
                                {{ $topic->estimated_time_minutes }} min estimated
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        @if($mcqs->count() > 0)
                            @foreach($mcqs as $index => $mcq)
                            <div class="question-card">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 40px; height: 40px; font-weight: bold;">
                                            {{ $index + 1 }}
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-2">
                                            <a href="{{ route('website.mcqs.practice', $mcq->uuid) }}" class="text-decoration-none text-dark">
                                                {!! Str::limit(strip_tags($mcq->question), 150) !!}
                                            </a>
                                        </h6>
                                        <div class="d-flex align-items-center flex-wrap">
                                            <span class="badge {{ $mcq->difficulty_level == 'easy' ? 'bg-success' : ($mcq->difficulty_level == 'medium' ? 'bg-warning text-dark' : 'bg-danger') }} me-2 mb-1">
                                                {{ ucfirst($mcq->difficulty_level) }}
                                            </span>
                                            <span class="badge bg-light text-dark me-2 mb-1">
                                                <i class="fas fa-star me-1"></i>{{ $mcq->marks }} Mark{{ $mcq->marks > 1 ? 's' : '' }}
                                            </span>
                                            @if($mcq->time_limit_seconds)
                                            <span class="badge bg-light text-dark mb-1">
                                                <i class="far fa-clock me-1"></i>{{ ceil($mcq->time_limit_seconds / 60) }} min
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <a href="{{ route('website.mcqs.practice', $mcq->uuid) }}" class="btn btn-sm btn-outline-primary">
                                            Practice
                                        </a>
                                    </div>
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
                            <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No questions available</h5>
                            <p class="text-muted">Questions for this topic will be added soon.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Topic Stats -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Topic Statistics</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Progress</span>
                                <span>0%</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <h6 class="mb-2">Difficulty Distribution</h6>
                            <div class="difficulty-distribution d-flex">
                                @php
                                    $easyCount = $mcqs->where('difficulty_level', 'easy')->count();
                                    $mediumCount = $mcqs->where('difficulty_level', 'medium')->count();
                                    $hardCount = $mcqs->where('difficulty_level', 'hard')->count();
                                    $total = $easyCount + $mediumCount + $hardCount;
                                @endphp
                                @if($total > 0)
                                    <div class="difficulty-easy-bar" style="width: {{ ($easyCount/$total)*100 }}%"></div>
                                    <div class="difficulty-medium-bar" style="width: {{ ($mediumCount/$total)*100 }}%"></div>
                                    <div class="difficulty-hard-bar" style="width: {{ ($hardCount/$total)*100 }}%"></div>
                                @endif
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <small>Easy: {{ $easyCount }}</small>
                                <small>Medium: {{ $mediumCount }}</small>
                                <small>Hard: {{ $hardCount }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Related Topics -->
                @if($relatedTopics->count() > 0)
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-link me-2"></i>Related Topics</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            @foreach($relatedTopics as $relatedTopic)
                            <a href="{{ route('website.mcqs.topic', ['test_type' => $testType->slug, 'subject' => $subject->slug, 'topic' => $relatedTopic->slug]) }}" 
                               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span>{{ $relatedTopic->title }}</span>
                                <span class="badge bg-primary rounded-pill">{{ $relatedTopic->mcqs_count }}</span>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Quick Actions -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-3">Quick Practice</h5>
                        <button class="btn btn-primary btn-lg w-100 mb-3" onclick="startRandomPractice()">
                            <i class="fas fa-random me-2"></i>Random 10 Questions
                        </button>
                        <button class="btn btn-outline-primary btn-lg w-100 mb-3" onclick="startTimedPractice()">
                            <i class="far fa-clock me-2"></i>Timed Practice
                        </button>
                        <a href="{{ route('website.mcqs.subject', ['test_type' => $testType->slug, 'subject' => $subject->slug]) }}" 
                           class="btn btn-outline-secondary btn-lg w-100">
                            <i class="fas fa-arrow-left me-2"></i>Back to Subject
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    function startRandomPractice() {
        // Get random questions from this topic
        const questions = @json($mcqs->items());
        if (questions.length > 0) {
            const randomQuestion = questions[Math.floor(Math.random() * questions.length)];
            window.location.href = `{{ route('website.mcqs.practice', ':uuid') }}`.replace(':uuid', randomQuestion.uuid);
        }
    }

    function startTimedPractice() {
        alert('Timed practice feature coming soon!');
    }
</script>
@endpush