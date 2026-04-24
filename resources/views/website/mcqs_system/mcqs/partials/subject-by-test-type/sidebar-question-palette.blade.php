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

        @if(isset($mcqs) && $mcqs->count() > 0)
            @php
                $easyCount = $mcqs->where('difficulty_level', 'easy')->count();
                $mediumCount = $mcqs->where('difficulty_level', 'medium')->count();
                $hardCount = $mcqs->where('difficulty_level', 'hard')->count();
            @endphp

            <div class="difficulty-stats">
                <h6>Difficulty Distribution</h6>
                <div class="difficulty-stat-row">
                    <span class="label easy"><i class="fas fa-circle"></i> Easy</span>
                    <span class="count">{{ $easyCount }}</span>
                </div>
                <div class="difficulty-stat-row">
                    <span class="label medium"><i class="fas fa-circle"></i> Medium</span>
                    <span class="count">{{ $mediumCount }}</span>
                </div>
                <div class="difficulty-stat-row">
                    <span class="label hard"><i class="fas fa-circle"></i> Hard</span>
                    <span class="count">{{ $hardCount }}</span>
                </div>
            </div>
        @endif
    </div>
</div>
