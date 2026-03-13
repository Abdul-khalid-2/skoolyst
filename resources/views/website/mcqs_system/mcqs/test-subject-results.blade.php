@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/test-results.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
<style>
    /* Additional styles for subject results */
    .breadcrumb-wrapper {
        margin-bottom: 20px;
    }
    .breadcrumb {
        background: transparent;
        padding: 0;
    }
    .breadcrumb-item a {
        color: #007bff;
        text-decoration: none;
    }
    .breadcrumb-item.active {
        color: #6c757d;
    }
    .test-info {
        background: #f8f9fa;
        padding: 15px 20px;
        border-radius: 10px;
        margin-bottom: 30px;
        border-left: 4px solid #007bff;
    }
    .test-info p {
        margin: 5px 0;
        color: #495057;
    }
    .test-info i {
        color: #007bff;
        width: 20px;
        margin-right: 8px;
    }
    .score-circle-wrapper {
        text-align: center;
    }
    .score-circle {
        width: 150px;
        height: 150px;
        margin: 0 auto;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2.5rem;
        font-weight: bold;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }
    .result-stat-card {
        background: white;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        text-align: center;
        height: 100%;
    }
    .result-stat-value {
        font-size: 2rem;
        font-weight: bold;
        color: #333;
        line-height: 1.2;
    }
    .result-stat-label {
        color: #666;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-top: 5px;
    }
    .result-stat-small {
        color: #999;
        font-size: 0.8rem;
        margin-top: 5px;
    }
    .stats-grid {
        margin-top: 40px;
        margin-bottom: 40px;
    }
    .stat-card {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        text-align: center;
        border: 1px solid #e9ecef;
        transition: transform 0.3s;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .stat-value {
        font-size: 1.8rem;
        font-weight: bold;
        color: #007bff;
    }
    .stat-label {
        color: #666;
        font-size: 0.9rem;
        margin-top: 5px;
    }
    .review-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        margin-top: 30px;
        overflow: hidden;
    }
    .review-header {
        background: #f8f9fa;
        padding: 15px 20px;
        border-bottom: 2px solid #007bff;
    }
    .review-header h5 {
        margin: 0;
        color: #333;
    }
    .review-body {
        padding: 20px;
    }
    .question-review-item {
        margin-bottom: 30px;
        padding: 20px;
        border-radius: 8px;
        background: #f8f9fa;
        border-left: 4px solid;
    }
    .question-review-item.correct {
        border-left-color: #28a745;
    }
    .question-review-item.incorrect {
        border-left-color: #dc3545;
    }
    .question-review-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    .question-review-header h6 {
        margin: 0;
        font-weight: bold;
        color: #333;
    }
    .result-badge {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    .result-badge.correct {
        background: #d4edda;
        color: #155724;
    }
    .result-badge.incorrect {
        background: #f8d7da;
        color: #721c24;
    }
    .question-text {
        background: white;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 15px;
        border: 1px solid #dee2e6;
    }
    .options-review {
        margin-bottom: 15px;
    }
    .options-review h6 {
        margin-bottom: 10px;
        color: #555;
    }
    .option-review-item {
        padding: 10px 15px;
        margin-bottom: 5px;
        border-radius: 5px;
        background: white;
        border: 1px solid #dee2e6;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }
    .option-review-item.correct-answer {
        background: #d4edda;
        border-color: #c3e6cb;
    }
    .option-review-item.user-answer.incorrect {
        background: #f8d7da;
        border-color: #f5c6cb;
    }
    .option-review-item.user-answer {
        border: 2px solid #007bff;
    }
    .option-letter {
        font-weight: bold;
        width: 25px;
        height: 25px;
        background: #007bff;
        color: white;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 0.8rem;
    }
    .option-text {
        flex: 1;
    }
    .option-badge {
        padding: 3px 8px;
        border-radius: 3px;
        font-size: 0.7rem;
        font-weight: 600;
    }
    .option-badge.correct {
        background: #28a745;
        color: white;
    }
    .option-badge.user-correct {
        background: #007bff;
        color: white;
    }
    .option-badge.user-incorrect {
        background: #dc3545;
        color: white;
    }
    .explanation-section {
        background: #fff3cd;
        padding: 15px;
        border-radius: 5px;
        margin-top: 10px;
        border-left: 4px solid #ffc107;
    }
    .explanation-section h6 {
        color: #856404;
        margin-bottom: 8px;
    }
    .explanation-section p {
        margin: 0;
        color: #856404;
    }
    .action-buttons {
        text-align: center;
        margin-top: 40px;
        margin-bottom: 20px;
    }
    .action-buttons .btn {
        margin: 0 10px;
        padding: 12px 30px;
        border-radius: 25px;
        font-weight: 600;
    }
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }
    .btn-outline-secondary {
        border: 2px solid #6c757d;
        color: #6c757d;
    }
    .btn-outline-secondary:hover {
        background: #6c757d;
        color: white;
    }
</style>
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
                <h3>{{ $subject->name }}</h3>
                @if($topic)
                    <p class="text-muted">Topic: {{ $topic->title }}</p>
                @else
                    <p class="text-muted">Subject Level Test</p>
                @endif
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
                    <li class="breadcrumb-item"><a href="{{ route('website.mcqs.subject-by-test-type', [$testType->slug, $subject->slug]) }}">{{ $subject->name }}</a></li>
                    @if($topic)
                        <li class="breadcrumb-item"><a href="{{ route('website.mcqs.topic', ['subject' => $subject->slug, 'topic' => $topic->slug]) }}">{{ $topic->title }}</a></li>
                    @endif
                    <li class="breadcrumb-item active">Test Results</li>
                </ol>
            </nav>
        </div>

        <!-- Test Info -->
        <div class="test-info">
            <p><i class="fas fa-clock"></i> <strong>Time Taken:</strong> {{ $testResults['time_taken'] ?? 'N/A' }} seconds</p>
            @if(isset($testResults['test_type_id']) && $testResults['test_type_id'])
                <p><i class="fas fa-tag"></i> <strong>Test Type:</strong> {{ \App\Models\TestType::find($testResults['test_type_id'])?->name ?? 'Standard' }}</p>
            @endif
            <p><i class="fas fa-calendar"></i> <strong>Completed:</strong> {{ now()->format('F j, Y, g:i a') }}</p>
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

        <!-- Additional Stats -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="stat-card">
                    <div class="stat-value">{{ $testResults['accuracy'] ?? 0 }}%</div>
                    <div class="stat-label">Accuracy</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="stat-card">
                    <div class="stat-value">{{ $testResults['negative_marks'] ?? 0 }}</div>
                    <div class="stat-label">Negative Marks</div>
                </div>
            </div>
        </div>

        <!-- Question Review -->
        <div class="review-card">
            <div class="review-header">
                <h5><i class="fas fa-clipboard-list me-2"></i>Question Review</h5>
            </div>
            <div class="review-body">
                @forelse($testResults['results'] as $index => $result)
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
                                
                                <span class="option-letter">{{ chr(65 + $key) }}</span>
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
                        <h6><i class="fas fa-info-circle"></i> Explanation:</h6>
                        <p>{{ $result['mcq']->explanation }}</p>
                    </div>
                    @endif
                </div>
                @empty
                <div class="text-center py-4">
                    <p class="text-muted">No questions to display.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            @if($topic)
                <a href="{{ route('website.mcqs.topic', ['subject' => $subject->slug, 'topic' => $topic->slug]) }}" class="btn btn-primary">
                    <i class="fas fa-redo"></i> Practice Again
                </a>
            @else
                <a href="{{ route('website.mcqs.subject', $subject->slug) }}?mode=practice" class="btn btn-primary">
                    <i class="fas fa-redo"></i> Take Another Test
                </a>
            @endif
            <a href="{{ route('website.mcqs.subject', $subject->slug) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Subject
            </a>
        </div>
    </div>
</section>
@endsection