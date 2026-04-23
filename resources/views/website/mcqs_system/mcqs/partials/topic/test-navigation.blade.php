{{-- Fixed nav bar: progress, timer, paginate, clear, submit --}}
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
                data-confirm="You have {{ $mcqs->currentPage() }} of {{ $mcqs->lastPage() }} pages completed. Submit now?"
                onclick="submitTopicTestEarly(this)">
                <i class="fas fa-check-circle"></i> Submit
            </button>
            @endif
        </div>
    </div>
</div>
