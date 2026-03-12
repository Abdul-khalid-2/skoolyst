@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/subject-practice.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
<style>
    /* Additional Styles for Proper Functionality */
    .question-card {
        background: #fff;
        border-radius: 10px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
    
    .question-card:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    .question-header {
        display: flex;
        gap: 15px;
        margin-bottom: 15px;
    }
    
    .question-number {
        background: #667eea;
        color: white;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        flex-shrink: 0;
    }
    
    .question-text {
        font-size: 16px;
        line-height: 1.6;
        color: #333;
        flex-grow: 1;
    }
    
    .question-meta {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    
    .difficulty-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .difficulty-badge.easy {
        background: #d4edda;
        color: #155724;
    }
    
    .difficulty-badge.medium {
        background: #fff3cd;
        color: #856404;
    }
    
    .difficulty-badge.hard {
        background: #f8d7da;
        color: #721c24;
    }
    
    .marks-badge {
        background: #e2e3e5;
        color: #383d41;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .hint-btn {
        background: none;
        border: 1px solid #ffc107;
        color: #856404;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .hint-btn:hover {
        background: #ffc107;
        color: #000;
    }
    
    .hint-section {
        background: #fff3cd;
        border-left: 4px solid #ffc107;
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 5px;
        display: none;
    }
    
    .hint-section.show {
        display: block;
    }
    
    .hint-section i {
        color: #856404;
        margin-right: 10px;
    }
    
    .options-container {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-top: 20px;
    }
    
    .option-item {
        display: flex;
        align-items: center;
        padding: 12px 15px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #fff;
    }
    
    .option-item:hover {
        border-color: #667eea;
        background: #f8f9ff;
    }
    
    .option-item.selected {
        background: #667eea;
        border-color: #667eea;
        color: white;
    }
    
    .option-item.selected .option-letter {
        background: white;
        color: #667eea;
    }
    
    .option-letter {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-right: 15px;
        transition: all 0.3s ease;
    }
    
    .option-text {
        font-size: 15px;
    }
    
    .test-navigation {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-top: 30px;
    }
    
    .progress-indicator {
        height: 8px;
        background: #e9ecef;
        border-radius: 4px;
        margin-bottom: 20px;
        overflow: hidden;
    }
    
    .progress-bar-fill {
        height: 100%;
        background: #28a745;
        transition: width 0.3s ease;
    }
    
    .navigation-info {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
        color: #6c757d;
        font-size: 14px;
    }
    
    .pagination-buttons {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .clear-btn {
        background: #dc3545;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .clear-btn:hover {
        background: #c82333;
    }
    
    .question-palette {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        position: sticky;
        top: 20px;
    }
    
    .palette-title {
        font-size: 18px;
        margin-bottom: 15px;
        color: #333;
    }
    
    .palette-info {
        font-size: 14px;
        color: #6c757d;
        margin-bottom: 15px;
    }
    
    .palette-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 8px;
        margin-bottom: 20px;
    }
    
    .palette-item {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 8px;
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        text-decoration: none;
        color: #495057;
        font-size: 14px;
        transition: all 0.3s ease;
    }
    
    .palette-item:hover {
        background: #667eea;
        color: white;
        border-color: #667eea;
    }
    
    .palette-item.answered {
        background: #28a745;
        color: white;
        border-color: #28a745;
    }
    
    .palette-item.current {
        background: #ffc107;
        color: #000;
        border-color: #ffc107;
        font-weight: bold;
    }
    
    .instructions {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
    }
    
    .instructions h6 {
        margin-bottom: 10px;
        color: #333;
    }
    
    .instructions ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .instructions li {
        margin-bottom: 8px;
        font-size: 13px;
        color: #6c757d;
        display: flex;
        align-items: center;
    }
    
    .instructions li i {
        color: #667eea;
        font-size: 8px;
        margin-right: 8px;
    }
    
    .difficulty-stats {
        border-top: 1px solid #dee2e6;
        padding-top: 20px;
    }
    
    .difficulty-stats h6 {
        margin-bottom: 15px;
        color: #333;
    }
    
    .difficulty-stat-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    
    .label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }
    
    .label.easy i {
        color: #28a745;
    }
    
    .label.medium i {
        color: #ffc107;
    }
    
    .label.hard i {
        color: #dc3545;
    }
    
    .count {
        font-weight: bold;
        color: #333;
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: #f8f9fa;
        border-radius: 10px;
    }
    
    .empty-state i {
        font-size: 48px;
        color: #dee2e6;
        margin-bottom: 20px;
    }
    
    .empty-state h5 {
        color: #333;
        margin-bottom: 10px;
    }
    
    .empty-state p {
        color: #6c757d;
    }
    
    .back-button-wrapper {
        margin-bottom: 20px;
    }
    
    .back-btn {
        display: inline-flex;
        align-items: center;
        padding: 8px 16px;
        background: #f8f9fa;
        color: #667eea;
        text-decoration: none;
        border-radius: 5px;
        transition: all 0.3s ease;
    }
    
    .back-btn:hover {
        background: #667eea;
        color: white;
    }
    
    .breadcrumb-wrapper {
        margin-bottom: 20px;
    }
    
    .breadcrumb {
        background: #f8f9fa;
        padding: 10px 15px;
        border-radius: 5px;
    }
    
    .breadcrumb-item a {
        color: #667eea;
        text-decoration: none;
    }
    
    .breadcrumb-item.active {
        color: #6c757d;
    }
    
    .practice-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 60px 0;
    }
    
    .practice-hero-content {
        display: flex;
        align-items: center;
        gap: 30px;
    }
    
    .practice-hero-icon {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255,255,255,0.2);
    }
    
    .practice-hero-icon i {
        font-size: 48px;
        color: white;
    }
    
    .practice-hero-text h1 {
        font-size: 36px;
        margin-bottom: 10px;
    }
    
    .practice-hero-text p {
        font-size: 18px;
        opacity: 0.9;
        margin: 0;
    }
    
    @media (max-width: 768px) {
        .practice-hero-content {
            flex-direction: column;
            text-align: center;
        }
        
        .pagination-buttons {
            flex-direction: column;
        }
        
        .pagination-buttons .d-flex {
            width: 100%;
        }
        
        .pagination-buttons .btn {
            flex: 1;
        }
    }
</style>
@endpush

@section('content')

<!-- ==================== HERO SECTION ==================== -->
<section class="practice-hero">
    <div class="container">
        <div class="practice-hero-content">
            <div class="practice-hero-icon" style="background: {{ $subject->color_code ?? '#667eea' }};">
                <i class="{{ $subject->icon ?? 'fas fa-book' }}"></i>
            </div>
            <div class="practice-hero-text">
                <h1>{{ $subject->name ?? 'Subject' }}</h1>
                <p>{{ $subject->description ?? 'Practice MCQs for ' . ($subject->name ?? 'this subject') }}</p>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        
        <!-- Difficulty Filters -->
        <div class="filter-card">
            <div class="filter-header">
                <h5><i class="fas fa-filter me-2"></i>Filter by Difficulty</h5>
            </div>
            <div class="filter-body">
                <div class="difficulty-filters">
                    <a href="{{ request()->fullUrlWithQuery(['difficulty' => null]) }}" 
                        class="difficulty-filter {{ !request('difficulty') ? 'active' : '' }}">
                        All ({{ $difficultyStats->sum('count') }})
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['difficulty' => 'easy']) }}" 
                        class="difficulty-filter {{ request('difficulty') === 'easy' ? 'active' : '' }}">
                        Easy ({{ $difficultyStats['easy']->count ?? 0 }})
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['difficulty' => 'medium']) }}" 
                        class="difficulty-filter {{ request('difficulty') === 'medium' ? 'active' : '' }}">
                        Medium ({{ $difficultyStats['medium']->count ?? 0 }})
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['difficulty' => 'hard']) }}" 
                        class="difficulty-filter {{ request('difficulty') === 'hard' ? 'active' : '' }}">
                        Hard ({{ $difficultyStats['hard']->count ?? 0 }})
                    </a>
                </div>
            </div>
        </div>

        <!-- Topic Filters -->
        @if($topics->count() > 0)
        <div class="filter-card">
            <div class="filter-header">
                <h5><i class="fas fa-folder me-2"></i>Topics</h5>
            </div>
            <div class="filter-body">
                <div class="topic-filters">
                    @foreach($topics as $topic)
                    <a href="{{ request()->fullUrlWithQuery(['topic' => $topic->id]) }}" 
                        class="topic-filter {{ request('topic') == $topic->id ? 'active' : '' }}">
                        {{ $topic->title }} ({{ $topic->mcqs_count }})
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
        <!-- Breadcrumb -->
        <div class="breadcrumb-wrapper">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('website.mcqs.index') }}">MCQs</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('website.mcqs.test-type', $testType->slug ?? '') }}">{{ $testType->name ?? 'Test Type' }}</a></li>
                    <li class="breadcrumb-item active">{{ $subject->name ?? 'Subject' }}</li>
                    @if(isset($selectedTopic) && $selectedTopic)
                        <li class="breadcrumb-item active">{{ $selectedTopic->title }}</li>
                    @endif
                </ol>
            </nav>
        </div>

        <!-- Back to Subject Button (Only when topic is selected) -->
        @if(request('topic') && isset($topics) && $topics->firstWhere('id', request('topic')))
            @php
                $selectedTopic = $topics->firstWhere('id', request('topic'));
            @endphp
            <div class="back-button-wrapper">
                <a href="{{ route('website.mcqs.subject-by-test-type', [$testType->slug ?? '', $subject->slug ?? '']) }}" 
                   class="back-btn">
                    <i class="fas fa-arrow-left"></i> 
                    Back to All {{ $subject->name ?? 'Subject' }} Topics
                </a>
            </div>
        @endif

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <form id="testForm" method="POST" action="{{ route('website.mcqs.submit-test') }}">
                    @csrf
                    <input type="hidden" name="topic_id" value="{{ request('topic') ?? ($topic->id ?? '') }}">
                    <input type="hidden" name="subject_id" value="{{ $subject->id ?? '' }}">
                    <input type="hidden" name="test_type_id" value="{{ $testType->id ?? '' }}">
                    <input type="hidden" name="time_taken" id="timeTaken" value="0">
                    
                    @if(isset($mcqs) && $mcqs->count() > 0)
                        @foreach($mcqs as $index => $mcq)
                        <div class="question-card" id="question-{{ $mcq->id }}" data-question-id="{{ $mcq->id }}">
                            <div class="question-header">
                                <span class="question-number">{{ $index + 1 }}</span>
                                <div class="question-text">{!! $mcq->question !!}</div>
                            </div>
                            
                            <div class="question-meta">
                                <span class="difficulty-badge {{ $mcq->difficulty_level ?? 'medium' }}">
                                    {{ ucfirst($mcq->difficulty_level ?? 'medium') }}
                                </span>
                                <span class="marks-badge">
                                    <i class="fas fa-star"></i>{{ $mcq->marks ?? 1 }} Mark{{ ($mcq->marks ?? 1) > 1 ? 's' : '' }}
                                </span>
                                @if(!empty($mcq->hint))
                                <button type="button" class="hint-btn" onclick="toggleHint({{ $mcq->id }})">
                                    <i class="fas fa-lightbulb"></i> Hint
                                </button>
                                @endif
                            </div>

                            <!-- Hint Section -->
                            @if(!empty($mcq->hint))
                            <div class="hint-section" id="hint-{{ $mcq->id }}">
                                <i class="fas fa-lightbulb"></i>
                                <span>{{ $mcq->hint }}</span>
                            </div>
                            @endif

                            <!-- Options -->
                            <div class="options-container">
                                @php
                                    $options = is_array($mcq->options) ? $mcq->options : (is_string($mcq->options) ? json_decode($mcq->options, true) : []);
                                    $correctAnswers = is_array($mcq->correct_answers) ? $mcq->correct_answers : (is_string($mcq->correct_answers) ? json_decode($mcq->correct_answers, true) : []);
                                    $isMultiple = (count($correctAnswers) > 1 || ($mcq->question_type ?? 'single') === 'multiple');
                                @endphp

                                @if(!empty($options) && is_array($options))
                                    @foreach($options as $key => $option)
                                    <div class="option-item" onclick="selectOption(this, '{{ $mcq->id }}', '{{ $key }}', {{ $isMultiple ? 'true' : 'false' }})">
                                        @if($isMultiple)
                                        <input type="checkbox" 
                                               name="answers[{{ $mcq->id }}][]" 
                                               value="{{ $key }}"
                                               id="option-{{ $mcq->id }}-{{ $key }}"
                                               class="d-none"
                                               data-question-id="{{ $mcq->id }}">
                                        @else
                                        <input type="radio" 
                                               name="answers[{{ $mcq->id }}]" 
                                               value="{{ $key }}"
                                               id="option-{{ $mcq->id }}-{{ $key }}"
                                               class="d-none"
                                               data-question-id="{{ $mcq->id }}">
                                        @endif
                                        <span class="option-letter">{{ chr(65 + $key) }}</span>
                                        <span class="option-text">{{ $option }}</span>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="alert alert-warning">
                                        No options available for this question.
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endforeach

                        <!-- Test Navigation -->
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
                                    
                                    <button type="button" class="btn btn-success" onclick="submitTest()">
                                        <i class="fas fa-check-circle"></i> Submit Test
                                    </button>
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
                        <span id="answeredStats">0/{{ $mcqs->total() ?? 0 }}</span> Answered
                    </div>
                    
                    <div class="palette-grid" id="paletteGrid">
                        @if(isset($mcqs) && $mcqs->count() > 0)
                            @foreach($mcqs as $index => $mcq)
                            <a href="#question-{{ $mcq->id }}" 
                               class="palette-item" 
                               data-question-id="{{ $mcq->id }}"
                               id="palette-{{ $mcq->id }}"
                               onclick="scrollToQuestion(event, {{ $mcq->id }})">
                                {{ $index + 1 }}
                            </a>
                            @endforeach
                        @endif
                    </div>
                    
                    <div class="palette-info">
                        <i class="fas fa-list"></i>
                        @if(isset($mcqs))
                        Showing {{ $mcqs->firstItem() ?? 0 }} - {{ $mcqs->lastItem() ?? 0 }} of {{ $mcqs->total() ?? 0 }}
                        @endif
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

                    <!-- Difficulty Stats -->
                    @if(isset($mcqs) && $mcqs->count() > 0)
                        @php
                            $easyCount = $mcqs->where('difficulty_level', 'easy')->count();
                            $mediumCount = $mcqs->where('difficulty_level', 'medium')->count();
                            $hardCount = $mcqs->where('difficulty_level', 'hard')->count();
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
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // State management
    let answeredQuestions = new Set();
    let startTime = Date.now();
    let timerInterval;
    
    // Get topic ID for storage key
    const topicId = '{{ request('topic') ?? ($topic->id ?? 'general') }}';
    const storageKey = `mcq_answers_${topicId}`;
    const totalQuestions = {{ $mcqs->total() ?? 0 }};

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadSavedAnswers();
        updateProgress();
        startTimer();
        
        // Add scroll spy for palette
        setupScrollSpy();
    });

    // Timer function
    function startTimer() {
        timerInterval = setInterval(function() {
            const elapsed = Math.floor((Date.now() - startTime) / 1000);
            const minutes = Math.floor(elapsed / 60);
            const seconds = elapsed % 60;
            document.getElementById('timer').textContent = 
                `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            document.getElementById('timeTaken').value = elapsed;
        }, 1000);
    }

    // Load saved answers from localStorage
    function loadSavedAnswers() {
        try {
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
                    updatePaletteItem(mcqId, true);
                }
            });
        } catch (e) {
            console.error('Error loading saved answers:', e);
        }
    }

    // Save answer to localStorage
    function saveAnswer(mcqId, answers) {
        try {
            const savedAnswers = JSON.parse(localStorage.getItem(storageKey) || '{}');
            savedAnswers[mcqId] = answers;
            localStorage.setItem(storageKey, JSON.stringify(savedAnswers));
        } catch (e) {
            console.error('Error saving answer:', e);
        }
    }

    // Toggle hint visibility
    function toggleHint(mcqId) {
        const hintSection = document.getElementById(`hint-${mcqId}`);
        if (hintSection) {
            hintSection.classList.toggle('show');
        }
    }

    // Select option
    function selectOption(element, mcqId, optionKey, isMultiple = false) {
        const questionCard = document.getElementById(`question-${mcqId}`);
        if (!questionCard) return;
        
        const inputs = questionCard.querySelectorAll('input');
        
        if (isMultiple) {
            const checkbox = document.getElementById(`option-${mcqId}-${optionKey}`);
            if (checkbox) {
                checkbox.checked = !checkbox.checked;
                
                if (checkbox.checked) {
                    element.classList.add('selected');
                } else {
                    element.classList.remove('selected');
                }
            }
        } else {
            // Remove selection from all options in this question
            questionCard.querySelectorAll('.option-item').forEach(item => {
                item.classList.remove('selected');
            });
            
            // Uncheck all radios
            inputs.forEach(input => {
                if (input.type === 'radio') {
                    input.checked = false;
                }
            });
            
            // Select this option
            const radio = document.getElementById(`option-${mcqId}-${optionKey}`);
            if (radio) {
                radio.checked = true;
                element.classList.add('selected');
            }
        }
        
        // Check if question is answered
        let isAnswered = false;
        const selectedAnswers = [];
        
        inputs.forEach(input => {
            if (input.checked) {
                isAnswered = true;
                selectedAnswers.push(input.value);
            }
        });
        
        // Update state
        if (isAnswered) {
            answeredQuestions.add(parseInt(mcqId));
            updatePaletteItem(mcqId, true);
            
            if (isMultiple) {
                saveAnswer(mcqId, selectedAnswers);
            } else {
                saveAnswer(mcqId, selectedAnswers[0]);
            }
        } else {
            answeredQuestions.delete(parseInt(mcqId));
            updatePaletteItem(mcqId, false);
            
            // Remove from localStorage
            const savedAnswers = JSON.parse(localStorage.getItem(storageKey) || '{}');
            delete savedAnswers[mcqId];
            localStorage.setItem(storageKey, JSON.stringify(savedAnswers));
        }
        
        updateProgress();
    }

    // Update palette item
    function updatePaletteItem(mcqId, isAnswered) {
        const paletteItem = document.getElementById(`palette-${mcqId}`);
        if (paletteItem) {
            if (isAnswered) {
                paletteItem.classList.add('answered');
            } else {
                paletteItem.classList.remove('answered');
            }
        }
    }

    // Update progress
    function updateProgress() {
        const answeredCount = answeredQuestions.size;
        const percentage = totalQuestions > 0 ? (answeredCount / totalQuestions) * 100 : 0;
        
        const progressBar = document.getElementById('progressBar');
        const answeredCountEl = document.getElementById('answeredCount');
        const answeredStats = document.getElementById('answeredStats');
        
        if (progressBar) {
            progressBar.style.width = `${percentage}%`;
        }
        if (answeredCountEl) {
            answeredCountEl.textContent = answeredCount;
        }
        if (answeredStats) {
            answeredStats.textContent = `${answeredCount}/${totalQuestions}`;
        }
    }

    // Submit test
    function submitTest() {
        const answeredCount = answeredQuestions.size;
        
        if (answeredCount === 0) {
            alert('Please answer at least one question before submitting.');
            return;
        }
        
        if (confirm(`You have answered ${answeredCount} out of ${totalQuestions} questions. Are you sure you want to submit?`)) {
            // Stop timer
            clearInterval(timerInterval);
            
            // Get form
            const form = document.getElementById('testForm');
            
            // Add all answers to form
            const savedAnswers = JSON.parse(localStorage.getItem(storageKey) || '{}');
            
            Object.keys(savedAnswers).forEach(mcqId => {
                const answers = savedAnswers[mcqId];
                if (Array.isArray(answers)) {
                    answers.forEach(answer => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = `answers[${mcqId}][]`;
                        input.value = answer;
                        form.appendChild(input);
                    });
                } else {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `answers[${mcqId}]`;
                    input.value = answers;
                    form.appendChild(input);
                }
            });
            
            // Submit form
            form.submit();
        }
    }

    // Clear all answers
    function clearTest() {
        if (confirm('Are you sure you want to clear all answers?')) {
            // Clear all inputs
            document.querySelectorAll('input[type="radio"], input[type="checkbox"]').forEach(input => {
                input.checked = false;
            });
            
            // Remove selected class from all options
            document.querySelectorAll('.option-item').forEach(item => {
                item.classList.remove('selected');
            });
            
            // Clear state
            answeredQuestions.clear();
            
            // Update UI
            document.querySelectorAll('.palette-item').forEach(item => {
                item.classList.remove('answered');
            });
            
            // Clear localStorage
            localStorage.removeItem(storageKey);
            
            // Update progress
            updateProgress();
        }
    }

    // Scroll to question
    function scrollToQuestion(event, mcqId) {
        event.preventDefault();
        const element = document.getElementById(`question-${mcqId}`);
        if (element) {
            element.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            // Update current highlight
            document.querySelectorAll('.palette-item').forEach(item => {
                item.classList.remove('current');
            });
            
            const paletteItem = document.getElementById(`palette-${mcqId}`);
            if (paletteItem) {
                paletteItem.classList.add('current');
            }
        }
    }

    // Setup scroll spy
    function setupScrollSpy() {
        window.addEventListener('scroll', function() {
            const questionCards = document.querySelectorAll('.question-card');
            const scrollPosition = window.scrollY + 200;
            
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
    }

    // Clean up on page unload
    window.addEventListener('beforeunload', function() {
        clearInterval(timerInterval);
    });
</script>
@endpush