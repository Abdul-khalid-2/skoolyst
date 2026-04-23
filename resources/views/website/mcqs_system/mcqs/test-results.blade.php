@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/test-results.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@endpush

@section('content')

<!-- ==================== RESULTS HERO SECTION ==================== -->
<section class="results-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-4">
                <div class="score-circle-wrapper">
                    <div class="score-circle">
                        <span>{{ $testResults['percentage'] }}%</span>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <h2>Test Completed!</h2>
                <p>{{ $topic->title }} - {{ $subject->name }}</p>
            </div>
        </div>
    </div>
</section>

<!-- ==================== STATS SECTION (Overlapping Hero) ==================== -->
<section class="results-stats-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="result-stat-card">
                    <div class="result-stat-value">{{ $testResults['obtained_marks'] }}</div>
                    <div class="result-stat-label">Marks Obtained</div>
                    <div class="result-stat-small">Out of {{ $testResults['total_marks'] }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="result-stat-card">
                    <div class="result-stat-value">{{ $testResults['correct'] }}</div>
                    <div class="result-stat-label">Correct</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="result-stat-card">
                    <div class="result-stat-value">{{ $testResults['wrong'] }}</div>
                    <div class="result-stat-label">Incorrect</div>
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
                    <li class="breadcrumb-item"><a href="{{ route('website.mcqs.subject', $subject->slug) }}">{{ $subject->name }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('website.mcqs.topic', ['subject' => $subject->slug, 'topic' => $topic->slug]) }}">{{ $topic->title }}</a></li>
                    <li class="breadcrumb-item active">Test Results</li>
                </ol>
            </nav>
        </div>

        <!-- Detailed Statistics -->
        <div class="stats-grid">
            <div class="row g-4">
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-value">{{ $testResults['total_questions'] }}</div>
                        <div class="stat-label">Total Questions</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-value">{{ $testResults['attempted'] }}</div>
                        <div class="stat-label">Attempted</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-value">{{ $testResults['skipped'] }}</div>
                        <div class="stat-label">Skipped</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-value">{{ $testResults['percentage'] }}%</div>
                        <div class="stat-label">Percentage</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Question Review -->
        <div class="review-card">
            <div class="review-header">
                <h5><i class="fas fa-clipboard-list me-2"></i>Question Review</h5>
            </div>
            <div class="review-body">
                @foreach($testResults['results'] as $index => $result)
                <div class="question-review-item {{ $result['is_correct'] ? 'correct' : 'incorrect' }}">
                    <div class="question-review-header">
                        <h6>Question {{ $index + 1 }}</h6>
                        <span class="result-badge {{ $result['is_correct'] ? 'correct' : 'incorrect' }}">
                            {{ $result['is_correct'] ? 'Correct' : 'Incorrect' }}
                        </span>
                    </div>
                    
                    <div class="question-text">
                        {!! $result['mcq']->question !!}
                    </div>
                    
                    <div class="options-review">
                        <h6>Options:</h6>
                        @php
                            $options = is_array($result['mcq']->options) ? $result['mcq']->options : json_decode($result['mcq']->options, true);
                        @endphp
                        
                        @foreach($options as $key => $option)
                            @php
                                $isCorrectAnswer = in_array($key, $result['correct_answers']);
                                $isUserAnswer = false;
                                
                                if ($result['user_answer']) {
                                    if (is_array($result['user_answer'])) {
                                        $isUserAnswer = in_array($key, $result['user_answer']);
                                    } else {
                                        $isUserAnswer = $key == $result['user_answer'];
                                    }
                                }
                            @endphp
                            
                            <div class="option-review-item 
                                {{ $isCorrectAnswer ? 'correct-answer' : '' }}
                                {{ $isUserAnswer && !$result['is_correct'] ? 'user-answer incorrect' : '' }}
                                {{ $isUserAnswer && $result['is_correct'] ? 'user-answer' : '' }}">
                                
                                <span class="option-letter">{{ chr(65 + $loop->index) }}</span>
                                <span class="option-text">{{ $option }}</span>
                                
                                @if($isCorrectAnswer)
                                    <span class="option-badge correct">Correct Answer</span>
                                @endif
                                @if($isUserAnswer && $result['is_correct'])
                                    <span class="option-badge user-correct">Your Answer ✓</span>
                                @endif
                                @if($isUserAnswer && !$result['is_correct'])
                                    <span class="option-badge user-incorrect">Your Answer ✗</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    
                    @if($result['mcq']->explanation)
                    <div class="explanation-section">
                        <h6><i class="fas fa-info-circle"></i>Explanation:</h6>
                        <p>{{ $result['mcq']->explanation }}</p>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="{{ route('website.mcqs.topic', ['subject' => $subject->slug, 'topic' => $topic->slug]) }}" class="btn btn-primary">
                <i class="fas fa-redo"></i>Practice Again
            </a>
            <a href="{{ route('website.mcqs.subject', $subject->slug) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i>Back to Subject
            </a>
        </div>
    </div>
</section>
@endsection