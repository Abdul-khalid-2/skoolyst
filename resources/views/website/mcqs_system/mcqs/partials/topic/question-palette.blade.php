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

        @php
            $mcqList = $mcqs instanceof \Illuminate\Pagination\LengthAwarePaginator
                ? $mcqs->getCollection()
                : $mcqs;
            $easyCount = $mcqList->filter(fn ($m) => $m->difficulty_value === 'easy')->count();
            $mediumCount = $mcqList->filter(fn ($m) => $m->difficulty_value === 'medium')->count();
            $hardCount = $mcqList->filter(fn ($m) => $m->difficulty_value === 'hard')->count();
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
