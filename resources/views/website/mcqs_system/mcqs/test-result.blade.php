@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<style>
    .result-header {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        padding: 60px 0;
        border-radius: 15px;
        margin-bottom: 40px;
        position: relative;
        overflow: hidden;
    }

    .result-header.failed {
        background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);
    }

    .percentage-circle {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        border: 10px solid rgba(255, 255, 255, 0.3);
        position: relative;
        margin: 0 auto;
    }

    .percentage-value {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 2.5rem;
        font-weight: bold;
    }

    .result-stats {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }

    .stat-card {
        text-align: center;
        padding: 20px;
        border-radius: 10px;
        background: #f8f9fa;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .stat-card.correct { border-top: 4px solid #28a745; }
    .stat-card.wrong { border-top: 4px solid #dc3545; }
    .stat-card.skipped { border-top: 4px solid #6c757d; }
    .stat-card.time { border-top: 4px solid #17a2b8; }

    .stat-number {
        font-size: 2.5rem;
        font-weight: bold;
        display: block;
    }

    .stat-label {
        color: #6c757d;
        font-size: 0.9rem;
    }

    .performance-chart {
        height: 300px;
        margin: 30px 0;
    }

    .question-review {
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
    }

    .question-review.correct {
        border-left: 4px solid #28a745;
    }

    .question-review.incorrect {
        border-left: 4px solid #dc3545;
    }

    .question-review.skipped {
        border-left: 4px solid #6c757d;
    }

    .answer-status {
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-left: 10px;
    }

    .status-correct { background: #d4edda; color: #155724; }
    .status-incorrect { background: #f8d7da; color: #721c24; }
    .status-skipped { background: #e9ecef; color: #495057; }

    .share-buttons {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .share-btn {
        flex: 1;
        padding: 10px;
        border-radius: 8px;
        border: none;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .share-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .share-btn.facebook { background: #3b5998; }
    .share-btn.twitter { background: #1da1f2; }
    .share-btn.whatsapp { background: #25d366; }
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
                <li class="breadcrumb-item"><a href="{{ route('website.mcqs.mock-test-detail', $attempt->mockTest->slug) }}">{{ $attempt->mockTest->title }}</a></li>
                <li class="breadcrumb-item active">Test Result</li>
            </ol>
        </nav>

        <!-- Result Header -->
        <div class="result-header {{ $attempt->result_status === 'passed' ? '' : 'failed' }}">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-4 text-center">
                        <div class="percentage-circle">
                            <div class="percentage-value">{{ number_format($attempt->percentage, 1) }}%</div>
                        </div>
                        <h4 class="mt-3 mb-0">{{ $attempt->result_status === 'passed' ? 'PASSED' : 'FAILED' }}</h4>
                    </div>
                    <div class="col-md-8">
                        <h1 class="h2 mb-3">{{ $attempt->mockTest->title }}</h1>
                        <p class="mb-4 opacity-75">Test completed on {{ $attempt->completed_at->format('F d, Y h:i A') }}</p>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-check-circle fa-2x me-3"></i>
                                    <div>
                                        <div class="h4 mb-0">{{ $attempt->correct_answers }}</div>
                                        <small>Correct Answers</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-clock fa-2x me-3"></i>
                                    <div>
                                        <div class="h4 mb-0">{{ floor($attempt->time_taken_seconds / 60) }}:{{ str_pad($attempt->time_taken_seconds % 60, 2, '0', STR_PAD_LEFT) }}</div>
                                        <small>Time Taken</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="result-stats">
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="stat-card correct">
                        <span class="stat-number">{{ $attempt->correct_answers }}</span>
                        <span class="stat-label">Correct</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card wrong">
                        <span class="stat-number">{{ $attempt->wrong_answers }}</span>
                        <span class="stat-label">Wrong</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card skipped">
                        <span class="stat-number">{{ $attempt->skipped_questions }}</span>
                        <span class="stat-label">Skipped</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card time">
                        <span class="stat-number">{{ floor($attempt->time_taken_seconds / 60) }}</span>
                        <span class="stat-label">Minutes</span>
                    </div>
                </div>
            </div>

            <!-- Score Breakdown -->
            <div class="row mt-5">
                <div class="col-md-6">
                    <h5 class="mb-3">Score Details</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td>Total Marks Obtained</td>
                                    <td class="text-end">{{ $attempt->total_marks_obtained }}/{{ $attempt->total_possible_marks }}</td>
                                </tr>
                                <tr>
                                    <td>Percentage</td>
                                    <td class="text-end">{{ number_format($attempt->percentage, 2) }}%</td>
                                </tr>
                                <tr>
                                    <td>Passing Percentage</td>
                                    <td class="text-end">{{ $attempt->mockTest->passing_marks }}%</td>
                                </tr>
                                <tr>
                                    <td>Result</td>
                                    <td class="text-end">
                                        <span class="badge {{ $attempt->result_status === 'passed' ? 'bg-success' : 'bg-danger' }}">
                                            {{ ucfirst($attempt->result_status) }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-6">
                    <h5 class="mb-3">Time Analysis</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td>Total Time Allotted</td>
                                    <td class="text-end">{{ $attempt->mockTest->total_time_minutes }} minutes</td>
                                </tr>
                                <tr>
                                    <td>Time Taken</td>
                                    <td class="text-end">
                                        {{ floor($attempt->time_taken_seconds / 60) }}:{{ str_pad($attempt->time_taken_seconds % 60, 2, '0', STR_PAD_LEFT) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Time Saved</td>
                                    <td class="text-end">
                                        @php
                                            $total_allotted = $attempt->mockTest->total_time_minutes * 60;
                                            $time_saved = $total_allotted - $attempt->time_taken_seconds;
                                        @endphp
                                        {{ floor($time_saved / 60) }}:{{ str_pad($time_saved % 60, 2, '0', STR_PAD_LEFT) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Average Time per Question</td>
                                    <td class="text-end">
                                        @php
                                            $avg_time = $attempt->attempted_questions > 0 ? 
                                                floor($attempt->time_taken_seconds / $attempt->attempted_questions) : 0;
                                        @endphp
                                        {{ $avg_time }} seconds
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Question Review -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-list-check me-2"></i>Question Review</h5>
            </div>
            <div class="card-body">
                @if($attempt->answers_data)
                    @foreach($attempt->answers_data as $index => $answer)
                    <div class="question-review {{ $answer['is_correct'] ? 'correct' : 'incorrect' }}">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h6 class="mb-0">Question {{ $index + 1 }}</h6>
                            <span class="answer-status status-{{ $answer['is_correct'] ? 'correct' : 'incorrect' }}">
                                {{ $answer['is_correct'] ? 'Correct' : 'Incorrect' }}
                            </span>
                        </div>
                        
                        <div class="mb-3">
                            <small class="text-muted">Your Answer:</small>
                            <div class="mt-1">
                                @if(is_array($answer['selected_answers']))
                                    @foreach($answer['selected_answers'] as $selected)
                                    <span class="badge bg-info me-2">{{ $selected }}</span>
                                    @endforeach
                                @else
                                    <span class="badge bg-info">{{ $answer['selected_answers'] }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">Correct Answer:</small>
                            <div class="mt-1">
                                @if(is_array($answer['correct_answers']))
                                    @foreach($answer['correct_answers'] as $correct)
                                    <span class="badge bg-success me-2">{{ $correct }}</span>
                                    @endforeach
                                @else
                                    <span class="badge bg-success">{{ $answer['correct_answers'] }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                Marks: 
                                <span class="{{ $answer['marks'] > 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $answer['marks'] > 0 ? '+' : '' }}{{ $answer['marks'] }}
                                </span>
                            </small>
                            <button class="btn btn-sm btn-outline-primary" onclick="showExplanation({{ $index }})">
                                View Explanation
                            </button>
                        </div>
                    </div>
                    @endforeach
                @else
                <div class="text-center py-4">
                    <i class="fas fa-exclamation-circle fa-2x text-muted mb-3"></i>
                    <p class="text-muted">No answer data available</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Share Results -->
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h5 class="mb-4">Share Your Results</h5>
                <div class="share-buttons">
                    <button class="share-btn facebook" onclick="shareOnFacebook()">
                        <i class="fab fa-facebook-f me-2"></i>Facebook
                    </button>
                    <button class="share-btn twitter" onclick="shareOnTwitter()">
                        <i class="fab fa-twitter me-2"></i>Twitter
                    </button>
                    <button class="share-btn whatsapp" onclick="shareOnWhatsApp()">
                        <i class="fab fa-whatsapp me-2"></i>WhatsApp
                    </button>
                </div>
                <div class="mt-4">
                    <a href="{{ route('website.mcqs.mock-test-detail', $attempt->mockTest->slug) }}" class="btn btn-primary me-3">
                        <i class="fas fa-redo me-2"></i>Retake Test
                    </a>
                    <a href="{{ route('website.mcqs.mock-tests') }}" class="btn btn-outline-primary">
                        <i class="fas fa-list me-2"></i>Browse More Tests
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    function shareOnFacebook() {
        const url = encodeURIComponent(window.location.href);
        const text = encodeURIComponent(`I scored ${parseFloat("{{ $attempt->percentage }}").toFixed(1)}% on "${encodeURIComponent("{{ $attempt->mockTest->title }}")}" test!`);
        window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}&quote=${text}`, '_blank');
    }

    function shareOnTwitter() {
        const url = encodeURIComponent(window.location.href);
        const text = encodeURIComponent(`I scored ${parseFloat("{{ $attempt->percentage }}").toFixed(1)}% on "{{ $attempt->mockTest->title }}" test!`);
        window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank');
    }

    function shareOnWhatsApp() {
        const text = encodeURIComponent(`I scored ${parseFloat("{{ $attempt->percentage }}").toFixed(1)}% on "{{ $attempt->mockTest->title }}" test! Check it out: ${window.location.href}`);
        window.open(`https://wa.me/?text=${text}`, '_blank');
    }

    function showExplanation(questionIndex) {
        // Implement logic to fetch and show question explanation
        Swal.fire({
            title: 'Question Explanation',
            text: 'Detailed explanation would appear here...',
            icon: 'info',
            confirmButtonText: 'OK'
        });
    }
</script>
@endpush