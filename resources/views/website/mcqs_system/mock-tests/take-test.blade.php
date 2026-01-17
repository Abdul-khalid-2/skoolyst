@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<style>
    .test-taking-container {
        min-height: calc(100vh - 200px);
    }
    
    .test-header {
        background: white;
        padding: 15px 0;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        position: sticky;
        top: 0;
        z-index: 1000;
    }
    
    .time-display {
        font-family: 'Courier New', monospace;
        font-size: 1.5rem;
        font-weight: bold;
        color: #dc3545;
    }
    
    .question-sidebar {
        position: fixed;
        right: 20px;
        top: 100px;
        width: 300px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        padding: 20px;
        max-height: calc(100vh - 150px);
        overflow-y: auto;
    }
    
    .question-nav-btn {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 2px;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .question-nav-btn.answered {
        background: #28a745;
        color: white;
    }
    
    .question-nav-btn.current {
        background: #4361ee;
        color: white;
    }
    
    .question-nav-btn.not-visited {
        background: #f8f9fa;
        color: #495057;
    }
    
    .question-container {
        background: white;
        border-radius: 10px;
        padding: 30px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .option-card {
        background: #f8f9fa;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .option-card:hover {
        background: #e9ecef;
        border-color: #4361ee;
    }
    
    .option-card.selected {
        background: #4361ee;
        color: white;
        border-color: #4361ee;
    }
    
    .option-card.multiple-selected {
        background: #6c757d;
        color: white;
        border-color: #6c757d;
    }
    
    .action-buttons {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: white;
        padding: 15px;
        box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
        z-index: 1000;
    }
    
    .question-text {
        font-size: 1.1rem;
        line-height: 1.6;
        color: #333;
    }
    
    @media (max-width: 768px) {
        .question-sidebar {
            display: none;
        }
    }
</style>
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@endpush

@section('content')

<!-- Test Header -->
<div class="test-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-4">
                <h5 class="mb-0">{{ $attempt->mockTest->title }}</h5>
                <small class="text-muted">Test ID: {{ $attempt->uuid }}</small>
            </div>
            
            <div class="col-md-4 text-center">
                <div class="time-display" id="timer">
                    {{ floor($remainingSeconds / 3600) }}:{{ floor(($remainingSeconds % 3600) / 60) }}:{{ $remainingSeconds % 60 }}
                </div>
                <small class="text-muted">Time Remaining</small>
            </div>
            
            <div class="col-md-4 text-end">
                <div class="d-flex align-items-center justify-content-end gap-2">
                    <span class="badge bg-light text-dark">
                        Q: <span id="current-question">1</span>/{{ $attempt->total_questions }}
                    </span>
                    <span class="badge bg-success">
                        <span id="answered-count">{{ $attempt->attempted_questions }}</span> Answered
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="test-taking-container">
    <div class="container py-5">
        <div class="row">
            <!-- Main Question Area -->
            <div class="col-lg-8">
                <div id="question-area">
                    <!-- Questions will be loaded here dynamically -->
                </div>
            </div>
            
            <!-- Question Navigation Sidebar -->
            <div class="col-lg-4">
                <div class="question-sidebar d-none d-lg-block">
                    <h6 class="mb-3">Question Navigation</h6>
                    <div class="row g-1" id="question-navigation">
                        @for($i = 1; $i <= $attempt->total_questions; $i++)
                            <div class="col-2">
                                <div class="question-nav-btn not-visited" 
                                     data-question="{{ $i }}" 
                                     onclick="goToQuestion({{ $i }})">
                                    {{ $i }}
                                </div>
                            </div>
                        @endfor
                    </div>
                    
                    <div class="mt-4">
                        <h6 class="mb-3">Legend</h6>
                        <div class="d-flex align-items-center mb-2">
                            <div class="question-nav-btn current me-2"></div>
                            <small>Current Question</small>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <div class="question-nav-btn answered me-2"></div>
                            <small>Answered</small>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <div class="question-nav-btn not-visited me-2"></div>
                            <small>Not Visited</small>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-4 border-top">
                        <button class="btn btn-primary w-100 mb-2" onclick="submitTest()">
                            <i class="fas fa-paper-plane me-2"></i>Submit Test
                        </button>
                        <button class="btn btn-outline-danger w-100" onclick="confirmExit()">
                            <i class="fas fa-sign-out-alt me-2"></i>Exit Test
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons (Mobile) -->
<div class="action-buttons d-lg-none">
    <div class="container">
        <div class="row g-2">
            <div class="col-4">
                <button class="btn btn-outline-primary w-100" onclick="prevQuestion()">
                    <i class="fas fa-chevron-left me-1"></i>Prev
                </button>
            </div>
            <div class="col-4">
                <button class="btn btn-outline-secondary w-100" onclick="markForReview()">
                    <i class="fas fa-flag me-1"></i>Review
                </button>
            </div>
            <div class="col-4">
                <button class="btn btn-outline-primary w-100" onclick="nextQuestion()">
                    Next <i class="fas fa-chevron-right ms-1"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Submit Modal -->
<div class="modal fade" id="submitModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Submit Test</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to submit the test?</p>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>{{ $attempt->attempted_questions }}</strong> questions answered out of <strong>{{ $attempt->total_questions }}</strong>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="finalSubmit()">Submit Test</button>
            </div>
        </div>
    </div>
</div>

<!-- Exit Modal -->
<div class="modal fade" id="exitModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Exit Test</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Your progress will be saved. You can continue later from where you left.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="{{ route('website.mcqs.mock-test-detail', $attempt->mockTest->slug) }}" class="btn btn-danger">Exit Test</a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let questions = @json($questions);
    let savedAnswers = @json($savedAnswers);
    let currentQuestion = 1;
    let timeLeft = {{ $remainingSeconds }};
    let timerInterval;
    
    // Initialize the test
    document.addEventListener('DOMContentLoaded', function() {
        loadQuestion(currentQuestion);
        updateNavigation();
        startTimer();
        
        // Load saved answers
        Object.keys(savedAnswers).forEach(mcqId => {
            let answer = savedAnswers[mcqId];
            let questionIndex = questions.findIndex(q => q.mcq_id == mcqId);
            if (questionIndex !== -1) {
                questions[questionIndex].selectedAnswers = answer.selected_answers;
                questions[questionIndex].saved = true;
            }
        });
    });
    
    // Timer functions
    function startTimer() {
        timerInterval = setInterval(function() {
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                autoSubmit();
                return;
            }
            
            timeLeft--;
            updateTimerDisplay();
        }, 1000);
    }
    
    function updateTimerDisplay() {
        let hours = Math.floor(timeLeft / 3600);
        let minutes = Math.floor((timeLeft % 3600) / 60);
        let seconds = timeLeft % 60;
        
        document.getElementById('timer').textContent = 
            `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }
    
    // Question navigation
    function loadQuestion(questionNumber) {
        currentQuestion = questionNumber;
        let question = questions[questionNumber - 1];
        
        if (!question) return;
        
        document.getElementById('current-question').textContent = questionNumber;
        
        let optionsHtml = '';
        if (question.options && typeof question.options === 'object') {
            Object.entries(question.options).forEach(([key, value]) => {
                let isSelected = question.selectedAnswers && question.selectedAnswers.includes(key.toString());
                let optionClass = 'option-card';
                if (isSelected) {
                    optionClass += question.question_type === 'multiple' ? ' multiple-selected' : ' selected';
                }
                
                optionsHtml += `
                    <div class="${optionClass}" onclick="selectOption('${key}')">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <input type="${question.question_type === 'multiple' ? 'checkbox' : 'radio'}" 
                                       ${isSelected ? 'checked' : ''}
                                       style="pointer-events: none;">
                            </div>
                            <div>
                                <strong>${key}.</strong> ${value}
                            </div>
                        </div>
                    </div>
                `;
            });
        }
        
        document.getElementById('question-area').innerHTML = `
            <div class="question-container">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <h5>Question ${questionNumber}</h5>
                    <div>
                        <span class="badge bg-primary">${question.marks} Mark${question.marks > 1 ? 's' : ''}</span>
                        ${question.time_limit_seconds ? 
                            `<span class="badge bg-warning text-dark ms-1">${question.time_limit_seconds}s</span>` : ''}
                    </div>
                </div>
                
                <div class="question-text mb-4">
                    ${question.question}
                </div>
                
                <div class="options-container">
                    ${optionsHtml}
                </div>
                
                <!-- Action buttons for desktop -->
                <div class="d-none d-md-flex justify-content-between mt-5 pt-4 border-top">
                    <button class="btn btn-outline-primary" onclick="prevQuestion()" ${questionNumber === 1 ? 'disabled' : ''}>
                        <i class="fas fa-chevron-left me-2"></i>Previous
                    </button>
                    
                    <div>
                        <button class="btn btn-outline-secondary me-2" onclick="markForReview()">
                            <i class="fas fa-flag me-2"></i>Mark for Review
                        </button>
                        <button class="btn btn-primary" onclick="nextQuestion()">
                            Next <i class="fas fa-chevron-right ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        updateNavigation();
    }
    
    function selectOption(optionKey) {
        let question = questions[currentQuestion - 1];
        
        if (!question.selectedAnswers) {
            question.selectedAnswers = [];
        }
        
        if (question.question_type === 'single') {
            question.selectedAnswers = [optionKey];
        } else {
            let index = question.selectedAnswers.indexOf(optionKey);
            if (index === -1) {
                question.selectedAnswers.push(optionKey);
            } else {
                question.selectedAnswers.splice(index, 1);
            }
        }
        
        // Save answer
        saveAnswer();
        loadQuestion(currentQuestion);
    }
    
    function saveAnswer() {
        let question = questions[currentQuestion - 1];
        if (!question || !question.selectedAnswers) return;
        
        fetch('{{ route("website.mcqs.save-answer", ["attempt" => $attempt->uuid]) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                mcq_id: question.mcq_id,
                selected_answers: question.selectedAnswers
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                question.saved = true;
                updateAnsweredCount();
            }
        })
        .catch(error => console.error('Error:', error));
    }
    
    function updateAnsweredCount() {
        let answered = questions.filter(q => q.selectedAnswers && q.selectedAnswers.length > 0).length;
        document.getElementById('answered-count').textContent = answered;
    }
    
    function updateNavigation() {
        questions.forEach((question, index) => {
            let btn = document.querySelector(`[data-question="${index + 1}"]`);
            if (btn) {
                btn.className = 'question-nav-btn';
                
                if ((index + 1) === currentQuestion) {
                    btn.classList.add('current');
                } else if (question.selectedAnswers && question.selectedAnswers.length > 0) {
                    btn.classList.add('answered');
                } else {
                    btn.classList.add('not-visited');
                }
            }
        });
    }
    
    function goToQuestion(questionNumber) {
        saveAnswer();
        loadQuestion(questionNumber);
    }
    
    function prevQuestion() {
        if (currentQuestion > 1) {
            saveAnswer();
            loadQuestion(currentQuestion - 1);
        }
    }
    
    function nextQuestion() {
        if (currentQuestion < questions.length) {
            saveAnswer();
            loadQuestion(currentQuestion + 1);
        }
    }
    
    function markForReview() {
        // Implement review functionality
        alert('Marked for review');
    }
    
    function submitTest() {
        let modal = new bootstrap.Modal(document.getElementById('submitModal'));
        modal.show();
    }
    
    function finalSubmit() {
        clearInterval(timerInterval);
        
        fetch('{{ route("website.mcqs.submit-test", ["attempt" => $attempt->uuid]) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => {
            if (response.redirected) {
                window.location.href = response.url;
            }
        })
        .catch(error => console.error('Error:', error));
    }
    
    function autoSubmit() {
        finalSubmit();
    }
    
    function confirmExit() {
        let modal = new bootstrap.Modal(document.getElementById('exitModal'));
        modal.show();
    }
    
    // Prevent accidental exit
    window.addEventListener('beforeunload', function(e) {
        e.preventDefault();
        e.returnValue = 'Your test progress will be lost. Are you sure you want to leave?';
    });
</script>
@endpush