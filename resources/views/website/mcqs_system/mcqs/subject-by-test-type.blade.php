@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<style>
    .difficulty-filter {
        display: inline-block;
        padding: 8px 20px;
        margin: 5px;
        border-radius: 25px;
        border: 2px solid #dee2e6;
        background: white;
        color: #495057;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .difficulty-filter:hover {
        border-color: #4361ee;
        color: #4361ee;
    }

    .difficulty-filter.active {
        background: #4361ee;
        border-color: #4361ee;
        color: white;
    }

    .question-preview {
        background: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        border: 1px solid #e9ecef;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }

    .question-preview:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }

    .mcq-counter {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 35px;
        height: 35px;
        background: #4361ee;
        color: white;
        border-radius: 50%;
        font-weight: bold;
        margin-right: 15px;
    }

    .topic-filter {
        padding: 8px 15px;
        margin: 3px;
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 20px;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }

    .topic-filter:hover {
        background: #e9ecef;
        border-color: #4361ee;
    }

    .topic-filter.active {
        background: #4361ee;
        color: white;
        border-color: #4361ee;
    }

    .stats-badge {
        padding: 10px 15px;
        border-radius: 10px;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .stats-badge.easy { background: #d4edda; color: #155724; }
    .stats-badge.medium { background: #fff3cd; color: #856404; }
    .stats-badge.hard { background: #f8d7da; color: #721c24; }

    /* Collapsible Options Styles */
    .options-toggle-btn {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 8px 15px;
        font-size: 0.9rem;
        color: #4361ee;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        margin: 10px 0;
    }

    .options-toggle-btn:hover {
        background: #e9ecef;
        border-color: #4361ee;
    }

    .options-container {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.5s ease;
        margin: 15px 0;
    }

    .options-container.expanded {
        max-height: 500px;
    }

    .option-item {
        margin-bottom: 10px;
        position: relative;
    }

    .option-label {
        display: block;
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        padding: 12px 15px 12px 45px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
        position: relative;
    }

    .option-label:hover {
        border-color: #4361ee;
        background: #f8fafc;
    }

    .option-input {
        display: none;
    }

    .option-input:checked + .option-label {
        background: #e8f4ff;
        border-color: #4361ee;
        color: #4361ee;
    }

    .option-marker {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        width: 24px;
        height: 24px;
        border: 2px solid #cbd5e0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        background: white;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }

    .option-input:checked + .option-label .option-marker {
        background: #4361ee;
        color: white;
        border-color: #4361ee;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }

    .check-btn, .reset-btn {
        padding: 8px 20px;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.9rem;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .check-btn {
        background: #4361ee;
        color: white;
    }

    .check-btn:hover {
        background: #3a0ca3;
    }

    .reset-btn {
        background: #6c757d;
        color: white;
    }

    .reset-btn:hover {
        background: #545b62;
    }

    .explanation-box {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-top: 15px;
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

    .hint-box {
        background: #fff3cd;
        border-radius: 8px;
        padding: 15px;
        margin-top: 10px;
        border-left: 4px solid #ffc107;
        display: none;
    }

    .hint-box.show {
        display: block;
    }

    .question-text {
        font-size: 1rem;
        line-height: 1.6;
        color: #2c3e50;
        margin-bottom: 15px;
    }

    .question-text img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 10px 0;
    }

    .result-badge {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-block;
        margin-left: 10px;
    }

    .result-correct {
        background: #d4edda;
        color: #155724;
    }

    .result-incorrect {
        background: #f8d7da;
        color: #721c24;
    }

    .mcq-meta {
        display: flex;
        gap: 10px;
        align-items: center;
        margin-bottom: 10px;
    }

    .difficulty-badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .difficulty-easy { background: #d1fae5; color: #065f46; }
    .difficulty-medium { background: #fef3c7; color: #92400e; }
    .difficulty-hard { background: #fee2e2; color: #991b1b; }

    /* Topic Content Styles */
    .topic-content-section {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
    }

    .topic-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 25px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f0f0f0;
    }

    .topic-title {
        color: #2c3e50;
        margin: 0;
        font-weight: 700;
        font-size: 1.8rem;
    }

    .topic-meta {
        display: flex;
        gap: 15px;
        align-items: center;
        flex-wrap: wrap;
    }

    .topic-meta-badge {
        background: #e8f4ff;
        color: #4361ee;
        padding: 8px 18px;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .topic-description-text {
        line-height: 1.7;
        color: #4a5568;
        font-size: 1.05rem;
        margin-bottom: 20px;
    }

    .topic-content-wrapper {
        line-height: 1.8;
        color: #4a5568;
        font-size: 1.05rem;
    }

    .topic-content-wrapper img {
        max-width: 100%;
        height: auto;
        border-radius: 10px;
        margin: 20px 0;
    }

    .topic-content-wrapper h1,
    .topic-content-wrapper h2,
    .topic-content-wrapper h3 {
        color: #2c3e50;
        margin-top: 25px;
        margin-bottom: 15px;
        font-weight: 600;
    }

    .topic-content-wrapper ul,
    .topic-content-wrapper ol {
        padding-left: 25px;
        margin: 15px 0;
    }

    .topic-content-wrapper li {
        margin-bottom: 8px;
    }

    .topic-content-wrapper table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
    }

    .topic-content-wrapper table th,
    .topic-content-wrapper table td {
        border: 1px solid #e2e8f0;
        padding: 12px;
        text-align: left;
    }

    .topic-content-wrapper table th {
        background: #f7fafc;
        font-weight: 600;
    }

    .topic-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 2px solid #f0f0f0;
    }

    .start-practice-btn {
        background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .start-practice-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(67, 97, 238, 0.3);
    }

    .back-to-topics-btn {
        background: #6c757d;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .back-to-topics-btn:hover {
        background: #545b62;
    }

    .progress-indicator {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #718096;
        font-size: 0.9rem;
    }

    .progress-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e8f4ff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: #4361ee;
    }

    /* Questions Section */
    .questions-section {
        display: none;
    }

    .questions-section.active {
        display: block;
        animation: fadeInSlide 0.5s ease;
    }

    @keyframes fadeInSlide {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .back-to-subject-btn {
        background: #6c757d;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        display: inline-block;
    }

    .back-to-subject-btn:hover {
        background: #545b62;
        color: white;
        text-decoration: none;
    }

    /* Breadcrumb improvement */
    .breadcrumb-item.active {
        color: #4361ee;
        font-weight: 500;
    }

    /* Difficulty badges for topics */
    .difficulty-beginner { background: #d1fae5; color: #065f46; }
    .difficulty-intermediate { background: #fef3c7; color: #92400e; }
    .difficulty-advanced { background: #fee2e2; color: #991b1b; }

    /* Responsive improvements */
    @media (max-width: 768px) {
        .topic-header {
            flex-direction: column;
            gap: 15px;
        }
        
        .topic-meta {
            width: 100%;
            justify-content: flex-start;
        }
        
        .topic-actions {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }
        
        .start-practice-btn {
            width: 100%;
            justify-content: center;
        }
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

        <!-- Back to Subject Button (Only when topic is selected) -->
        @if(request('topic') && $topics->firstWhere('id', request('topic')))
            <div class="mb-4">
                <a href="{{ route('subject-by-test-type', ['test_type' => $testType->slug, 'subject' => $subject->slug]) }}" 
                   class="back-to-subject-btn">
                    <i class="fas fa-arrow-left me-2"></i> 
                    Back to All {{ $subject->name }} Topics
                </a>
            </div>
        @endif

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Subject Header -->
                <div class="d-flex align-items-center mb-4">
                    <div class="subject-icon-large me-4" style="background: {{ $subject->color_code }}; color: white; width: 80px; height: 80px; border-radius: 15px; display: flex; align-items: center; justify-content: center; font-size: 2rem;">
                        <i class="{{ $subject->icon ?? 'fas fa-book' }}"></i>
                    </div>
                    <div>
                        <h1 class="h2 mb-2">{{ $subject->name }}</h1>
                        <p class="text-muted mb-0">{{ $subject->description }}</p>
                    </div>
                </div>

                @if(request('topic'))
                    @php
                        $selectedTopic = $topics->firstWhere('id', request('topic'));
                    @endphp
                    
                    @if($selectedTopic)
                        <!-- Topic Content Section -->
                        <div class="topic-content-section" id="topicContent">
                            <div class="topic-header">
                                <h2 class="topic-title">{{ $selectedTopic->title }}</h2>
                                <div class="topic-meta">
                                    @if($selectedTopic->estimated_time_minutes)
                                    <span class="topic-meta-badge">
                                        <i class="fas fa-clock me-1"></i> 
                                        {{ $selectedTopic->estimated_time_minutes }} min read
                                    </span>
                                    @endif
                                    <span class="topic-meta-badge">
                                        <i class="fas fa-question-circle me-1"></i> 
                                        {{ $selectedTopic->mcqs_count }} Questions
                                    </span>
                                    <span class="topic-meta-badge difficulty-{{ $selectedTopic->difficulty_level }}">
                                        {{ ucfirst($selectedTopic->difficulty_level) }}
                                    </span>
                                </div>
                            </div>

                            @if($selectedTopic->description)
                                <div class="mb-4">
                                    <h4 class="mb-3">Overview</h4>
                                    <p class="topic-description-text">{{ $selectedTopic->description }}</p>
                                </div>
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
                                        <div>Understand the topic first for better results</div>
                                    </div>
                                </div>
                                <button class="start-practice-btn" onclick="startPractice()">
                                    <i class="fas fa-play-circle"></i>
                                    Start Practice
                                </button>
                            </div>
                        </div>

                        <!-- Questions Section (Hidden initially) -->
                        <div class="questions-section" id="questionsSection">
                            <div class="card shadow-sm mb-4">
                                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-0">Practicing: {{ $selectedTopic->title }}</h5>
                                        <small class="text-muted">{{ $selectedTopic->mcqs_count }} questions available</small>
                                    </div>
                                    <button class="back-to-topics-btn" onclick="backToContent()">
                                        <i class="fas fa-arrow-left me-2"></i> Back to Content
                                    </button>
                                </div>
                                
                                <div class="card-body">
                                    <!-- Questions List -->
                                    @if($mcqs->count() > 0)
                                        @foreach($mcqs as $index => $mcq)
                                            @php
                                                $options = is_array($mcq->options) ? $mcq->options : [];
                                            @endphp
                                            
                                            <div class="question-preview" id="mcq-{{ $mcq->uuid }}">
                                                <div class="d-flex align-items-start mb-3">
                                                    <div class="mcq-counter">{{ $index + 1 }}</div>
                                                    <div class="flex-grow-1">
                                                        <div class="mcq-meta">
                                                            <span class="difficulty-badge difficulty-{{ $mcq->difficulty_level }}">
                                                                {{ ucfirst($mcq->difficulty_level) }}
                                                            </span>
                                                            <span class="badge bg-light text-dark">
                                                                <i class="fas fa-star me-1"></i>{{ $mcq->marks }} Mark{{ $mcq->marks > 1 ? 's' : '' }}
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
                                                                @foreach($options as $optIndex => $option)
                                                                <div class="option-item">
                                                                    <input type="{{ $mcq->question_type == 'multiple' ? 'checkbox' : 'radio' }}" 
                                                                           id="mcq-{{ $mcq->uuid }}-option-{{ $optIndex }}" 
                                                                           name="mcq-{{ $mcq->uuid }}" 
                                                                           value="{{ $optIndex }}"
                                                                           class="option-input">
                                                                    <label for="mcq-{{ $mcq->uuid }}-option-{{ $optIndex }}" class="option-label">
                                                                        <span class="option-marker">{{ chr(65 + $optIndex) }}</span>
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
                                                                <i class="fas fa-check"></i> Check Answer
                                                            </button>
                                                            <button class="reset-btn" onclick="resetAnswer('{{ $mcq->uuid }}')">
                                                                <i class="fas fa-redo"></i> Reset
                                                            </button>
                                                            @if($mcq->hint)
                                                            <button class="btn btn-sm btn-outline-warning" onclick="showHint('{{ $mcq->uuid }}')">
                                                                <i class="fas fa-lightbulb"></i> Hint
                                                            </button>
                                                            @endif
                                                        </div>

                                                        <!-- Explanation -->
                                                        @if($mcq->explanation)
                                                        <div class="explanation-box" id="explanation-{{ $mcq->uuid }}">
                                                            <h6 class="mb-2"><i class="fas fa-info-circle text-success me-2"></i>Explanation</h6>
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
                                                            <h6 class="mb-1"><i class="fas fa-lightbulb text-warning me-2"></i>Hint</h6>
                                                            <div class="hint-content">{{ $mcq->hint }}</div>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                    <div class="text-center py-5">
                                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No questions found for this topic</h5>
                                        <p class="text-muted">There are no questions available for "{{ $selectedTopic->title }}"</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <!-- Default View (When no topic selected) -->
                    
                <!-- Difficulty Filters -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Filter by Difficulty</h5>
                        <div class="text-center">
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
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Topics</h5>
                        <div class="d-flex flex-wrap">
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
                <div class="card shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">All Practice Questions</h5>
                        <span class="badge bg-primary">
                            {{ $mcqs->total() }} Questions
                        </span>
                    </div>
                    <div class="card-body">
                        @foreach($mcqs as $index => $mcq)
                            @php
                                $options = is_array($mcq->options) ? $mcq->options : [];
                            @endphp
                            
                            <div class="question-preview" id="mcq-{{ $mcq->uuid }}">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="mcq-counter">{{ ($mcqs->currentPage() - 1) * $mcqs->perPage() + $index + 1 }}</div>
                                    <div class="flex-grow-1">
                                        <div class="mcq-meta">
                                            <span class="difficulty-badge difficulty-{{ $mcq->difficulty_level }}">
                                                {{ ucfirst($mcq->difficulty_level) }}
                                            </span>
                                            @if($mcq->topic)
                                            <span class="badge bg-light text-dark">
                                                <i class="fas fa-folder me-1"></i>{{ $mcq->topic->title }}
                                            </span>
                                            @endif
                                            <span class="badge bg-light text-dark">
                                                <i class="fas fa-star me-1"></i>{{ $mcq->marks }} Mark{{ $mcq->marks > 1 ? 's' : '' }}
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

                                        <!-- Options Container (Collapsed by default) -->
                                        <div class="options-container" id="options-{{ $mcq->uuid }}" 
                                            data-question-type="{{ $mcq->question_type }}">
                                        @if(count($options) > 0)
                                            @foreach($options as $optIndex => $option)
                                            <div class="option-item">
                                                <input type="{{ $mcq->question_type == 'multiple' ? 'checkbox' : 'radio' }}" 
                                                        id="mcq-{{ $mcq->uuid }}-option-{{ $optIndex }}" 
                                                        name="mcq-{{ $mcq->uuid }}" 
                                                        value="{{ $optIndex }}"
                                                        class="option-input">
                                                <label for="mcq-{{ $mcq->uuid }}-option-{{ $optIndex }}" class="option-label">
                                                    <span class="option-marker">{{ chr(65 + $optIndex) }}</span>
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
                                                <i class="fas fa-check"></i> Check Answer
                                            </button>
                                            <button class="reset-btn" onclick="resetAnswer('{{ $mcq->uuid }}')">
                                                <i class="fas fa-redo"></i> Reset
                                            </button>
                                            @if($mcq->hint)
                                            <button class="btn btn-sm btn-outline-warning" onclick="showHint('{{ $mcq->uuid }}')">
                                                <i class="fas fa-lightbulb"></i> Hint
                                            </button>
                                            @endif
                                        </div>

                                        <!-- Explanation (Initially Hidden) -->
                                        @if($mcq->explanation)
                                        <div class="explanation-box" id="explanation-{{ $mcq->uuid }}">
                                            <h6 class="mb-2"><i class="fas fa-info-circle text-success me-2"></i>Explanation</h6>
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

                                        <!-- Hint (Initially Hidden) -->
                                        @if($mcq->hint)
                                        <div class="hint-box" id="hint-{{ $mcq->uuid }}">
                                            <h6 class="mb-1"><i class="fas fa-lightbulb text-warning me-2"></i>Hint</h6>
                                            <div class="hint-content">{{ $mcq->hint }}</div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <!-- Pagination -->
                        @if($mcqs->hasPages())
                        <div class="mt-4">
                            {{ $mcqs->links('pagination::bootstrap-5') }}
                        </div>
                        @endif
                    </div>
                </div>
                @else
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No questions found</h5>
                        <p class="text-muted">Try selecting a topic or adjusting your filters</p>
                    </div>
                </div>
                @endif
            @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Quick Stats -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Quick Stats</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="text-center p-3 bg-light rounded">
                                    <div class="h3 mb-1">{{ $difficultyStats->sum('count') }}</div>
                                    <div class="text-muted small">Total Questions</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-3 bg-light rounded">
                                    <div class="h3 mb-1">{{ $topics->count() }}</div>
                                    <div class="text-muted small">Topics</div>
                                </div>
                            </div>
                            <div class="col-12">
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
                </div>

                <!-- Recommended Practice -->
                @if($topics->count() > 0)
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-fire me-2"></i>Recommended Topics</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            @foreach($topics->take(5) as $topic)
                            <a href="{{ request()->fullUrlWithQuery(['topic' => $topic->id]) }}" 
                               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div>
                                    <span>{{ $topic->title }}</span>
                                    <small class="text-muted d-block">{{ $topic->difficulty_level }}</small>
                                </div>
                                <span class="badge bg-primary rounded-pill">{{ $topic->mcqs_count }}</span>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Quick Actions -->
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="mb-3">Quick Actions</h5>
                        <a href="{{ route('website.mcqs.mock-tests', ['test_type' => $testType->slug]) }}" class="btn btn-primary btn-lg w-100 mb-3">
                            <i class="fas fa-clipboard-list me-2"></i>Take Mock Test
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['difficulty' => 'random']) }}" class="btn btn-outline-primary btn-lg w-100">
                            <i class="fas fa-random me-2"></i>Random Questions
                        </a>
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

    // MCQ Functions
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
        const questionType = questionElement.querySelector('.options-container').dataset.questionType;
        
        let selectedOptions = [];
        
        // Get selected answers
        if (questionType === 'multiple') {
            questionElement.querySelectorAll(`input[name="mcq-${mcqUuid}"]:checked`).forEach(input => {
                selectedOptions.push(input.value);
            });
        } else {
            const selected = questionElement.querySelector(`input[name="mcq-${mcqUuid}"]:checked`);
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
                resultBadge.innerHTML = '<span class="result-badge result-correct"><i class="fas fa-check me-1"></i>Correct</span>';
            } else {
                resultBadge.innerHTML = '<span class="result-badge result-incorrect"><i class="fas fa-times me-1"></i>Incorrect</span>';
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
        if (e.target.classList.contains('option-input')) {
            const questionElement = e.target.closest('.question-preview');
            const uuid = questionElement.id.replace('mcq-', '');
            const optionsContainer = document.getElementById(`options-${uuid}`);
            
            if (!optionsContainer.classList.contains('expanded')) {
                toggleOptions(uuid);
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