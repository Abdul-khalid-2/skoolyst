@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<style>
    .test-detail-header {
        background: linear-gradient(135deg, #0f4077 0%, #1e56a0 100%);
        color: white;
        padding: 60px 0;
        border-radius: 15px;
        margin-bottom: 40px;
        position: relative;
        overflow: hidden;
    }

    .test-detail-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
    }

    .test-info-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 15px;
    }

    .start-test-btn {
        background: white;
        color: #0f4077;
        border: none;
        padding: 15px 30px;
        font-size: 1.2rem;
        font-weight: 600;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .start-test-btn:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }

    .feature-list {
        list-style: none;
        padding: 0;
    }

    .feature-list li {
        padding: 10px 0;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        align-items: center;
    }

    .feature-list li:last-child {
        border-bottom: none;
    }

    .feature-list i {
        color: #28a745;
        margin-right: 10px;
        font-size: 1.2rem;
    }

    .subject-breakdown {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 15px;
    }

    .subject-item {
        background: #f8f9fa;
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 0.9rem;
    }

    .instructions-box {
        background: #f8f9fa;
        border-left: 4px solid #4361ee;
        padding: 20px;
        border-radius: 0 10px 10px 0;
        margin-bottom: 20px;
    }

    .attempt-history {
        max-height: 300px;
        overflow-y: auto;
    }

    .attempt-item {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 10px;
        transition: all 0.3s ease;
    }

    .attempt-item:hover {
        border-color: #4361ee;
        box-shadow: 0 5px 15px rgba(67, 97, 238, 0.1);
    }

    .result-badge {
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .result-passed { background: #d4edda; color: #155724; }
    .result-failed { background: #f8d7da; color: #721c24; }
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
                <li class="breadcrumb-item"><a href="{{ route('website.mcqs.mock-tests') }}">Mock Tests</a></li>
                <li class="breadcrumb-item active">{{ $mockTest->title }}</li>
            </ol>
        </nav>

        <!-- Test Header -->
        <div class="test-detail-header position-relative">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="position-relative">
                            <span class="badge bg-warning text-dark mb-3">
                                {{ $mockTest->testType->name }}
                            </span>
                            <h1 class="h2 mb-3">{{ $mockTest->title }}</h1>
                            <p class="mb-0 opacity-75">{{ $mockTest->description }}</p>
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end">
                        @if($mockTest->is_free)
                        <form action="{{ route('website.mcqs.start-mock-test', $mockTest->slug) }}" method="POST">
                            @csrf
                            <button type="submit" class="start-test-btn">
                                <i class="fas fa-play me-2"></i>Start Test
                            </button>
                        </form>
                        @else
                        <button class="start-test-btn" onclick="showPremiumAlert()">
                            <i class="fas fa-crown me-2"></i>Premium Test
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Test Details -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="test-info-card text-center">
                            <div class="h3 mb-2">{{ $mockTest->total_questions }}</div>
                            <div class="text-muted">Total Questions</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="test-info-card text-center">
                            <div class="h3 mb-2">{{ $mockTest->total_time_minutes }}</div>
                            <div class="text-muted">Total Minutes</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="test-info-card text-center">
                            <div class="h3 mb-2">{{ $mockTest->total_marks }}</div>
                            <div class="text-muted">Total Marks</div>
                        </div>
                    </div>
                </div>

                <!-- Instructions -->
                @if($mockTest->instructions)
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Test Instructions</h5>
                    </div>
                    <div class="card-body">
                        <div class="instructions-box">
                            {!! nl2br(e($mockTest->instructions)) !!}
                        </div>
                    </div>
                </div>
                @endif

                <!-- Subject Breakdown -->
                @if($mockTest->subject_breakdown)
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-layer-group me-2"></i>Subject Breakdown</h5>
                    </div>
                    <div class="card-body">
                        <div class="subject-breakdown">
                            @foreach(json_decode($mockTest->subject_breakdown, true) as $subject => $count)
                            <div class="subject-item">
                                {{ $subject }}: {{ $count }} Questions
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Features -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-star me-2"></i>Test Features</h5>
                    </div>
                    <div class="card-body">
                        <ul class="feature-list">
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <strong>Test Mode:</strong> {{ ucfirst($mockTest->test_mode->value) }}
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <strong>Passing Marks:</strong> {{ $mockTest->passing_marks }}%
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <strong>Shuffle Questions:</strong> {{ $mockTest->shuffle_questions ? 'Yes' : 'No' }}
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <strong>Show Results:</strong> {{ $mockTest->show_result_immediately ? 'Immediately' : 'After Completion' }}
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <strong>Retake Allowed:</strong> {{ $mockTest->allow_retake ? 'Yes' : 'No' }}
                                @if($mockTest->allow_retake && $mockTest->max_attempts)
                                (Max {{ $mockTest->max_attempts }} attempts)
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Test Actions -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body text-center">
                        @if($mockTest->is_free)
                            @auth
                            <form action="{{ route('website.mcqs.start-mock-test', $mockTest->slug) }}" method="POST" class="mb-3">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                                    <i class="fas fa-play me-2"></i>Start Test Now
                                </button>
                            </form>
                            @else
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg w-100 mb-3">
                                <i class="fas fa-sign-in-alt me-2"></i>Login to Start
                            </a>
                            @endauth
                        @else
                        <button class="btn btn-warning btn-lg w-100 mb-3" onclick="showPremiumAlert()">
                            <i class="fas fa-crown me-2"></i>Premium Test
                        </button>
                        @endif

                        <hr class="my-4">

                        <div class="row g-2">
                            <div class="col-6">
                                <button class="btn btn-outline-secondary w-100">
                                    <i class="far fa-bookmark me-2"></i>Save
                                </button>
                            </div>
                            <div class="col-6">
                                <button class="btn btn-outline-secondary w-100">
                                    <i class="fas fa-share-alt me-2"></i>Share
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Previous Attempts -->
                @if($previousAttempts && $previousAttempts->count() > 0)
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-history me-2"></i>Your Previous Attempts</h5>
                    </div>
                    <div class="card-body">
                        <div class="attempt-history">
                            @foreach($previousAttempts as $attempt)
                            <div class="attempt-item">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small class="text-muted">
                                        {{ $attempt->created_at->format('M d, Y') }}
                                    </small>
                                    <span class="result-badge result-{{ $attempt->result_status }}">
                                        {{ ucfirst($attempt->result_status) }}
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="h5 mb-0">{{ $attempt->percentage }}%</div>
                                        <small class="text-muted">Score</small>
                                    </div>
                                    <div>
                                        <div class="h5 mb-0">{{ $attempt->correct_answers }}/{{ $attempt->total_questions }}</div>
                                        <small class="text-muted">Correct</small>
                                    </div>
                                    <div>
                                        <div class="h5 mb-0">{{ floor($attempt->time_taken_seconds / 60) }}:{{ str_pad($attempt->time_taken_seconds % 60, 2, '0', STR_PAD_LEFT) }}</div>
                                        <small class="text-muted">Time</small>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <a href="{{ route('website.mcqs.test-result', $attempt->uuid) }}" class="btn btn-sm btn-outline-primary w-100">
                                        View Details
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Test Tips -->
                <div class="card shadow-sm mt-4">
                    <div class="card-body">
                        <h6 class="mb-3"><i class="fas fa-lightbulb me-2 text-warning"></i>Test Tips</h6>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Read questions carefully</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Manage your time wisely</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Review before submitting</li>
                            <li><i class="fas fa-check text-success me-2"></i>Stay calm and focused</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    function showPremiumAlert() {
        Swal.fire({
            title: 'Premium Test',
            text: 'This is a premium test. Please purchase a subscription to access premium content.',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'View Plans',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '/pricing';
            }
        });
    }
</script>
@endpush