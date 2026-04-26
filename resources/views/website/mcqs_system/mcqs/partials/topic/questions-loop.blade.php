@foreach($mcqs as $index => $mcq)
    <div class="question-card" id="question-{{ $mcq->id }}" data-question-id="{{ $mcq->id }}">
        <div class="question-header">
            <span class="question-number">{{ ($mcqs->currentPage() - 1) * $mcqs->perPage() + $index + 1 }}</span>
            <div class="question-text">{!! $mcq->question !!}</div>
        </div>

        <div class="question-meta">
            <span class="difficulty-badge {{ $mcq->difficulty_value }}">
                {{ $mcq->difficulty_label }}
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

        @if($mcq->hint)
        <div class="hint-section" id="hint-{{ $mcq->id }}">
            <i class="fas fa-lightbulb"></i>
            <span>{{ $mcq->hint }}</span>
        </div>
        @endif

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
                <span class="option-letter">{{ chr(65 + $loop->index) }}</span>
                <span class="option-text">{{ $option }}</span>
            </div>
            @endforeach
        </div>
    </div>
@endforeach
