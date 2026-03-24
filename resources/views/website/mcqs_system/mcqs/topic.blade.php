@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/topic-practice.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@endpush

@section('content')

<!-- ==================== TOPIC HERO SECTION ==================== -->
<section class="topic-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1>{{ $topic->title }}</h1>
                <div class="topic-meta">
                    <span class="topic-meta-item">
                        <i class="far fa-clock"></i>
                        {{ $topic->estimated_time_minutes }} minutes
                    </span>
                    <span class="topic-difficulty-badge">
                        {{ ucfirst($topic->difficulty_level) }}
                    </span>
                    <span class="topic-stats-badge">
                        <i class="fas fa-question-circle"></i>{{ $mcqs->total() }} Questions
                    </span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="topic-header-actions">
                    <button class="btn btn-light" onclick="submitTest()" id="submitBtn">
                        <i class="fas fa-check-circle me-2"></i>Submit Test
                    </button>
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
                    <li class="breadcrumb-item active">{{ $topic->title }}</li>
                </ol>
            </nav>
        </div>

        <!-- Topic Description -->
        <div class="description-card" id="descriptionPreview">
            <div class="description-header">
                <h5><i class="fas fa-info-circle me-2"></i>Topic Overview</h5>
                <button class="description-toggle" onclick="toggleDescription()" id="toggleDescriptionBtn">
                    Read More <i class="fas fa-chevron-down ms-1"></i>
                </button>
            </div>
            <p class="description-preview">{{ Str::limit(strip_tags($topic->description), 200) }}</p>
        </div>

        <div class="description-full" id="descriptionFull">
            <div class="description-header">
                <h5><i class="fas fa-info-circle me-2"></i>Complete Topic Overview</h5>
                <button class="description-toggle" onclick="toggleDescription()">
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
                                <div class="question-text">{!! $mcq->question !!}</div>
                            </div>
                            
                            <div class="question-meta">
                                <span class="difficulty-badge {{ $mcq->difficulty_level }}">
                                    {{ ucfirst($mcq->difficulty_level) }}
                                </span>
                                <span class="marks-badge">
                                    <i class="fas fa-star"></i>{{ $mcq->marks }} Mark{{ $mcq->marks > 1 ? 's' : '' }}
                                </span>
                                @if($mcq->hint)
                                <button type="button" class="hint-btn" onclick="toggleHint({{ $mcq->id }})">
                                    <i class="fas fa-lightbulb"></i>Hint
                                </button>
                                @endif
                            </div>

                            <!-- Hint Section -->
                            @if($mcq->hint)
                            <div class="hint-section" id="hint-{{ $mcq->id }}">
                                <i class="fas fa-lightbulb"></i>
                                <span>{{ $mcq->hint }}</span>
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
                                           class="d-none">
                                    @else
                                    <input type="radio" 
                                           name="answers[{{ $mcq->id }}]" 
                                           value="{{ $key }}"
                                           id="option-{{ $mcq->id }}-{{ $key }}"
                                           class="d-none">
                                    @endif
                                    <span class="option-letter">{{ chr(64 + $key) }}</span>
                                    <span class="option-text">{{ $option }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach

                        <!-- Test Navigation (non-fixed, with timer like subject-by-test-type) -->
                        <div class="test-navigation">
                            <div class="progress-indicator">
                                <div class="progress-bar-fill" id="progressBar" style="width: 0%"></div>
                            </div>
                            
                            <div class="navigation-info">
                                <span>
                                    <i class="fas fa-check-circle me-1"></i>
                                    <span id="answeredCount">0</span>/{{ $mcqs->total() }} Answered
                                </span>
                                <span>
                                    <i class="fas fa-clock me-1"></i>
                                    <span id="timer">00:00</span>
                                </span>
                            </div>
                            
                            <div class="pagination-buttons">
                                <div class="d-flex gap-2">
                                    @if($mcqs->onFirstPage() == false)
                                    <a href="{{ $mcqs->previousPageUrl() }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left"></i> Previous
                                    </a>
                                    @endif
                                    
                                    @if($mcqs->hasMorePages())
                                    <a href="{{ $mcqs->nextPageUrl() }}" class="btn btn-primary">
                                        Next <i class="fas fa-arrow-right"></i>
                                    </a>
                                    @endif
                                </div>
                                
                                <div class="d-flex gap-2">
                                    <button type="button" class="clear-btn" onclick="clearTest()">
                                        <i class="fas fa-undo"></i> Clear All
                                    </button>
                                    
                                    @if($mcqs->currentPage() == $mcqs->lastPage())
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-check-circle"></i> Submit Test
                                    </button>
                                    @else
                                    <button type="button" class="btn btn-outline-success" 
                                            onclick="if(confirm('You have {{ $mcqs->currentPage() }} of {{ $mcqs->lastPage() }} pages completed. Submit now?')) { document.getElementById('testForm').submit(); }">
                                        <i class="fas fa-check-circle"></i> Submit
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-question-circle"></i>
                            <h5>No questions available</h5>
                            <p>Questions for this topic will be added soon.</p>
                        </div>
                    @endif
                </form>
            </div>

            <!-- Sidebar - Question Palette -->
            <div class="col-lg-4">
                <div class="question-palette">
                    <h5 class="palette-title">
                        <i class="fas fa-th"></i> Question Palette
                    </h5>
                    
                    <div class="palette-info">
                        <i class="fas fa-info-circle"></i>
                        <span id="answeredStats">0/{{ $mcqs->total() }}</span> Answered
                    </div>
                    
                    <div class="palette-grid" id="paletteGrid">
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
                    
                    <div class="palette-info">
                        <i class="fas fa-list"></i>
                        Showing {{ ($mcqs->currentPage() - 1) * $mcqs->perPage() + 1 }} - {{ min($mcqs->currentPage() * $mcqs->perPage(), $mcqs->total()) }} of {{ $mcqs->total() }}
                    </div>
                    
                    <div class="instructions">
                        <h6>Instructions:</h6>
                        <ul>
                            <li><i class="fas fa-circle"></i> Click on an option to select your answer</li>
                            <li><i class="fas fa-circle"></i> Use the hint button if you need help</li>
                            <li><i class="fas fa-circle"></i> Track your progress with the question palette</li>
                            <li><i class="fas fa-circle"></i> Submit your answers to see results</li>
                        </ul>
                    </div>

                    <!-- Topic Stats -->
                    @php
                        $easyCount = $mcqs->where('difficulty_level', 'easy')->count();
                        $mediumCount = $mcqs->where('difficulty_level', 'medium')->count();
                        $hardCount = $mcqs->where('difficulty_level', 'hard')->count();
                        $total = $easyCount + $mediumCount + $hardCount;
                    @endphp
                    
                    <div class="difficulty-stats">
                        <h6>Difficulty Distribution</h6>
                        <div class="difficulty-stat-row">
                            <span class="label easy">
                                <i class="fas fa-circle"></i> Easy
                            </span>
                            <span class="count">{{ $easyCount }}</span>
                        </div>
                        <div class="difficulty-stat-row">
                            <span class="label medium">
                                <i class="fas fa-circle"></i> Medium
                            </span>
                            <span class="count">{{ $mediumCount }}</span>
                        </div>
                        <div class="difficulty-stat-row">
                            <span class="label hard">
                                <i class="fas fa-circle"></i> Hard
                            </span>
                            <span class="count">{{ $hardCount }}</span>
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
    let startTime = Date.now();
    let timerInterval;

    // Load saved answers from localStorage on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadSavedAnswers();
        updateProgress();
        startTimer();
    });

    function startTimer() {
        if (timerInterval) clearInterval(timerInterval);
        timerInterval = setInterval(function() {
            const elapsed = Math.floor((Date.now() - startTime) / 1000);
            const minutes = Math.floor(elapsed / 60);
            const seconds = elapsed % 60;

            const timer = document.getElementById('timer');
            if (timer) {
                timer.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            }
        }, 1000);
    }

    function loadSavedAnswers() {
        // ensure timer is stopped
        if (timerInterval) {
            clearInterval(timerInterval);
        }

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
            const checkbox = document.getElementById(`option-${mcqId}-${optionKey}`);
            checkbox.checked = !checkbox.checked;
            
            if (checkbox.checked) {
                optionItem.classList.add('selected');
            } else {
                optionItem.classList.remove('selected');
            }
        } else {
            inputs.forEach(input => {
                if (input.type === 'radio') {
                    input.checked = false;
                }
            });
            
            const radio = document.getElementById(`option-${mcqId}-${optionKey}`);
            radio.checked = true;
            
            questionCard.querySelectorAll('.option-item').forEach(item => {
                item.classList.remove('selected');
            });
            
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
            answeredQuestions.add(parseInt(mcqId));
            const paletteItem = document.getElementById(`palette-${mcqId}`);
            if (paletteItem) {
                paletteItem.classList.add('answered');
            }
            
            if (isMultiple) {
                saveAnswer(mcqId, selectedAnswers);
            } else {
                saveAnswer(mcqId, selectedAnswers[0]);
            }
        } else {
            answeredQuestions.delete(parseInt(mcqId));
            const paletteItem = document.getElementById(`palette-${mcqId}`);
            if (paletteItem) {
                paletteItem.classList.remove('answered');
            }
            
            const savedAnswers = JSON.parse(localStorage.getItem(storageKey) || '{}');
            delete savedAnswers[mcqId];
            localStorage.setItem(storageKey, JSON.stringify(savedAnswers));
        }
        
        updateProgress();
    }

    function updateProgress() {
        const savedAnswers = JSON.parse(localStorage.getItem(storageKey) || '{}');
        const answeredCount = Object.keys(savedAnswers).length;
        const percentage = totalQuestions > 0 ? (answeredCount / totalQuestions) * 100 : 0;
        
        const progressBar = document.getElementById('progressBar');
        const answeredCountEl = document.getElementById('answeredCount');
        const answeredStatsEl = document.getElementById('answeredStats');
        
        if (progressBar) {
            progressBar.style.width = `${percentage}%`;
        }
        if (answeredCountEl) {
            answeredCountEl.textContent = answeredCount;
        }
        if (answeredStatsEl) {
            answeredStatsEl.textContent = `${answeredCount}/${totalQuestions}`;
        }
    }
    
    document.getElementById('testForm')?.addEventListener('submit', function(e) {
        const savedAnswers = JSON.parse(localStorage.getItem(storageKey) || '{}');
        
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
        
        localStorage.removeItem(storageKey);
    });

    function scrollToQuestion(event, mcqId) {
        event.preventDefault();
        const element = document.getElementById(`question-${mcqId}`);
        element.scrollIntoView({ behavior: 'smooth', block: 'center' });
        
        document.querySelectorAll('.palette-item').forEach(item => {
            item.classList.remove('current');
        });
        
        document.getElementById(`palette-${mcqId}`).classList.add('current');
    }

    function clearTest() {
        if (confirm('Are you sure you want to clear all answers?')) {
            document.querySelectorAll('input[type="radio"], input[type="checkbox"]').forEach(input => {
                input.checked = false;
            });
            
            document.querySelectorAll('.option-item').forEach(item => {
                item.classList.remove('selected');
            });
            
            answeredQuestions.clear();
            
            document.querySelectorAll('.palette-item').forEach(item => {
                item.classList.remove('answered');
            });
            
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
        updateProgress();
        
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