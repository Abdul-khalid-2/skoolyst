@php
    $testResults = $testResults ?? [];
    $tt = (int) ($testResults['time_taken'] ?? 0);
    $m = intdiv($tt, 60);
    $s = $tt % 60;
    if ($m > 0) {
        $durationLabel = $m.' min'.($s > 0 ? ' '.$s.' sec' : '');
    } else {
        $durationLabel = '0 min '.$s.' sec';
    }
    $completedLabel = $testResults['completed_at'] ?? null
        ? \Illuminate\Support\Carbon::parse($testResults['completed_at'])->format('F j, Y, g:i a')
        : now()->format('F j, Y, g:i a');
    $pct = number_format((float) ($testResults['percentage'] ?? 0), 2);
@endphp

<aside class="subject-results-analytics">
    <div class="card border-0 shadow-sm subject-results-analytics__card">
        <div class="card-body p-3 p-md-4">
            <div class="text-center border-bottom pb-3 mb-3">
                <div class="fs-1 fw-bold text-body lh-sm">{{ (int) ($testResults['obtained_marks'] ?? 0) }}</div>
                <div class="fw-medium text-body">Marks obtained</div>
                <div class="text-muted small">Out of {{ (int) ($testResults['total_marks'] ?? 0) }}</div>
            </div>

            <div class="row g-2 text-center mb-3">
                <div class="col-6">
                    <div class="rounded bg-success bg-opacity-10 p-2">
                        <div class="fs-4 fw-bold text-success">{{ (int) ($testResults['correct'] ?? 0) }}</div>
                        <div class="text-muted small">Correct</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="rounded bg-danger bg-opacity-10 p-2">
                        <div class="fs-4 fw-bold text-danger">{{ (int) ($testResults['wrong'] ?? 0) }}</div>
                        <div class="text-muted small">Incorrect</div>
                    </div>
                </div>
            </div>

            <ul class="list-unstyled small subject-results-analytics__meta mb-0">
                <li class="d-flex justify-content-between py-1 border-bottom">
                    <span class="text-muted">Time taken</span>
                    <span class="text-end fw-medium">{{ $durationLabel }}</span>
                </li>
                <li class="d-flex justify-content-between py-1 border-bottom">
                    <span class="text-muted">Test type</span>
                    <span class="text-end fw-medium text-break ps-1">{{ $testType->name ?? '—' }}</span>
                </li>
                <li class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-start py-1 gap-1">
                    <span class="text-muted flex-shrink-0">Completed</span>
                    <span class="text-end fw-medium ps-sm-1">{{ $completedLabel }}</span>
                </li>
            </ul>
        </div>
    </div>

    <div class="card border-0 shadow-sm subject-results-analytics__card mt-3">
        <div class="card-body p-3 p-md-4">
            <h3 class="h6 text-muted text-uppercase small mb-3">Summary</h3>
            <div class="row g-2 g-md-3 text-center row-cols-2 row-cols-sm-4">
                <div class="col">
                    <div class="fw-bold fs-5 text-body">{{ (int) ($testResults['total_questions'] ?? 0) }}</div>
                    <div class="text-muted" style="font-size: 0.7rem;">Total</div>
                </div>
                <div class="col">
                    <div class="fw-bold fs-5 text-body">{{ (int) ($testResults['attempted'] ?? 0) }}</div>
                    <div class="text-muted" style="font-size: 0.7rem;">Attempted</div>
                </div>
                <div class="col">
                    <div class="fw-bold fs-5 text-body">{{ (int) ($testResults['skipped'] ?? 0) }}</div>
                    <div class="text-muted" style="font-size: 0.7rem;">Skipped</div>
                </div>
                <div class="col">
                    <div class="fw-bold fs-5 text-primary">{{ $pct }}%</div>
                    <div class="text-muted" style="font-size: 0.65rem; line-height: 1.15;">Percentage</div>
                </div>
            </div>
            @if (isset($testResults['accuracy']))
                <p class="small text-muted mb-0 mt-3 text-center">Accuracy: <strong class="text-body">{{ $testResults['accuracy'] }}%</strong></p>
            @endif
        </div>
    </div>
</aside>
