@forelse($testResults['results'] as $index => $result)
    <div class="question-review-item {{ $result['is_correct'] ? 'correct' : 'incorrect' }}">
        <div class="question-review-header">
            <h6>Question {{ $index + 1 }}</h6>
            <span class="result-badge {{ $result['is_correct'] ? 'correct' : 'incorrect' }}">
                {{ $result['is_correct'] ? 'Correct' : 'Incorrect' }}
            </span>
        </div>

        <div class="question-text">
            {!! $result['mcq']->question !!}
        </div>

        <div class="options-review">
            <h6>Options:</h6>
            @php
                $options = is_array($result['mcq']->options) ? $result['mcq']->options : json_decode($result['mcq']->options, true);
            @endphp

            @foreach($options as $key => $option)
                @php
                    $isCorrectAnswer = in_array($key, $result['correct_answers']);
                    $isUserAnswer = false;
                    if ($result['user_answer']) {
                        if (is_array($result['user_answer'])) {
                            $isUserAnswer = in_array($key, $result['user_answer']);
                        } else {
                            $isUserAnswer = $key == $result['user_answer'];
                        }
                    }
                @endphp

                <div class="option-review-item
                    {{ $isCorrectAnswer ? 'correct-answer' : '' }}
                    {{ $isUserAnswer && !$result['is_correct'] ? 'user-answer incorrect' : '' }}
                    {{ $isUserAnswer && $result['is_correct'] ? 'user-answer' : '' }}">

                    {{-- Keys are 0-based: chr(64+0) was '@' — use loop index: A=0, B=1, … --}}
                    <span class="option-letter">{{ chr(65 + $loop->index) }}</span>
                    <span class="option-text">{{ $option }}</span>

                    @if($isCorrectAnswer)
                        <span class="option-badge correct">Correct Answer</span>
                    @endif
                    @if($isUserAnswer && $result['is_correct'])
                        <span class="option-badge user-correct">Your Answer ✓</span>
                    @endif
                    @if($isUserAnswer && !$result['is_correct'])
                        <span class="option-badge user-incorrect">Your Answer ✗</span>
                    @endif
                </div>
            @endforeach
        </div>

        @if($result['mcq']->explanation)
            <div class="explanation-section">
                <h6><i class="fas fa-info-circle"></i> Explanation:</h6>
                <p>{{ $result['mcq']->explanation }}</p>
            </div>
        @endif
    </div>
@empty
    <div class="text-center py-4 text-muted">No questions to display.</div>
@endforelse
