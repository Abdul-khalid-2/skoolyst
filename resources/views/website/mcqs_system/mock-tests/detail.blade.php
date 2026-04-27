@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<style>
    .test-detail-hero {
        background: linear-gradient(135deg, #1a2980 0%, #26d0ce 100%);
        color: white;
        padding: 60px 0;
    }
    
    .test-info-card {
        background: white;
        border-radius: 10px;
        padding: 30px;
        margin-bottom: 20px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    
    .info-item {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }
    
    .info-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #4361ee;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
    }
    
    .attempt-history {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-top: 20px;
    }
    
    .attempt-item {
        padding: 10px 0;
        border-bottom: 1px solid #dee2e6;
    }
    
    .attempt-item:last-child {
        border-bottom: none;
    }
    
    .question-preview {
        background: white;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 15px;
        border: 1px solid #eaeaea;
    }
    
    .preview-options {
        margin-top: 10px;
    }
    
    .preview-option {
        padding: 8px 15px;
        background: #f8f9fa;
        border-radius: 5px;
        margin-bottom: 5px;
        font-size: 0.9rem;
    }
</style>
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@endpush

@section('content')

<!-- Hero Section -->
<section class="test-detail-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-light">
                        <li class="breadcrumb-item"><a href="{{ route('website.mcqs.index') }}">MCQs</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('website.mcqs.mock-tests') }}">Mock Tests</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $mockTest->title }}</li>
                    </ol>
                </nav>
                
                <h1 class="display-5 fw-bold mb-3">{{ $mockTest->title }}</h1>
                <p class="lead mb-4">{{ $mockTest->description }}</p>
                
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge bg-light text-dark py-2 px-3">
                        <i class="fas fa-graduation-cap me-1"></i>{{ $mockTest->testType->name }}
                    </span>
                    <span class="badge bg-light text-dark py-2 px-3">
                        <i class="fas fa-clock me-1"></i>{{ $timeStats['hours'] }}h {{ $timeStats['minutes'] }}m
                    </span>
                    <span class="badge bg-light text-dark py-2 px-3">
                        <i class="fas fa-question-circle me-1"></i>{{ $mockTest->total_questions }} Questions
                    </span>
                    @if($mockTest->is_free)
                        <span class="badge bg-success py-2 px-3">Free</span>
                    @else
                        <span class="badge bg-warning text-dark py-2 px-3">Premium</span>
                    @endif
                </div>
            </div>
            
            <div class="col-md-4 text-md-end">
                @if($hasActiveAttempt)
                    <a href="{{ route('website.mcqs.take-test', $activeAttempt->uuid) }}" class="btn btn-light btn-lg px-5">
                        <i class="fas fa-play-circle me-2"></i>Continue Test
                    </a>
                @else
                    <!-- Change form to link since it's GET -->
                    <a href="{{ route('website.mcqs.start-mock-test', $mockTest) }}" class="btn btn-light btn-lg px-5">
                        <i class="fas fa-play-circle me-2"></i>Start Test
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Test Details -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Left Column - Test Info -->
            <div class="col-lg-8">
                <div class="test-info-card">
                    <h3 class="mb-4">Test Information</h3>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-question-circle"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Total Questions</small>
                                    <strong>{{ $mockTest->total_questions }}</strong>
                                </div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Total Time</small>
                                    <strong>{{ $timeStats['hours'] }} hours {{ $timeStats['minutes'] }} minutes</strong>
                                </div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-star"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Total Marks</small>
                                    <strong>{{ $mockTest->total_marks }}</strong>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-trophy"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Passing Marks</small>
                                    <strong>{{ $mockTest->passing_marks }}%</strong>
                                </div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-bullseye"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Test Mode</small>
                                    <strong>{{ ucfirst($mockTest->test_mode->value) }}</strong>
                                </div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-redo"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Max Attempts</small>
                                    <strong>{{ $mockTest->max_attempts ?? 'Unlimited' }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($mockTest->instructions)
                    <div class="mt-4">
                        <h5 class="mb-3">Instructions</h5>
                        <div class="bg-light p-3 rounded">
                            {!! nl2br(e($mockTest->instructions)) !!}
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Question Preview -->
                <div class="test-info-card">
                    <h3 class="mb-4">Question Preview</h3>
                    <p class="text-muted mb-4">Here's a preview of what type of questions you'll encounter:</p>
                    
                    @foreach($mockTest->questions->take(3) as $question)
                    <div class="question-preview">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="badge bg-primary">Question {{ $question->question_number }}</span>
                            <span class="badge bg-light text-dark">{{ $question->marks }} Mark{{ $question->marks > 1 ? 's' : '' }}</span>
                        </div>
                        
                        <h6 class="mb-3">{!! Str::limit(strip_tags($question->mcq->question), 200) !!}</h6>
                        
                        <div class="preview-options">
                            @if(is_array($question->mcq->options))
                                @foreach(array_slice($question->mcq->options, 0, 4) as $key => $option)
                                <div class="preview-option">
                                    {{ $key }}. {{ Str::limit($option, 100) }}
                                </div>
                                @endforeach
                            @endif
                        </div>
                        
                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="fas fa-book me-1"></i>{{ $question->mcq->subject->name }} | 
                                <i class="fas fa-folder me-1"></i>{{ $question->mcq->topic->title ?? 'No Topic' }}
                            </small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Right Column - Actions & History -->
            <div class="col-lg-4">
                <!-- Start Test Card -->
                <div class="test-info-card text-center">
                    @if(Auth::check())
                        @if($hasActiveAttempt)
                            <div class="mb-4">
                                <i class="fas fa-play-circle fa-4x text-primary mb-3"></i>
                                <h4>Continue Test</h4>
                                <p class="text-muted">You have an active attempt in progress</p>
                            </div>
                            <a href="{{ route('website.mcqs.take-test', $activeAttempt->uuid) }}" 
                               class="btn btn-primary btn-lg w-100 mb-3">
                                <i class="fas fa-play-circle me-2"></i>Continue Test
                            </a>
                        @else
                                <div class="mb-4">
                                    <i class="fas fa-play-circle fa-4x text-primary mb-3"></i>
                                    <h4>Start Test</h4>
                                    <p class="text-muted">Begin your test now</p>
                                </div>
                                <a href="{{ route('website.mcqs.start-mock-test', $mockTest) }}" class="btn btn-primary btn-lg w-100 mb-3">
                                    <i class="fas fa-play-circle me-2"></i>Start Test Now
                                </a>
                        @endif
                        
                        @if($previousAttempts && $previousAttempts->count() > 0)
                            <div class="mt-4 pt-4 border-top">
                                <a href="{{ route('website.mcqs.test-result', $previousAttempts->first()->uuid) }}" 
                                   class="btn btn-outline-primary w-100">
                                    <i class="fas fa-chart-bar me-2"></i>View Last Result
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="mb-4">
                            <i class="fas fa-sign-in-alt fa-4x text-primary mb-3"></i>
                            <h4>Login Required</h4>
                            <p class="text-muted">Please login to take this test</p>
                        </div>
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg w-100 mb-3">
                            <i class="fas fa-sign-in-alt me-2"></i>Login to Start
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-user-plus me-2"></i>Create Account
                        </a>
                    @endif
                </div>
                
                <!-- Attempt History -->
                @if(Auth::check() && $previousAttempts && $previousAttempts->count() > 0)
                <div class="test-info-card">
                    <h5 class="mb-4">Your Previous Attempts</h5>
                    
                    <div class="attempt-history">
                        @foreach($previousAttempts as $attempt)
                        <div class="attempt-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted d-block">{{ $attempt->created_at->format('M d, Y') }}</small>
                                    <strong class="{{ $attempt->result_status == 'passed' ? 'text-success' : 'text-danger' }}">
                                        {{ number_format($attempt->percentage, 1) }}%
                                    </strong>
                                    <small class="ms-2">({{ $attempt->correct_answers }}/{{ $attempt->total_questions }})</small>
                                </div>
                                <a href="{{ route('website.mcqs.test-result', $attempt->uuid) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    View
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    @if($previousAttempts->count() == 5)
                    <div class="text-center mt-3">
                        <a href="#" class="text-decoration-none">View All Attempts</a>
                    </div>
                    @endif
                </div>
                @endif
                
                <!-- Test Features -->
                <div class="test-info-card">
                    <h5 class="mb-4">Test Features</h5>
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            {{ $mockTest->shuffle_questions ? 'Questions shuffled randomly' : 'Questions in fixed order' }}
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            {{ $mockTest->show_result_immediately ? 'Instant results after submission' : 'Results reviewed manually' }}
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            {{ $mockTest->allow_retake ? 'Multiple attempts allowed' : 'Single attempt only' }}
                        </li>
                        <li>
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Detailed answer explanations
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection