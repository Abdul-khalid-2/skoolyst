@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<style>
    .practice-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .question-box {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }

    .question-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: #4361ee;
        color: white;
        border-radius: 50%;
        font-weight: bold;
        margin-right: 15px;
    }

    .question-text {
        font-size: 1.2rem;
        line-height: 1.6;
        color: #333;
        margin-bottom: 30px;
    }

    .options-container {
        margin-bottom: 30px;
    }

    .option-item {
        position: relative;
        margin-bottom: 15px;
    }

    .option-label {
        display: block;
        background: #f8f9fa;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 15px 20px 15px 50px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .option-label:hover {
        background: #e9ecef;
        border-color: #4361ee;
    }

    .option-input {
        display: none;
    }

    .option-input:checked + .option-label {
        background: #4361ee;
        border-color: #4361ee;
        color: white;
    }

    .option-marker {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        width: 24px;
        height: 24px;
        border: 2px solid #dee2e6;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        background: white;
    }

    .option-input:checked + .option-label .option-marker {
        background: white;
        color: #4361ee;
        border-color: white;
    }

    .explanation-box {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-top: 20px;
        border-left: 4px solid #28a745;
        display: none;
    }

    .explanation-box.show {
        display: block;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .difficulty-badge {
        padding: 6px 15px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .timer-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px;
        border-radius: 10px;
        text-align: center;
        margin-bottom: 20px;
    }

    .timer {
        font-size: 2rem;
        font-weight: bold;
        font-family: monospace;
    }

    .navigation-buttons {
        display: flex;
        justify-content: space-between;
        margin-top: 30px;
    }

    .similar-question {
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 10px;
        transition: all 0.3s ease;
    }

    .similar-question:hover {
        border-color: #4361ee;
        background: #f8f9fa;
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
                <li class="breadcrumb-item"><a href="{{ route('website.mcqs.test-type', $mcq->testType->slug) }}">{{ $mcq->testType->name }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('website.mcqs.subject', ['test_type' => $mcq->testType->slug, 'subject' => $mcq->subject->slug]) }}">{{ $mcq->subject->name }}</a></li>
                @if($mcq->topic)
                <li class="breadcrumb-item"><a href="{{ route('website.mcqs.topic', ['test_type' => $mcq->testType->slug, 'subject' => $mcq->subject->slug, 'topic' => $mcq->topic->slug]) }}">{{ $mcq->topic->title }}</a></li>
                @endif
                <li class="breadcrumb-item active">Practice</li>
            </ol>
        </nav>

        <div class="practice-container">
            <!-- Timer (if time limited) -->
            @if($mcq->time_limit_seconds)
            <div class="timer-container">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small>Time Remaining</small>
                        <div class="timer" id="timer">00:{{ str_pad($mcq->time_limit_seconds, 2, '0', STR_PAD_LEFT) }}</div>
                    </div>
                    <button class="btn btn-light btn-sm" onclick="resetTimer()">
                        <i class="fas fa-redo"></i>
                    </button>
                </div>
            </div>
            @endif

            <!-- Question Box -->
            <div class="question-box">
                <!-- Question Header -->
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div class="d-flex align-items-center">
                        <div class="question-number">Q</div>
                        <div>
                            <span class="difficulty-badge {{ $mcq->difficulty_level == 'easy' ? 'bg-success' : ($mcq->difficulty_level == 'medium' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                {{ ucfirst($mcq->difficulty_level) }}
                            </span>
                            <span class="badge bg-light text-dark ms-2">
                                {{ $mcq->marks }} Mark{{ $mcq->marks > 1 ? 's' : '' }}
                            </span>
                        </div>
                    </div>
                    <div class="text-end">
                        <small class="text-muted d-block">{{ $mcq->subject->name }}</small>
                        @if($mcq->topic)
                        <small class="text-muted">{{ $mcq->topic->title }}</small>
                        @endif
                    </div>
                </div>

                <!-- Question Text -->
                <div class="question-text">
                    {!! $mcq->question !!}
                </div>

                <!-- Options -->
                <div class="options-container" data-question-type="{{ $mcq->question_type }}">
                    @foreach($mcq->options as $index => $option)
                    <div class="option-item">
                        <input type="{{ $mcq->question_type == 'multiple' ? 'checkbox' : 'radio' }}" 
                               id="option{{ $index }}" 
                               name="answer" 
                               value="{{ $option }}" 
                               class="option-input">
                        <label for="option{{ $index }}" class="option-label">
                            <span class="option-marker">{{ chr(65 + $index) }}</span>
                            {{ $option }}
                        </label>
                    </div>
                    @endforeach
                </div>

                <!-- Action Buttons -->
                <div class="navigation-buttons">
                    <div>
                        @if($mcq->hint)
                        <button class="btn btn-outline-info" onclick="showHint()">
                            <i class="fas fa-lightbulb me-2"></i>Show Hint
                        </button>
                        @endif
                    </div>
                    <div>
                        <button class="btn btn-secondary me-2" onclick="resetAnswer()">
                            <i class="fas fa-redo me-2"></i>Reset
                        </button>
                        <button class="btn btn-primary" onclick="checkAnswer()">
                            Check Answer <i class="fas fa-check ms-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Explanation (hidden initially) -->
                @if($mcq->explanation)
                <div class="explanation-box" id="explanationBox">
                    <h6 class="mb-3"><i class="fas fa-info-circle me-2 text-success"></i>Explanation</h6>
                    <div class="explanation-content">
                        {!! $mcq->explanation !!}
                    </div>
                    @if($mcq->reference_book)
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-book me-1"></i>
                            Reference: {{ $mcq->reference_book }}
                            @if($mcq->reference_page)
                            , Page {{ $mcq->reference_page }}
                            @endif
                        </small>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Hint (hidden initially) -->
                @if($mcq->hint)
                <div class="explanation-box" id="hintBox" style="border-left-color: #ffc107;">
                    <h6 class="mb-3"><i class="fas fa-lightbulb me-2 text-warning"></i>Hint</h6>
                    <div class="hint-content">
                        {{ $mcq->hint }}
                    </div>
                </div>
                @endif
            </div>

            <!-- Similar Questions -->
            @if($similarMcqs->count() > 0)
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-layer-group me-2"></i>Similar Questions</h5>
                </div>
                <div class="card-body">
                    @foreach($similarMcqs as $similarMcq)
                    <a href="{{ route('website.mcqs.practice', $similarMcq->uuid) }}" class="text-decoration-none">
                        <div class="similar-question">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">
                                        {!! Str::limit(strip_tags($similarMcq->question), 100) !!}
                                    </h6>
                                    <div class="d-flex align-items-center">
                                        <span class="badge {{ $similarMcq->difficulty_level == 'easy' ? 'bg-success' : ($similarMcq->difficulty_level == 'medium' ? 'bg-warning text-dark' : 'bg-danger') }} me-2">
                                            {{ ucfirst($similarMcq->difficulty_level) }}
                                        </span>
                                        <small class="text-muted">{{ $similarMcq->subject->name }}</small>
                                    </div>
                                </div>
                                <i class="fas fa-arrow-right text-primary"></i>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    let timeLimit = {{ $mcq->time_limit_seconds ?? 0 }};
    let timerInterval;
    let timeLeft = timeLimit;

    // Timer functionality
    if (timeLimit > 0) {
        startTimer();
    }

    function startTimer() {
        timerInterval = setInterval(() => {
            timeLeft--;
            updateTimerDisplay();
            
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                checkAnswer(); // Auto-submit when time's up
            }
        }, 1000);
    }

    function updateTimerDisplay() {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        document.getElementById('timer').textContent = 
            `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }

    function resetTimer() {
        clearInterval(timerInterval);
        timeLeft = timeLimit;
        updateTimerDisplay();
        startTimer();
    }

    // Answer checking
    function checkAnswer() {
        const questionType = document.querySelector('.options-container').dataset.questionType;
        const selectedOptions = [];
        
        if (questionType === 'multiple') {
            document.querySelectorAll('.option-input:checked').forEach(input => {
                selectedOptions.push(input.value);
            });
        } else {
            const checked = document.querySelector('.option-input:checked');
            if (checked) {
                selectedOptions.push(checked.value);
            }
        }

        if (selectedOptions.length === 0) {
            alert('Please select an answer first!');
            return;
        }

        // Send request to check answer
        fetch('{{ route("website.mcqs.check-answer", $mcq->uuid) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                selected_answers: selectedOptions,
                time_taken: timeLimit ? timeLimit - timeLeft : 0
            })
        })
        .then(response => response.json())
        .then(data => {
            // Show explanation
            if (data.explanation) {
                document.getElementById('explanationBox').classList.add('show');
            }
            
            // Mark correct/incorrect options
            const correctAnswers = data.correct_answers;
            document.querySelectorAll('.option-item').forEach(item => {
                const input = item.querySelector('.option-input');
                const label = item.querySelector('.option-label');
                const marker = item.querySelector('.option-marker');
                
                if (correctAnswers.includes(input.value)) {
                    label.classList.add('correct');
                    label.style.backgroundColor = '#d4edda';
                    label.style.borderColor = '#28a745';
                    label.style.color = '#155724';
                    marker.style.backgroundColor = '#28a745';
                    marker.style.color = 'white';
                } else if (selectedOptions.includes(input.value) && !data.correct) {
                    label.classList.add('incorrect');
                    label.style.backgroundColor = '#f8d7da';
                    label.style.borderColor = '#dc3545';
                    label.style.color = '#721c24';
                    marker.style.backgroundColor = '#dc3545';
                    marker.style.color = 'white';
                }
            });

            // Disable all inputs
            document.querySelectorAll('.option-input').forEach(input => {
                input.disabled = true;
            });
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error checking answer. Please try again.');
        });
    }

    function resetAnswer() {
        // Clear all selections
        document.querySelectorAll('.option-input').forEach(input => {
            input.checked = false;
            input.disabled = false;
        });
        
        // Reset styles
        document.querySelectorAll('.option-label').forEach(label => {
            label.classList.remove('correct', 'incorrect');
            label.style.backgroundColor = '';
            label.style.borderColor = '';
            label.style.color = '';
        });
        
        document.querySelectorAll('.option-marker').forEach(marker => {
            marker.style.backgroundColor = '';
            marker.style.color = '';
        });
        
        // Hide explanation
        document.getElementById('explanationBox')?.classList.remove('show');
        document.getElementById('hintBox')?.classList.remove('show');
        
        // Reset timer if exists
        if (timeLimit > 0) {
            resetTimer();
        }
    }

    function showHint() {
        document.getElementById('hintBox').classList.toggle('show');
    }
</script>
@endpush