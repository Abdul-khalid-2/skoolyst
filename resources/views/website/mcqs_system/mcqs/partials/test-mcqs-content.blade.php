@if(isset($mcqs) && $mcqs->count() > 0)
    <form id="testForm" method="POST" action="{{ route('website.mcqs.submit-test') }}">
        @csrf
        <input type="hidden" name="topic_id" value="{{ request()->get('topic') }}">
        <input type="hidden" name="subject_id" value="{{ $subject->id ?? '' }}">
        <input type="hidden" name="test_type_id" value="{{ $testType->id ?? '' }}">
        <input type="hidden" name="time_taken" id="timeTaken" value="0">
        
        @foreach($mcqs as $index => $mcq)
        <div class="question-card" id="question-{{ $mcq->id }}" data-question-id="{{ $mcq->id }}">
            <div class="question-header">
                <span class="question-number">{{ $index + 1 + (($mcqs->currentPage() - 1) * $mcqs->perPage()) }}</span>
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
                    <span id="answeredCount">0</span>/<span id="totalQuestions">{{ $mcqs->total() }}</span> Answered
                </span>
                <span>
                    <i class="fas fa-clock me-1"></i>
                    <span id="timer">00:00</span>
                </span>
            </div>
            
            <div class="pagination-buttons">
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary prev-page" {{ $mcqs->onFirstPage() ? 'disabled' : '' }} data-page="{{ $mcqs->currentPage() - 1 }}">
                        <i class="fas fa-arrow-left"></i> Previous
                    </button>
                    
                    <button type="button" class="btn btn-primary next-page" {{ !$mcqs->hasMorePages() ? 'disabled' : '' }} data-page="{{ $mcqs->currentPage() + 1 }}">
                        Next <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="button" class="clear-btn" onclick="clearTest()">
                        <i class="fas fa-undo"></i> Clear
                    </button>
                    
                    <button type="button" class="btn btn-success" onclick="submitTest()">
                        <i class="fas fa-check-circle"></i> Submit Test
                    </button>
                </div>
            </div>
        </div>
    </form>
@else
    <div class="empty-state">
        <i class="fas fa-question-circle"></i>
        <h5>No questions available</h5>
        <p>Questions for this topic will be added soon.</p>
    </div>
@endif