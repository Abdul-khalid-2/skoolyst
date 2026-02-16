@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<style>
    .result-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 40px;
        border-radius: 15px;
        margin-bottom: 30px;
    }

    .score-circle {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        font-size: 2.5rem;
        font-weight: bold;
        border: 4px solid white;
    }

    .stat-box {
        background: white;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        height: 100%;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: bold;
        color: #333;
    }

    .stat-label {
        color: #666;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .question-review {
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 15px;
        background: white;
    }

    .question-review.correct {
        border-left: 4px solid #28a745;
    }

    .question-review.incorrect {
        border-left: 4px solid #dc3545;
    }

    .option-review {
        padding: 10px;
        margin-bottom: 5px;
        border-radius: 5px;
    }

    .option-review.correct-answer {
        background: #d4edda;
        border: 1px solid #c3e6cb;
    }

    .option-review.user-answer {
        background: #cce5ff;
        border: 1px solid #b8daff;
    }

    .option-review.user-answer.incorrect {
        background: #f8d7da;
        border: 1px solid #f5c6cb;
    }

    .badge-correct {
        background: #28a745;
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 0.8rem;
    }

    .badge-incorrect {
        background: #dc3545;
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 0.8rem;
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
                <li class="breadcrumb-item"><a href="{{ route('website.mcqs.subject', $subject->slug) }}">{{ $subject->name }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('website.mcqs.topic', ['subject' => $subject->slug, 'topic' => $topic->slug]) }}">{{ $topic->title }}</a></li>
                <li class="breadcrumb-item active">Test Results</li>
            </ol>
        </nav>

        <!-- Results Header -->
        <div class="result-card">
            <div class="row align-items-center">
                <div class="col-md-4 text-center">
                    <div class="score-circle">
                        {{ $testResults['percentage'] }}%
                    </div>
                </div>
                <div class="col-md-8">
                    <h2 class="mb-3">Test Completed!</h2>
                    <p class="mb-4">{{ $topic->title }} - {{ $subject->name }}</p>
                    <div class="row">
                        <div class="col-4">
                            <div class="stat-box">
                                <div class="stat-value">{{ $testResults['obtained_marks'] }}</div>
                                <div class="stat-label">Marks Obtained</div>
                                <small>Out of {{ $testResults['total_marks'] }}</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-box">
                                <div class="stat-value">{{ $testResults['correct'] }}</div>
                                <div class="stat-label">Correct</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-box">
                                <div class="stat-value">{{ $testResults['wrong'] }}</div>
                                <div class="stat-label">Incorrect</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-box">
                    <div class="stat-value">{{ $testResults['total_questions'] }}</div>
                    <div class="stat-label">Total Questions</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-box">
                    <div class="stat-value">{{ $testResults['attempted'] }}</div>
                    <div class="stat-label">Attempted</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-box">
                    <div class="stat-value">{{ $testResults['skipped'] }}</div>
                    <div class="stat-label">Skipped</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-box">
                    <div class="stat-value">{{ $testResults['percentage'] }}%</div>
                    <div class="stat-label">Percentage</div>
                </div>
            </div>
        </div>

        <!-- Question Review -->
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Question Review</h5>
            </div>
            <div class="card-body">
                @foreach($testResults['results'] as $index => $result)
                <div class="question-review {{ $result['is_correct'] ? 'correct' : 'incorrect' }}">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h6>Question {{ $index + 1 }}</h6>
                        <span class="badge {{ $result['is_correct'] ? 'badge-correct' : 'badge-incorrect' }}">
                            {{ $result['is_correct'] ? 'Correct' : 'Incorrect' }}
                        </span>
                    </div>
                    
                    <p class="mb-3">{!! $result['mcq']->question !!}</p>
                    
                    <div class="options-review mb-3">
                        <h6 class="mb-2">Options:</h6>
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
                            
                            <div class="option-review 
                                {{ $isCorrectAnswer ? 'correct-answer' : '' }}
                                {{ $isUserAnswer && !$result['is_correct'] && $isUserAnswer ? 'user-answer incorrect' : '' }}
                                {{ $isUserAnswer && $result['is_correct'] && $isUserAnswer ? 'user-answer' : '' }}">
                                <strong>{{ chr(65 + $key) }}.</strong> {{ $option }}
                                @if($isCorrectAnswer)
                                    <span class="badge bg-success text-white float-end">Correct Answer</span>
                                @endif
                                @if($isUserAnswer && $result['is_correct'])
                                    <span class="badge bg-info text-white float-end">Your Answer ✓</span>
                                @endif
                                @if($isUserAnswer && !$result['is_correct'])
                                    <span class="badge bg-danger text-white float-end">Your Answer ✗</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    
                    @if($result['mcq']->explanation)
                    <div class="explanation mt-3 p-3 bg-light rounded">
                        <h6><i class="fas fa-info-circle me-2"></i>Explanation:</h6>
                        <p class="mb-0">{{ $result['mcq']->explanation }}</p>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="text-center mt-4">
            <a href="{{ route('website.mcqs.topic', ['subject' => $subject->slug, 'topic' => $topic->slug]) }}" class="btn btn-primary btn-lg me-2">
                <i class="fas fa-redo me-2"></i>Practice Again
            </a>
            <a href="{{ route('website.mcqs.subject', $subject->slug) }}" class="btn btn-outline-secondary btn-lg">
                <i class="fas fa-arrow-left me-2"></i>Back to Subject
            </a>
        </div>
    </div>
</section>
@endsection