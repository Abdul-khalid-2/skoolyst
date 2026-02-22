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

    .description-preview {
        background: white;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .description-full {
        background: white;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        display: none;
    }

    .description-full.show {
        display: block;
    }

    .question-card {
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
        background: white;
    }

    .question-card:hover {
        border-color: #4361ee;
        box-shadow: 0 5px 15px rgba(67, 97, 238, 0.1);
    }

    .question-header {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }

    .question-number {
        width: 35px;
        height: 35px;
        background: #4361ee;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-right: 15px;
    }

    .question-text {
        font-size: 1.1rem;
        font-weight: 500;
        flex: 1;
    }

    .options-container {
        margin: 15px 0;
    }

    .option-item {
        padding: 10px 15px;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .option-item:hover {
        background: #f8f9fa;
        border-color: #4361ee;
    }

    .option-item.selected {
        background: #e3f2fd;
        border-color: #4361ee;
    }

    .option-item input[type="radio"],
    .option-item input[type="checkbox"] {
        margin-right: 10px;
    }

    .hint-section {
        margin-top: 10px;
        padding: 10px;
        background: #fff3cd;
        border-radius: 5px;
        border-left: 4px solid #ffc107;
        display: none;
    }

    .hint-section.show {
        display: block;
    }

    .difficulty-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 500;
        margin-right: 10px;
    }

    .difficulty-easy {
        background: #d4edda;
        color: #155724;
    }

    .difficulty-medium {
        background: #fff3cd;
        color: #856404;
    }

    .difficulty-hard {
        background: #f8d7da;
        color: #721c24;
    }

    .test-navigation {
        position: sticky;
        bottom: 20px;
        background: white;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 -5px 20px rgba(0,0,0,0.1);
        z-index: 100;
    }

    .progress-indicator {
        height: 5px;
        background: #e9ecef;
        border-radius: 5px;
        overflow: hidden;
        margin-bottom: 15px;
    }

    .progress-bar-fill {
        height: 100%;
        background: #4361ee;
        transition: width 0.3s ease;
    }

    .question-palette {
        position: sticky;
        top: 20px;
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .palette-item {
        width: 35px;
        height: 35px;
        border-radius: 5px;
        background: #e9ecef;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin: 0 5px 5px 0;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 0.9rem;
    }

    .palette-item.answered {
        background: #4361ee;
        color: white;
    }

    .palette-item.current {
        border: 2px solid #ffc107;
        transform: scale(1.1);
    }

    .palette-item:hover {
        background: #4361ee;
        color: white;
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
                <li class="breadcrumb-item active">{{ $topic->title }}</li>
            </ol>
        </nav>

        <!-- Topic Header -->
        <div class="topic-header">
            <div class="container position-relative">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="h2 mb-3">{{ $topic->title }}</h1>
                        <div class="d-flex align-items-center flex-wrap">
                            <span class="estimated-time me-3">
                                <i class="far fa-clock me-1"></i>
                                {{ $topic->estimated_time_minutes }} min
                            </span>
                            <span class="difficulty-badge difficulty-{{ $topic->difficulty_level }} me-2">
                                {{ ucfirst($topic->difficulty_level) }}
                            </span>
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-question-circle me-1"></i>{{ $mcqs->total() }} Questions
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <button class="btn btn-light btn-lg" onclick="submitTest()" id="submitBtn">
                            <i class="fas fa-check-circle me-2"></i>Submit Test
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Topic Description -->
        <div class="description-preview" id="descriptionPreview">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Topic Overview</h5>
                <button class="btn btn-link" onclick="toggleDescription()" id="toggleDescriptionBtn">
                    Read More <i class="fas fa-chevron-down ms-1"></i>
                </button>
            </div>
            <p class="mt-3 mb-0">{{ Str::limit(strip_tags($topic->description), 200) }}</p>
        </div>

        <div class="description-full" id="descriptionFull">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Complete Topic Overview</h5>
                <button class="btn btn-link" onclick="toggleDescription()">
                    Show Less <i class="fas fa-chevron-up ms-1"></i>
                </button>
            </div>
            {!! $topic->content ?? $topic->description !!}
        </div>

        <div class="row">
            <!-- Main Content - Questions -->
            <div class="col-lg-8">
                <form id="testForm" method="POST" action="{{ route('website.mcqs.submit-topic-test') }}">
                    @csrf
                    <input type="hidden" name="topic_id" value="{{ $topic->id }}">
                    <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                    
                    @if($mcqs->count() > 0)
                        @foreach($mcqs as $index => $mcq)
                        <div class="question-card" id="question-{{ $mcq->id }}" data-question-id="{{ $mcq->id }}">
                            <div class="question-header">
                                <span class="question-number">{{ ($mcqs->currentPage() - 1) * $mcqs->perPage() + $index + 1 }}</span>
                                <span class="question-text">{!! $mcq->question !!}</span>
                            </div>
                            
                            <div class="d-flex align-items-center mb-3">
                                <span class="difficulty-badge difficulty-{{ $mcq->difficulty_level }}">
                                    {{ ucfirst($mcq->difficulty_level) }}
                                </span>
                                <span class="badge bg-light text-dark me-2">
                                    <i class="fas fa-star me-1"></i>{{ $mcq->marks }} Mark{{ $mcq->marks > 1 ? 's' : '' }}
                                </span>
                                @if($mcq->hint)
                                <button type="button" class="btn btn-sm btn-outline-warning" onclick="toggleHint({{ $mcq->id }})">
                                    <i class="fas fa-lightbulb me-1"></i>Hint
                                </button>
                                @endif
                            </div>

                            <!-- Hint Section -->
                            @if($mcq->hint)
                            <div class="hint-section" id="hint-{{ $mcq->id }}">
                                <i class="fas fa-lightbulb text-warning me-2"></i>
                                {{ $mcq->hint }}
                            </div>
                            @endif

                            <!-- Options -->
                            <div class="options-container">
                                @php
                                    $options = is_array($mcq->options) ? $mcq->options : json_decode($mcq->options, true);
                                    $correctAnswers = is_array($mcq->correct_answers) ? $mcq->correct_answers : json_decode($mcq->correct_answers, true);
                                    $isMultiple = count($correctAnswers) > 1 || $mcq->question_type === 'multiple';
                                @endphp

                                @foreach($options as $key => $option)
                                <div class="option-item" onclick="selectOption(this, '{{ $mcq->id }}', '{{ $key }}', {{ $isMultiple ? 'true' : 'false' }})">
                                    @if($isMultiple)
                                    <input type="checkbox" 
                                           name="answers[{{ $mcq->id }}][]" 
                                           value="{{ $key }}"
                                           id="option-{{ $mcq->id }}-{{ $key }}"
                                           class="form-check-input d-none">
                                    @else
                                    <input type="radio" 
                                           name="answers[{{ $mcq->id }}]" 
                                           value="{{ $key }}"
                                           id="option-{{ $mcq->id }}-{{ $key }}"
                                           class="form-check-input d-none">
                                    @endif
                                    <span class="option-letter">{{ chr(65 + $key) }}.</span>
                                    <span class="option-text">{{ $option }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach

                        <!-- Test Navigation -->
                        <div class="test-navigation mb-4">
                            <div class="progress-indicator mb-3">
                                <div class="progress-bar-fill" id="progressBar" style="width: 0%"></div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <span id="answeredCount">0</span>/{{ $mcqs->count() }} Answered (Page {{ $mcqs->currentPage() }} of {{ $mcqs->lastPage() }})
                                </div>
                                <div>
                                    <button type="button" class="btn btn-outline-primary me-2" onclick="clearTest()">
                                        <i class="fas fa-undo me-2"></i>Clear
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Pagination and Submit Buttons -->
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div>
                                    @if($mcqs->hasMorePages())
                                    <a href="{{ $mcqs->nextPageUrl() }}" class="btn btn-primary">
                                        <i class="fas fa-arrow-right me-2"></i>Next Page ({{ $mcqs->currentPage() + 1 }}/{{ $mcqs->lastPage() }})
                                    </a>
                                    @endif
                                    
                                    @if($mcqs->onFirstPage() == false)
                                    <a href="{{ $mcqs->previousPageUrl() }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Previous Page
                                    </a>
                                    @endif
                                </div>
                                
                                <div>
                                    @if($mcqs->currentPage() == $mcqs->lastPage())
                                    <button type="submit" class="btn btn-success btn-lg">
                                        <i class="fas fa-check-circle me-2"></i>Submit Test
                                    </button>
                                    @else
                                    <button type="button" class="btn btn-outline-success" onclick="if(confirm('Are you sure you want to submit? You have {{ $mcqs->currentPage() }} of {{ $mcqs->lastPage() }} pages completed.')) { document.getElementById('testForm').submit(); }">
                                        <i class="fas fa-check-circle me-2"></i>Submit Now
                                    </button>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Pagination Links -->
                            <div class="mt-3">
                                {{ $mcqs->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No questions available</h5>
                            <p class="text-muted">Questions for this topic will be added soon.</p>
                        </div>
                    @endif
                </form>
            </div>

            <!-- Sidebar - Question Palette -->
            <div class="col-lg-4">
                <div class="question-palette">
                    <h5 class="mb-3"><i class="fas fa-th me-2"></i>Question Palette</h5>
                    <div class="mb-3">
                        <p class="small text-muted mb-2">Page {{ $mcqs->currentPage() }} of {{ $mcqs->lastPage() }}</p>
                        @foreach($mcqs as $index => $mcq)
                        <a href="#question-{{ $mcq->id }}" 
                           class="palette-item" 
                           data-question-id="{{ $mcq->id }}"
                           id="palette-{{ $mcq->id }}"
                           onclick="scrollToQuestion(event, {{ $mcq->id }})">
                            {{ ($mcqs->currentPage() - 1) * $mcqs->perPage() + $index + 1 }}
                        </a>
                        @endforeach
                    </div>
                    <div class="alert alert-info small">
                        <i class="fas fa-info-circle me-2"></i>
                        Showing questions {{ ($mcqs->currentPage() - 1) * $mcqs->perPage() + 1 }} to {{ min($mcqs->currentPage() * $mcqs->perPage(), $mcqs->total()) }} of {{ $mcqs->total() }}
                    </div>
                    
                    <hr>
                    
                    <div class="mb-3">
                        <h6 class="mb-2">Instructions:</h6>
                        <ul class="small text-muted">
                            <li>Click on an option to select your answer</li>
                            <li>Use the hint button if you need help</li>
                            <li>Track your progress with the question palette</li>
                            <li>Submit your answers to see results</li>
                        </ul>
                    </div>

                    <!-- Topic Stats -->
                    <div class="mt-4">
                        <h6 class="mb-2">Difficulty Distribution</h6>
                        @php
                            $easyCount = $mcqs->where('difficulty_level', 'easy')->count();
                            $mediumCount = $mcqs->where('difficulty_level', 'medium')->count();
                            $hardCount = $mcqs->where('difficulty_level', 'hard')->count();
                            $total = $easyCount + $mediumCount + $hardCount;
                        @endphp
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-success">Easy</span>
                            <span>{{ $easyCount }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-warning">Medium</span>
                            <span>{{ $mediumCount }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-danger">Hard</span>
                            <span>{{ $hardCount }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    let answeredQuestions = new Set();
    const storageKey = 'mcq_answers_{{ $topic->id }}';
    const totalQuestions = {{ $mcqs->total() }};

    // Load saved answers from localStorage on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadSavedAnswers();
        updateProgress();
    });

    function loadSavedAnswers() {
        const savedAnswers = JSON.parse(localStorage.getItem(storageKey) || '{}');
        
        Object.keys(savedAnswers).forEach(mcqId => {
            const answers = savedAnswers[mcqId];
            const questionCard = document.getElementById(`question-${mcqId}`);
            
            if (questionCard) {
                if (Array.isArray(answers)) {
                    // Multiple choice
                    answers.forEach(answerKey => {
                        const checkbox = document.getElementById(`option-${mcqId}-${answerKey}`);
                        if (checkbox) {
                            checkbox.checked = true;
                            checkbox.closest('.option-item')?.classList.add('selected');
                        }
                    });
                } else {
                    // Single choice
                    const radio = document.getElementById(`option-${mcqId}-${answers}`);
                    if (radio) {
                        radio.checked = true;
                        radio.closest('.option-item')?.classList.add('selected');
                    }
                }
                
                answeredQuestions.add(parseInt(mcqId));
                const paletteItem = document.getElementById(`palette-${mcqId}`);
                if (paletteItem) {
                    paletteItem.classList.add('answered');
                }
            }
        });
    }

    function saveAnswer(mcqId, answers) {
        const savedAnswers = JSON.parse(localStorage.getItem(storageKey) || '{}');
        savedAnswers[mcqId] = answers;
        localStorage.setItem(storageKey, JSON.stringify(savedAnswers));
    }

    function toggleDescription() {
        const preview = document.getElementById('descriptionPreview');
        const full = document.getElementById('descriptionFull');
        const btn = document.getElementById('toggleDescriptionBtn');
        
        if (full.classList.contains('show')) {
            full.classList.remove('show');
            preview.style.display = 'block';
            btn.innerHTML = 'Read More <i class="fas fa-chevron-down ms-1"></i>';
        } else {
            full.classList.add('show');
            preview.style.display = 'none';
            btn.innerHTML = 'Show Less <i class="fas fa-chevron-up ms-1"></i>';
        }
    }

    function toggleHint(mcqId) {
        const hintSection = document.getElementById(`hint-${mcqId}`);
        hintSection.classList.toggle('show');
    }

    function selectOption(element, mcqId, optionKey, isMultiple = false) {
        const optionItem = element;
        const questionCard = document.getElementById(`question-${mcqId}`);
        const inputs = questionCard.querySelectorAll('input');
        
        if (isMultiple) {
            // For multiple choice questions
            const checkbox = document.getElementById(`option-${mcqId}-${optionKey}`);
            checkbox.checked = !checkbox.checked;
            
            if (checkbox.checked) {
                optionItem.classList.add('selected');
            } else {
                optionItem.classList.remove('selected');
            }
        } else {
            // For single choice questions
            inputs.forEach(input => {
                if (input.type === 'radio') {
                    input.checked = false;
                }
            });
            
            const radio = document.getElementById(`option-${mcqId}-${optionKey}`);
            radio.checked = true;
            
            // Remove selected class from all options
            questionCard.querySelectorAll('.option-item').forEach(item => {
                item.classList.remove('selected');
            });
            
            // Add selected class to clicked option
            optionItem.classList.add('selected');
        }
        
        // Check if question is answered
        let isAnswered = false;
        inputs.forEach(input => {
            if (input.checked) {
                isAnswered = true;
            }
        });
        
        // Save answers to localStorage
        const selectedAnswers = [];
        inputs.forEach(input => {
            if (input.checked) {
                selectedAnswers.push(input.value);
            }
        });
        
        if (selectedAnswers.length > 0) {
            answeredQuestions.add(mcqId);
            const paletteItem = document.getElementById(`palette-${mcqId}`);
            if (paletteItem) {
                paletteItem.classList.add('answered');
            }
            // Save to localStorage
            if (isMultiple) {
                saveAnswer(mcqId, selectedAnswers);
            } else {
                saveAnswer(mcqId, selectedAnswers[0]);
            }
        } else {
            answeredQuestions.delete(mcqId);
            const paletteItem = document.getElementById(`palette-${mcqId}`);
            if (paletteItem) {
                paletteItem.classList.remove('answered');
            }
            // Remove from localStorage
            const savedAnswers = JSON.parse(localStorage.getItem(storageKey) || '{}');
            delete savedAnswers[mcqId];
            localStorage.setItem(storageKey, JSON.stringify(savedAnswers));
        }
        
        updateProgress();
    }

    function updateProgress() {
        // Count all answered questions from localStorage
        const savedAnswers = JSON.parse(localStorage.getItem(storageKey) || '{}');
        const answeredCount = Object.keys(savedAnswers).length;
        const percentage = totalQuestions > 0 ? (answeredCount / totalQuestions) * 100 : 0;
        
        const progressBar = document.getElementById('progressBar');
        const answeredCountEl = document.getElementById('answeredCount');
        
        if (progressBar) {
            progressBar.style.width = `${percentage}%`;
        }
        if (answeredCountEl) {
            answeredCountEl.textContent = answeredCount;
        }
    }
    
    // Before form submit, add all answers from localStorage as hidden inputs
    document.getElementById('testForm')?.addEventListener('submit', function(e) {
        const savedAnswers = JSON.parse(localStorage.getItem(storageKey) || '{}');
        
        // Add all saved answers as hidden inputs
        Object.keys(savedAnswers).forEach(mcqId => {
            const answers = savedAnswers[mcqId];
            if (Array.isArray(answers)) {
                answers.forEach(answer => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `answers[${mcqId}][]`;
                    input.value = answer;
                    this.appendChild(input);
                });
            } else {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `answers[${mcqId}]`;
                input.value = answers;
                this.appendChild(input);
            }
        });
        
        // Clear localStorage after submission
        localStorage.removeItem(storageKey);
    });

    function scrollToQuestion(event, mcqId) {
        event.preventDefault();
        const element = document.getElementById(`question-${mcqId}`);
        element.scrollIntoView({ behavior: 'smooth', block: 'center' });
        
        // Remove current class from all palette items
        document.querySelectorAll('.palette-item').forEach(item => {
            item.classList.remove('current');
        });
        
        // Add current class to clicked palette item
        document.getElementById(`palette-${mcqId}`).classList.add('current');
    }

    function clearTest() {
        if (confirm('Are you sure you want to clear all answers?')) {
            // Reset all inputs
            document.querySelectorAll('input[type="radio"], input[type="checkbox"]').forEach(input => {
                input.checked = false;
            });
            
            // Remove selected class from all options
            document.querySelectorAll('.option-item').forEach(item => {
                item.classList.remove('selected');
            });
            
            // Clear answered questions set
            answeredQuestions.clear();
            
            // Remove answered class from palette
            document.querySelectorAll('.palette-item').forEach(item => {
                item.classList.remove('answered');
            });
            
            // Clear localStorage
            localStorage.removeItem(storageKey);
            
            updateProgress();
        }
    }

    function submitTest() {
        const form = document.getElementById('testForm');
        
        if (answeredQuestions.size === 0) {
            alert('Please answer at least one question before submitting.');
            return;
        }
        
        if (confirm(`You have answered ${answeredQuestions.size} out of {{ $mcqs->total() }} questions. Are you sure you want to submit?`)) {
            form.submit();
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Check for previously answered questions (if any)
        updateProgress();
        
        // Add scroll spy for current question
        window.addEventListener('scroll', function() {
            const questionCards = document.querySelectorAll('.question-card');
            const scrollPosition = window.scrollY + 100;
            
            questionCards.forEach(card => {
                const rect = card.getBoundingClientRect();
                const absoluteTop = window.scrollY + rect.top;
                const absoluteBottom = absoluteTop + rect.height;
                
                if (scrollPosition >= absoluteTop && scrollPosition <= absoluteBottom) {
                    const mcqId = card.dataset.questionId;
                    
                    document.querySelectorAll('.palette-item').forEach(item => {
                        item.classList.remove('current');
                    });
                    
                    const paletteItem = document.getElementById(`palette-${mcqId}`);
                    if (paletteItem) {
                        paletteItem.classList.add('current');
                    }
                }
            });
        });
    });
</script>
@endpush