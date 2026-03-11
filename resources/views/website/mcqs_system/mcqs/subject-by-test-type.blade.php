@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/subject-practice.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@endpush

@section('content')

<!-- ==================== HERO SECTION ==================== -->
<section class="practice-hero">
    <div class="container">
        <div class="practice-hero-content">
            <div class="practice-hero-icon" style="background: {{ $subject->color_code }};">
                <i class="{{ $subject->icon ?? 'fas fa-book' }}"></i>
            </div>
            <div class="practice-hero-text">
                <h1>{{ $subject->name }}</h1>
                <p>{{ $subject->description }}</p>
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
                    <li class="breadcrumb-item"><a href="{{ route('website.mcqs.test-type', $testType->slug) }}">{{ $testType->name }}</a></li>
                    <li class="breadcrumb-item active">{{ $subject->name }}</li>
                    @if(request('topic') && $topics->firstWhere('id', request('topic')))
                        @php
                            $selectedTopic = $topics->firstWhere('id', request('topic'));
                        @endphp
                        <li class="breadcrumb-item active">{{ $selectedTopic->title }}</li>
                    @endif
                </ol>
            </nav>
        </div>

        <!-- Back to Subject Button (Only when topic is selected) -->
        @if(request('topic') && $topics->firstWhere('id', request('topic')))
            <div class="back-button-wrapper">
                <a href="{{ route('website.mcqs.subject-by-test-type', [$testType->slug, $subject->slug]) }}" 
                   class="back-btn">
                    <i class="fas fa-arrow-left"></i> 
                    Back to All {{ $subject->name }} Topics
                </a>
            </div>
        @endif

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                @if(request('topic'))
                    @php
                        $selectedTopic = $topics->firstWhere('id', request('topic'));
                    @endphp
                    
                    @if($selectedTopic)
                        <!-- Topic Content Section -->
                        <div class="topic-content-card" id="topicContent">
                            <div class="topic-content-header">
                                <h2>{{ $selectedTopic->title }}</h2>
                                <div class="topic-meta">
                                    @if($selectedTopic->estimated_time_minutes)
                                    <span class="topic-meta-badge">
                                        <i class="fas fa-clock"></i> 
                                        {{ $selectedTopic->estimated_time_minutes }} min
                                    </span>
                                    @endif
                                    <span class="topic-meta-badge">
                                        <i class="fas fa-question-circle"></i> 
                                        {{ $selectedTopic->mcqs_count }} Questions
                                    </span>
                                    <span class="topic-meta-badge difficulty-{{ $selectedTopic->difficulty_level }}">
                                        {{ ucfirst($selectedTopic->difficulty_level) }}
                                    </span>
                                </div>
                            </div>

                            <div class="topic-content-body">
                                @if($selectedTopic->description)
                                    <h4 class="mb-3">Overview</h4>
                                    <p class="topic-description-text">{{ $selectedTopic->description }}</p>
                                @endif

                                <!-- Topic Content -->
                                @if($selectedTopic->content)
                                    <div class="mb-4">
                                        <h4 class="mb-3">Topic Content</h4>
                                        <p class="d-inline-flex gap-1">
                                            <a class="btn btn-primary" data-bs-toggle="collapse" href="#topicContentCollapsible" role="button" aria-expanded="false" aria-controls="topicContentCollapsible">
                                                Show Full Content
                                            </a>
                                        </p>
                                        <div class="collapse" id="topicContentCollapsible">
                                            <div class="topic-content-wrapper">
                                                {!! $selectedTopic->content !!}
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        No detailed content available for this topic. You can start practicing directly.
                                    </div>
                                @endif

                                <div class="topic-actions">
                                    <div class="progress-indicator">
                                        <div class="progress-circle">
                                            <i class="fas fa-book-reader"></i>
                                        </div>
                                        <div>
                                            <strong>Read Before Practicing</strong>
                                            <span>Understand the topic first for better results</span>
                                        </div>
                                    </div>
                                    <button class="start-practice-btn" onclick="startPractice()">
                                        <i class="fas fa-play-circle"></i>
                                        Start Practice
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Questions Section (Hidden initially) -->
                        <div class="questions-section" id="questionsSection">
                            <div class="questions-header">
                                <div>
                                    <h5>Practicing: {{ $selectedTopic->title }}</h5>
                                    <small>{{ $selectedTopic->mcqs_count }} questions available</small>
                                </div>
                                <button class="back-to-content-btn" onclick="backToContent()">
                                    <i class="fas fa-arrow-left me-2"></i> Back to Content
                                </button>
                            </div>
                            
                            <!-- Questions List -->
                            @if($mcqs->count() > 0)
                               @foreach($mcqs as $index => $mcq)
                                    @php
                                        // Decode options properly
                                        $options = is_array($mcq->options) ? $mcq->options : (is_string($mcq->options) ? json_decode($mcq->options, true) : []);
                                        $options = is_array($options) ? $options : [];
                                        
                                        $correctAnswers = is_array($mcq->correct_answers) ? $mcq->correct_answers : (is_string($mcq->correct_answers) ? json_decode($mcq->correct_answers, true) : []);
                                        $correctAnswers = is_array($correctAnswers) ? $correctAnswers : [];
                                        
                                        $isMultiple = count($correctAnswers) > 1 || $mcq->question_type === 'multiple';
                                    @endphp
                                    
                                    <div class="question-card" id="mcq-{{ $mcq->uuid }}">
                                        <div class="d-flex align-items-start gap-3">
                                            <div class="question-counter">{{ ($mcqs->currentPage() - 1) * $mcqs->perPage() + $index + 1 }}</div>
                                            <div class="flex-grow-1">
                                                <div class="question-meta">
                                                    <span class="difficulty-badge {{ $mcq->difficulty_level }}">
                                                        {{ ucfirst($mcq->difficulty_level) }}
                                                    </span>
                                                    @if($mcq->topic)
                                                    <span class="topic-badge">
                                                        <i class="fas fa-folder"></i>{{ $mcq->topic->title }}
                                                    </span>
                                                    @endif
                                                    <span class="marks-badge">
                                                        <i class="fas fa-star"></i>{{ $mcq->marks }} Mark{{ $mcq->marks > 1 ? 's' : '' }}
                                                    </span>
                                                    <span id="result-{{ $mcq->uuid }}"></span>
                                                </div>
                                                
                                                <div class="question-text">
                                                    {!! $mcq->question !!}
                                                </div>

                                                <!-- Options Toggle Button -->
                                                <button class="options-toggle-btn" onclick="toggleOptions('{{ $mcq->uuid }}')">
                                                    <i class="fas fa-chevron-down" id="toggle-icon-{{ $mcq->uuid }}"></i>
                                                    <span>Show Options</span>
                                                </button>

                                                <!-- Options Container -->
                                                <div class="options-container" id="options-{{ $mcq->uuid }}" 
                                                    data-question-type="{{ $mcq->question_type }}">
                                                    @if(count($options) > 0)
                                                        @foreach($options as $key => $option)
                                                            @php
                                                                // Ensure key is treated as string for value
                                                                $optionKey = (string)$key;
                                                                // Calculate letter (A, B, C, D) - convert key to int for calculation
                                                                $letterNum = is_numeric($key) ? (int)$key : (array_search($key, array_keys($options)) + 1);
                                                                $letter = chr(64 + $letterNum);
                                                            @endphp
                                                            <div class="option-item">
                                                                <input type="{{ $isMultiple ? 'checkbox' : 'radio' }}" 
                                                                        id="mcq-{{ $mcq->uuid }}-option-{{ $optionKey }}" 
                                                                        name="mcq-{{ $mcq->uuid }}{{ $isMultiple ? '[]' : '' }}" 
                                                                        value="{{ $optionKey }}"
                                                                        class="option-input">
                                                                <label for="mcq-{{ $mcq->uuid }}-option-{{ $optionKey }}" class="option-label">
                                                                    <span class="option-marker">{{ $letter }}</span>
                                                                    {{ $option }}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <div class="alert alert-warning">
                                                            No options available for this question.
                                                        </div>
                                                    @endif
                                                </div>

                                                <!-- Action Buttons -->
                                                <div class="action-buttons">
                                                    <button class="check-btn" onclick="checkAnswer('{{ $mcq->uuid }}')">
                                                        <i class="fas fa-check"></i> Check
                                                    </button>
                                                    <button class="reset-btn" onclick="resetAnswer('{{ $mcq->uuid }}')">
                                                        <i class="fas fa-redo"></i> Reset
                                                    </button>
                                                    @if($mcq->hint)
                                                    <button class="hint-btn" onclick="showHint('{{ $mcq->uuid }}')">
                                                        <i class="fas fa-lightbulb"></i> Hint
                                                    </button>
                                                    @endif
                                                </div>

                                                <!-- Explanation -->
                                                @if($mcq->explanation)
                                                <div class="explanation-box" id="explanation-{{ $mcq->uuid }}">
                                                    <h6><i class="fas fa-info-circle"></i>Explanation</h6>
                                                    <div class="explanation-content">
                                                        {!! $mcq->explanation !!}
                                                    </div>
                                                    @if($mcq->reference_book)
                                                    <div class="mt-2">
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

                                                <!-- Hint -->
                                                @if($mcq->hint)
                                                <div class="hint-box" id="hint-{{ $mcq->uuid }}">
                                                    <h6><i class="fas fa-lightbulb"></i>Hint</h6>
                                                    <div class="hint-content">{{ $mcq->hint }}</div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="empty-state">
                                    <i class="fas fa-search"></i>
                                    <h5>No questions found for this topic</h5>
                                    <p>There are no questions available for "{{ $selectedTopic->title }}"</p>
                                </div>
                            @endif
                        </div>
                    @endif
                @else
                    <!-- Default View (When no topic selected) -->
                    
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

                    <!-- All Questions (When no specific topic selected) -->
                    @if($mcqs->count() > 0)
                    <div class="filter-card">
                        <div class="filter-header">
                            <h5><i class="fas fa-question-circle me-2"></i>All Practice Questions</h5>
                        </div>
                        <div class="filter-body">
                            @foreach($mcqs as $index => $mcq)
                                @php
                                    // Decode options properly
                                    $options = is_array($mcq->options) ? $mcq->options : (is_string($mcq->options) ? json_decode($mcq->options, true) : []);
                                    $options = is_array($options) ? $options : [];
                                    
                                    $correctAnswers = is_array($mcq->correct_answers) ? $mcq->correct_answers : (is_string($mcq->correct_answers) ? json_decode($mcq->correct_answers, true) : []);
                                    $correctAnswers = is_array($correctAnswers) ? $correctAnswers : [];
                                    
                                    $isMultiple = count($correctAnswers) > 1 || $mcq->question_type === 'multiple';
                                @endphp
                                
                                <div class="question-card" id="mcq-{{ $mcq->uuid }}">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="question-counter">{{ ($mcqs->currentPage() - 1) * $mcqs->perPage() + $index + 1 }}</div>
                                        <div class="flex-grow-1">
                                            <div class="question-meta">
                                                <span class="difficulty-badge {{ $mcq->difficulty_level }}">
                                                    {{ ucfirst($mcq->difficulty_level) }}
                                                </span>
                                                @if($mcq->topic)
                                                <span class="topic-badge">
                                                    <i class="fas fa-folder"></i>{{ $mcq->topic->title }}
                                                </span>
                                                @endif
                                                <span class="marks-badge">
                                                    <i class="fas fa-star"></i>{{ $mcq->marks }} Mark{{ $mcq->marks > 1 ? 's' : '' }}
                                                </span>
                                                <span id="result-{{ $mcq->uuid }}"></span>
                                            </div>
                                            
                                            <div class="question-text">
                                                {!! $mcq->question !!}
                                            </div>

                                            <!-- Options Toggle Button -->
                                            <button class="options-toggle-btn" onclick="toggleOptions('{{ $mcq->uuid }}')">
                                                <i class="fas fa-chevron-down" id="toggle-icon-{{ $mcq->uuid }}"></i>
                                                <span>Show Options</span>
                                            </button>

                                            <!-- Options Container -->
                                            <div class="options-container" id="options-{{ $mcq->uuid }}" 
                                                data-question-type="{{ $mcq->question_type }}">
                                                @if(count($options) > 0)
                                                    @foreach($options as $key => $option)
                                                        @php
                                                            // Ensure key is treated as string for value
                                                            $optionKey = (string)$key;
                                                            // Calculate letter (A, B, C, D) - convert key to int for calculation
                                                            $letterNum = is_numeric($key) ? (int)$key : (array_search($key, array_keys($options)) + 1);
                                                            $letter = chr(64 + $letterNum);
                                                        @endphp
                                                        <div class="option-item">
                                                            <input type="{{ $isMultiple ? 'checkbox' : 'radio' }}" 
                                                                    id="mcq-{{ $mcq->uuid }}-option-{{ $optionKey }}" 
                                                                    name="mcq-{{ $mcq->uuid }}{{ $isMultiple ? '[]' : '' }}" 
                                                                    value="{{ $optionKey }}"
                                                                    class="option-input">
                                                            <label for="mcq-{{ $mcq->uuid }}-option-{{ $optionKey }}" class="option-label">
                                                                <span class="option-marker">{{ $letter }}</span>
                                                                {{ $option }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="alert alert-warning">
                                                        No options available for this question.
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Action Buttons -->
                                            <div class="action-buttons">
                                                <button class="check-btn" onclick="checkAnswer('{{ $mcq->uuid }}')">
                                                    <i class="fas fa-check"></i> Check
                                                </button>
                                                <button class="reset-btn" onclick="resetAnswer('{{ $mcq->uuid }}')">
                                                    <i class="fas fa-redo"></i> Reset
                                                </button>
                                                @if($mcq->hint)
                                                <button class="hint-btn" onclick="showHint('{{ $mcq->uuid }}')">
                                                    <i class="fas fa-lightbulb"></i> Hint
                                                </button>
                                                @endif
                                            </div>

                                            <!-- Explanation -->
                                            @if($mcq->explanation)
                                            <div class="explanation-box" id="explanation-{{ $mcq->uuid }}">
                                                <h6><i class="fas fa-info-circle"></i>Explanation</h6>
                                                <div class="explanation-content">
                                                    {!! $mcq->explanation !!}
                                                </div>
                                                @if($mcq->reference_book)
                                                <div class="mt-2">
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

                                            <!-- Hint -->
                                            @if($mcq->hint)
                                            <div class="hint-box" id="hint-{{ $mcq->uuid }}">
                                                <h6><i class="fas fa-lightbulb"></i>Hint</h6>
                                                <div class="hint-content">{{ $mcq->hint }}</div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <!-- Pagination and Navigation -->
                            <div class="test-navigation mt-4">
                                <div class="navigation-info">
                                    <span>Showing {{ $mcqs->firstItem() }} to {{ $mcqs->lastItem() }} of {{ $mcqs->total() }} MCQs</span>
                                    <span class="badge">Page {{ $mcqs->currentPage() }} of {{ $mcqs->lastPage() }}</span>
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
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="filter-card">
                        <div class="filter-body">
                            <div class="empty-state">
                                <i class="fas fa-search"></i>
                                <h5>No questions found</h5>
                                <p>Try selecting a topic or adjusting your filters</p>
                            </div>
                        </div>
                    </div>
                    @endif
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Quick Stats -->
                <div class="sidebar-widget mb-4">
                    <div class="card-header">
                        <h5><i class="fas fa-chart-bar me-2"></i>Quick Stats</h5>
                    </div>
                    <div class="card-body">
                        <div class="quick-stats">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted">Total Questions</span>
                                <span class="h5 mb-0">{{ $difficultyStats->sum('count') }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted">Topics</span>
                                <span class="h5 mb-0">{{ $topics->count() }}</span>
                            </div>
                            <div class="stats-badge easy mb-2">
                                <i class="fas fa-check-circle me-2"></i>
                                Easy: {{ $difficultyStats['easy']->count ?? 0 }} questions
                            </div>
                            <div class="stats-badge medium mb-2">
                                <i class="fas fa-circle me-2"></i>
                                Medium: {{ $difficultyStats['medium']->count ?? 0 }} questions
                            </div>
                            <div class="stats-badge hard">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                Hard: {{ $difficultyStats['hard']->count ?? 0 }} questions
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recommended Topics -->
                @if($topics->count() > 0)
                <div class="sidebar-widget mb-4">
                    <div class="card-header">
                        <h5><i class="fas fa-fire me-2"></i>Recommended Topics</h5>
                    </div>
                    <div class="card-body">
                        <div class="recommended-list">
                            @foreach($topics->take(5) as $topic)
                            <a href="{{ request()->fullUrlWithQuery(['topic' => $topic->id]) }}" 
                               class="recommended-item">
                                <div class="topic-info">
                                    <span class="topic-title">{{ $topic->title }}</span>
                                    <span class="topic-level">{{ ucfirst($topic->difficulty_level) }}</span>
                                </div>
                                <span class="topic-count">{{ $topic->mcqs_count }}</span>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Quick Actions -->
                <div class="sidebar-widget">
                    <div class="card-header">
                        <h5><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="quick-actions">
                            <a href="{{ route('website.mcqs.mock-tests', ['test_type' => $testType->slug]) }}" class="quick-action-btn primary">
                                <i class="fas fa-clipboard-list"></i>Take Mock Test
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['difficulty' => 'random']) }}" class="quick-action-btn outline-primary">
                                <i class="fas fa-random"></i>Random Questions
                            </a>
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
    // Start Practice - Show questions
    function startPractice() {
        document.getElementById('topicContent').style.display = 'none';
        document.getElementById('questionsSection').classList.add('active');
        
        // Save that user has read the content
        @if(request('topic'))
            localStorage.setItem(`topic_read_{{ request('topic') }}`, 'true');
        @endif
        
        // Scroll to top of questions
        document.getElementById('questionsSection').scrollIntoView({ behavior: 'smooth' });
    }

    // Back to Content
    function backToContent() {
        document.getElementById('questionsSection').classList.remove('active');
        document.getElementById('topicContent').style.display = 'block';
        
        // Scroll to top of content
        document.getElementById('topicContent').scrollIntoView({ behavior: 'smooth' });
    }

    // Toggle Options
    function toggleOptions(mcqUuid) {
        const optionsContainer = document.getElementById(`options-${mcqUuid}`);
        const toggleIcon = document.getElementById(`toggle-icon-${mcqUuid}`);
        const toggleBtn = optionsContainer.previousElementSibling;
        
        if (optionsContainer.classList.contains('expanded')) {
            optionsContainer.classList.remove('expanded');
            toggleIcon.className = 'fas fa-chevron-down';
            toggleBtn.querySelector('span').textContent = 'Show Options';
        } else {
            optionsContainer.classList.add('expanded');
            toggleIcon.className = 'fas fa-chevron-up';
            toggleBtn.querySelector('span').textContent = 'Hide Options';
        }
    }

    // Check answer
    async function checkAnswer(mcqUuid) {
        const questionElement = document.getElementById(`mcq-${mcqUuid}`);
        const optionsContainer = questionElement.querySelector('.options-container');
        const questionType = optionsContainer.dataset.questionType;
        
        let selectedOptions = [];
        
        // Get selected answers - handle both single and multiple
        if (questionType === 'multiple') {
            questionElement.querySelectorAll(`.option-input:checked`).forEach(input => {
                selectedOptions.push(input.value);
            });
        } else {
            const selected = questionElement.querySelector(`.option-input:checked`);
            if (selected) {
                selectedOptions.push(selected.value);
            }
        }

        if (selectedOptions.length === 0) {
            alert('Please select an answer first!');
            return;
        }

        // Show loading
        const checkBtn = questionElement.querySelector('.check-btn');
        const originalText = checkBtn.innerHTML;
        checkBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Checking...';
        checkBtn.disabled = true;

        try {
            // Send request to check answer
            const response = await fetch(`/mcqs/practice/${mcqUuid}/check`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    selected_answers: selectedOptions,
                    time_taken: 0
                })
            });

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            const data = await response.json();

            // Show explanation
            const explanationBox = document.getElementById(`explanation-${mcqUuid}`);
            if (explanationBox) {
                explanationBox.classList.add('show');
            }

            // Mark correct/incorrect options
            const correctAnswers = data.correct_answers;
            questionElement.querySelectorAll('.option-item').forEach(item => {
                const input = item.querySelector('.option-input');
                const label = item.querySelector('.option-label');
                const marker = item.querySelector('.option-marker');
                
                if (correctAnswers.includes(input.value)) {
                    // Mark correct answer
                    label.style.backgroundColor = '#d4edda';
                    label.style.borderColor = '#28a745';
                    label.style.color = '#155724';
                    marker.style.backgroundColor = '#28a745';
                    marker.style.color = 'white';
                } else if (selectedOptions.includes(input.value) && !data.correct) {
                    // Mark incorrect user selection
                    label.style.backgroundColor = '#f8d7da';
                    label.style.borderColor = '#dc3545';
                    label.style.color = '#721c24';
                    marker.style.backgroundColor = '#dc3545';
                    marker.style.color = 'white';
                }
            });

            // Disable all inputs
            questionElement.querySelectorAll('.option-input').forEach(input => {
                input.disabled = true;
            });

            // Show result badge
            const resultBadge = document.getElementById(`result-${mcqUuid}`);
            if (data.correct) {
                resultBadge.innerHTML = '<span class="result-badge correct"><i class="fas fa-check me-1"></i>Correct</span>';
            } else {
                resultBadge.innerHTML = '<span class="result-badge incorrect"><i class="fas fa-times me-1"></i>Incorrect</span>';
            }

        } catch (error) {
            console.error('Error checking answer:', error);
            alert('Error checking answer. Please try again.');
        } finally {
            // Reset button
            checkBtn.innerHTML = originalText;
            checkBtn.disabled = false;
        }
    }

    // Reset answer
    function resetAnswer(mcqUuid) {
        const questionElement = document.getElementById(`mcq-${mcqUuid}`);
        
        // Clear all selections
        questionElement.querySelectorAll('.option-input').forEach(input => {
            input.checked = false;
            input.disabled = false;
        });
        
        // Reset styles
        questionElement.querySelectorAll('.option-label').forEach(label => {
            label.style.backgroundColor = '';
            label.style.borderColor = '';
            label.style.color = '';
        });
        
        questionElement.querySelectorAll('.option-marker').forEach(marker => {
            marker.style.backgroundColor = '';
            marker.style.color = '';
        });
        
        // Hide explanation and hint
        const explanationBox = document.getElementById(`explanation-${mcqUuid}`);
        if (explanationBox) {
            explanationBox.classList.remove('show');
        }
        
        const hintBox = document.getElementById(`hint-${mcqUuid}`);
        if (hintBox) {
            hintBox.classList.remove('show');
        }
        
        // Clear result badge
        const resultBadge = document.getElementById(`result-${mcqUuid}`);
        resultBadge.innerHTML = '';
    }

    // Show hint
    function showHint(mcqUuid) {
        const hintBox = document.getElementById(`hint-${mcqUuid}`);
        if (hintBox) {
            hintBox.classList.toggle('show');
        }
    }

    // Auto-expand options when an option is clicked
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('option-input') || e.target.closest('.option-label')) {
            const questionElement = e.target.closest('.question-card');
            if (questionElement) {
                const uuid = questionElement.id.replace('mcq-', '');
                const optionsContainer = document.getElementById(`options-${uuid}`);
                
                if (optionsContainer && !optionsContainer.classList.contains('expanded')) {
                    toggleOptions(uuid);
                }
            }
        }
    });

    // On page load
    document.addEventListener('DOMContentLoaded', function() {
        @if(request('topic'))
            // Check if user already read the topic
            const hasRead = localStorage.getItem(`topic_read_{{ request('topic') }}`);
            if (hasRead === 'true') {
                // User already read the content, show questions directly
                document.getElementById('topicContent').style.display = 'none';
                document.getElementById('questionsSection').classList.add('active');
            }
        @endif
    });
</script>
@endpush