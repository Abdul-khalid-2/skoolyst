<x-app-layout>
    <main class="main-content">
        <div class="container-fluid px-0 px-md-3">
            <!-- Page Header -->
            <div class="page-header mb-4 px-3 px-md-0">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                    <div class="mb-3 mb-md-0">
                        <h1 class="h3 mb-1 mb-md-2">Mock Test Details</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('mock-tests.index') }}">Mock Tests</a></li>
                                <li class="breadcrumb-item active">{{ Str::limit($mockTest->title, 20) }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('mock-tests.edit', $mockTest) }}" class="btn btn-primary d-flex align-items-center">
                            <i class="fas fa-edit me-1 me-md-2"></i> 
                            <span class="d-none d-md-inline">Edit</span>
                        </a>
                        <a href="{{ route('mock-tests.index') }}" class="btn btn-outline-secondary d-flex align-items-center">
                            <i class="fas fa-arrow-left me-1 me-md-2"></i> 
                            <span class="d-none d-md-inline">Back</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Test Stats - Mobile Optimized -->
            <div class="row mb-4 g-3 px-3 px-md-0">
                <div class="col-6 col-md-3">
                    <div class="card card-hover h-100">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1 small">Questions</h6>
                                    <h4 class="mb-0">{{ $mockTest->questions_count }}</h4>
                                    <small class="text-muted d-block">Total</small>
                                </div>
                                <div class="bg-primary bg-opacity-10 p-2 rounded">
                                    <i class="fas fa-question-circle text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-6 col-md-3">
                    <div class="card card-hover h-100">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1 small">Total Marks</h6>
                                    <h4 class="mb-0">{{ $mockTest->questions->sum('marks') }}</h4>
                                    <small class="text-muted d-block">Max score</small>
                                </div>
                                <div class="bg-success bg-opacity-10 p-2 rounded">
                                    <i class="fas fa-star text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-6 col-md-3">
                    <div class="card card-hover h-100">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1 small">Attempts</h6>
                                    <h4 class="mb-0">{{ $mockTest->attempts_count }}</h4>
                                    <small class="text-success d-block">
                                        {{ $mockTest->attempts()->where('result_status', 'passed')->count() }} passed
                                    </small>
                                </div>
                                <div class="bg-warning bg-opacity-10 p-2 rounded">
                                    <i class="fas fa-users text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-6 col-md-3">
                    <div class="card card-hover h-100">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1 small">Avg. Score</h6>
                                    <h4 class="mb-0">
                                        {{ $mockTest->attempts_count > 0 ? round($mockTest->attempts()->avg('percentage'), 1) : 0 }}%
                                    </h4>
                                    <small class="text-muted d-block">Average</small>
                                </div>
                                <div class="bg-info bg-opacity-10 p-2 rounded">
                                    <i class="fas fa-chart-line text-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Test Details -->
            <div class="row">
                <div class="col-lg-8">
                    <!-- Test Information -->
                    <div class="card mb-4 mx-3 mx-md-0">
                        <div class="card-header">
                            <h5 class="mb-0">{{ Str::limit($mockTest->title, 30) }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3 mb-4">
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted small">Title</label>
                                        <p class="fw-bold">{{ $mockTest->title }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted small">Test Type</label>
                                        <p>
                                            @if($mockTest->testType)
                                            <span class="badge bg-light text-dark">
                                                <i class="{{ $mockTest->testType->icon ?? 'fas fa-tag' }} me-1"></i>
                                                {{ $mockTest->testType->name }}
                                            </span>
                                            @else
                                            <span class="text-muted small">No type assigned</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted small">Duration</label>
                                        <p>
                                            <i class="fas fa-clock me-1 text-muted"></i>
                                            {{ $mockTest->total_time_minutes }} minutes
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted small">Passing Marks</label>
                                        <p>{{ $mockTest->passing_marks }} / {{ $mockTest->total_marks }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted small">Test Mode</label>
                                        <p>
                                            <span class="badge bg-{{ $mockTest->test_mode === \App\Enums\MockTestMode::Exam ? 'danger' : ($mockTest->test_mode === \App\Enums\MockTestMode::Timed ? 'warning' : 'primary') }}">
                                                {{ ucfirst($mockTest->test_mode->value) }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted small">Access</label>
                                        <p>
                                            @if($mockTest->is_free)
                                            <span class="badge bg-success">
                                                <i class="fas fa-unlock me-1"></i> Free
                                            </span>
                                            @else
                                            <span class="badge bg-warning">
                                                <i class="fas fa-lock me-1"></i> Premium
                                            </span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted small">Status</label>
                                        <p>
                                            <span class="badge bg-{{ $mockTest->status === \App\Enums\ContentStatus::Published ? 'success' : ($mockTest->status === \App\Enums\ContentStatus::Draft ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($mockTest->status->value) }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted small">Created</label>
                                        <p class="small">{{ $mockTest->created_at->format('M j, Y g:i A') }}</p>
                                    </div>
                                </div>
                                
                                @if($mockTest->description)
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label text-muted small">Description</label>
                                        <div class="border rounded p-3 bg-light small">
                                            {{ $mockTest->description }}
                                        </div>
                                    </div>
                                </div>
                                @endif
                                
                                @if($mockTest->instructions)
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label text-muted small">Instructions</label>
                                        <div class="border rounded p-3 bg-light small">
                                            {{ $mockTest->instructions }}
                                        </div>
                                    </div>
                                </div>
                                @endif
                                
                                <!-- Test Settings -->
                                <div class="col-12">
                                    <h6 class="text-muted mb-3 small">Test Settings:</h6>
                                    <div class="row g-3">
                                        <div class="col-12 col-md-6 col-lg-4">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-random me-2 text-{{ $mockTest->shuffle_questions ? 'success' : 'secondary' }}"></i>
                                                <span class="small">Shuffle: {{ $mockTest->shuffle_questions ? 'Yes' : 'No' }}</span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 col-lg-4">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-eye me-2 text-{{ $mockTest->show_result_immediately ? 'success' : 'secondary' }}"></i>
                                                <span class="small">Show Result: {{ $mockTest->show_result_immediately ? 'Yes' : 'No' }}</span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 col-lg-4">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-redo me-2 text-{{ $mockTest->allow_retake ? 'success' : 'secondary' }}"></i>
                                                <span class="small">Retake: {{ $mockTest->allow_retake ? 'Yes' : 'No' }}</span>
                                            </div>
                                        </div>
                                        @if($mockTest->max_attempts)
                                        <div class="col-12 col-md-6 col-lg-4">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-times-circle me-2 text-info"></i>
                                                <span class="small">Max: {{ $mockTest->max_attempts }}</span>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Questions List -->
                    <div class="card mx-3 mx-md-0">
                        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                            <h5 class="mb-2 mb-md-0">Test Questions ({{ $mockTest->questions->count() }})</h5>
                            <a href="{{ route('mock-tests.add-questions', $mockTest) }}" class="btn btn-sm btn-success d-flex align-items-center">
                                <i class="fas fa-plus me-1"></i> 
                                <span class="d-none d-sm-inline">Add Questions</span>
                                <span class="d-inline d-sm-none">Add</span>
                            </a>
                        </div>
                        <div class="card-body">
                            @if($mockTest->questions->count() > 0)
                            <div class="table-responsive d-none d-md-block">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th width="60">#</th>
                                            <th>Question</th>
                                            <th>Subject/Topic</th>
                                            <th width="100">Difficulty</th>
                                            <th width="80">Marks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($mockTest->questions->sortBy('question_number') as $question)
                                        <tr>
                                            <td>
                                                <span class="badge bg-light text-dark">
                                                    {{ $question->question_number }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="question-preview">
                                                    {!! Str::limit(strip_tags($question->mcq->question), 50) !!}
                                                </div>
                                                <div class="small text-muted mt-1">
                                                    <span class="badge bg-{{ $question->mcq->question_type == 'single' ? 'primary' : 'info' }}">
                                                        {{ $question->mcq->question_type == 'single' ? 'Single' : 'Multiple' }}
                                                    </span>
                                                    @if($question->mcq->is_premium)
                                                    <span class="badge bg-warning ms-1">Premium</span>
                                                    @endif
                                                    @if($question->mcq->is_verified)
                                                    <span class="badge bg-success ms-1">✓</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="small">
                                                    <div class="fw-bold">{{ Str::limit($question->mcq->subject->name ?? 'N/A', 15) }}</div>
                                                    <div class="text-muted">{{ Str::limit($question->mcq->topic->title ?? 'N/A', 15) }}</div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $question->mcq->difficulty_badge_variant }}">
                                                    {{ $question->mcq->difficulty_label }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="fw-bold">{{ $question->marks }}</span>
                                                @if($question->negative_marks > 0)
                                                <div class="small text-danger">-{{ $question->negative_marks }}</div>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Mobile Questions List -->
                            <div class="d-block d-md-none">
                                <div class="list-group">
                                    @foreach($mockTest->questions->sortBy('question_number') as $question)
                                    <div class="list-group-item mb-3 border rounded">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-light text-dark me-2">
                                                    #{{ $question->question_number }}
                                                </span>
                                                <div>
                                                    <span class="badge bg-{{ $question->mcq->question_type == 'single' ? 'primary' : 'info' }} me-1">
                                                        {{ $question->mcq->question_type == 'single' ? 'S' : 'M' }}
                                                    </span>
                                                    @if($question->mcq->is_premium)
                                                    <span class="badge bg-warning me-1">Premium</span>
                                                    @endif
                                                    @if($question->mcq->is_verified)
                                                    <span class="badge bg-success">✓</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-{{ $question->mcq->difficulty_badge_variant }}">
                                                    {{ $question->mcq->difficulty_label }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <div class="small text-muted mb-1">
                                                {!! Str::limit(strip_tags($question->mcq->question), 80) !!}
                                            </div>
                                        </div>
                                        
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <div class="small text-muted">Subject</div>
                                                <div class="small fw-bold">{{ $question->mcq->subject->name ?? 'N/A' }}</div>
                                            </div>
                                            <div class="col-6">
                                                <div class="small text-muted">Topic</div>
                                                <div class="small fw-bold">{{ Str::limit($question->mcq->topic->title ?? 'N/A', 15) }}</div>
                                            </div>
                                            <div class="col-6">
                                                <div class="small text-muted">Marks</div>
                                                <div class="fw-bold">{{ $question->marks }}</div>
                                                @if($question->negative_marks > 0)
                                                <small class="text-danger">-{{ $question->negative_marks }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @else
                            <div class="text-center py-4">
                                <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No questions added yet</p>
                                <a href="{{ route('mock-tests.add-questions', $mockTest) }}" class="btn btn-primary d-flex align-items-center justify-content-center mx-auto" style="max-width: 200px;">
                                    <i class="fas fa-plus me-2"></i> Add Questions
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Sidebar -->
                <div class="col-lg-4 mt-4 mt-lg-0">
                    <!-- Test Actions -->
                    <div class="card mb-4 mx-3 mx-md-0">
                        <div class="card-header">
                            <h5 class="mb-0 d-flex align-items-center">
                                <i class="fas fa-cogs me-2"></i>Test Actions
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('mock-tests.edit', $mockTest) }}" class="btn btn-primary d-flex align-items-center justify-content-center">
                                    <i class="fas fa-edit me-2"></i> 
                                    <span>Edit Test</span>
                                </a>
                                <a href="{{ route('mock-tests.add-questions', $mockTest) }}" class="btn btn-success d-flex align-items-center justify-content-center">
                                    <i class="fas fa-plus me-2"></i> 
                                    <span>Manage Questions</span>
                                </a>
                                @if($mockTest->is_free)
                                <a href="{{ route('mock-test.preview', $mockTest) }}" class="btn btn-info d-flex align-items-center justify-content-center" target="_blank">
                                    <i class="fas fa-eye me-2"></i> 
                                    <span>Preview Test</span>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Subject Distribution -->
                    <div class="card mb-4 mx-3 mx-md-0">
                        <div class="card-header">
                            <h5 class="mb-0 d-flex align-items-center">
                                <i class="fas fa-chart-pie me-2"></i>Subject Distribution
                            </h5>
                        </div>
                        <div class="card-body">
                            @php
                            $subjectDistribution = [];
                            foreach ($mockTest->questions as $question) {
                                $subjectName = $question->mcq->subject->name ?? 'Unknown';
                                if (!isset($subjectDistribution[$subjectName])) {
                                    $subjectDistribution[$subjectName] = 0;
                                }
                                $subjectDistribution[$subjectName]++;
                            }
                            @endphp
                            
                            @if(count($subjectDistribution) > 0)
                            <div class="mb-3">
                                <canvas id="subjectChart" height="150"></canvas>
                            </div>
                            <div class="list-group list-group-flush">
                                @foreach($subjectDistribution as $subject => $count)
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="small">{{ Str::limit($subject, 20) }}</span>
                                    <span class="badge bg-primary small">{{ $count }} Qs</span>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-3">
                                <i class="fas fa-chart-pie fa-2x text-muted mb-2"></i>
                                <p class="text-muted small">No subject data</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Recent Attempts -->
                    <div class="card mx-3 mx-md-0">
                        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                            <h5 class="mb-2 mb-md-0 d-flex align-items-center">
                                <i class="fas fa-history me-2"></i>Recent Attempts
                            </h5>
                            @if($mockTest->attempts_count > 0)
                            <a href="{{ route('user-test-attempts.index', ['mock_test_id' => $mockTest->id]) }}" 
                               class="btn btn-sm btn-outline-primary d-flex align-items-center">
                                <span class="d-none d-md-inline">View All</span>
                                <i class="fas fa-external-link-alt ms-1"></i>
                            </a>
                            @endif
                        </div>
                        <div class="card-body">
                            @php
                            $recentAttempts = $mockTest->attempts()
                                ->with('user')
                                ->latest()
                                ->take(5)
                                ->get();
                            @endphp
                            
                            @if($recentAttempts->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($recentAttempts as $attempt)
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1 small">{{ Str::limit($attempt->user->name ?? 'Anonymous', 20) }}</h6>
                                            <div class="small text-muted">
                                                {{ $attempt->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-bold small">{{ $attempt->percentage }}%</div>
                                            <span class="badge bg-{{ $attempt->result_status === \App\Enums\UserTestResultStatus::Passed ? 'success' : ($attempt->result_status === \App\Enums\UserTestResultStatus::Failed ? 'danger' : 'secondary') }}">
                                                {{ ucfirst($attempt->result_status->value) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-3">
                                <i class="fas fa-history fa-2x text-muted mb-2"></i>
                                <p class="text-muted small">No attempts yet</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @push('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Subject Distribution Chart
            const subjectData = @json(array_values($subjectDistribution));
            const subjectLabels = @json(array_keys($subjectDistribution));
            
            if (subjectLabels.length > 0) {
                const ctx = document.getElementById('subjectChart').getContext('2d');
                new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: subjectLabels,
                        datasets: [{
                            data: subjectData,
                            backgroundColor: [
                                '#0d6efd',
                                '#198754',
                                '#ffc107',
                                '#dc3545',
                                '#6f42c1',
                                '#20c997',
                                '#fd7e14',
                                '#e83e8c'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 12,
                                    padding: 10,
                                    font: {
                                        size: window.innerWidth < 768 ? 10 : 12
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
    @endpush

    <style>
        /* Responsive Container */
        .container-fluid {
            padding-left: 0;
            padding-right: 0;
        }
        
        @media (min-width: 768px) {
            .container-fluid {
                padding-left: var(--bs-gutter-x, 0.75rem);
                padding-right: var(--bs-gutter-x, 0.75rem);
            }
        }
        
        /* Card Hover Effect */
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        /* Stats Cards */
        @media (max-width: 767.98px) {
            .card-hover .card-body {
                padding: 0.75rem !important;
            }
            
            .card-hover h4 {
                font-size: 1.25rem;
            }
            
            .col-6 {
                padding-left: 0.375rem;
                padding-right: 0.375rem;
            }
            
            .card-hover small {
                font-size: 0.75rem;
            }
        }
        
        /* Question Preview */
        .question-preview {
            max-width: 100%;
        }
        
        @media (min-width: 768px) {
            .question-preview {
                max-width: 200px;
            }
        }
        
        /* Subject Chart */
        #subjectChart {
            width: 100% !important;
        }
        
        /* Table Responsiveness */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        @media (max-width: 767.98px) {
            .table-responsive {
                border: 0;
                background: transparent;
            }
            
            .card.mx-3.mx-md-0 {
                margin-left: 0 !important;
                margin-right: 0 !important;
            }
            
            .list-group-item.mb-3 {
                margin-left: 0 !important;
                margin-right: 0 !important;
                padding: 1rem;
            }
        }
        
        /* Badge Adjustments */
        .badge {
            font-size: 0.75em;
        }
        
        @media (max-width: 767.98px) {
            .badge {
                font-size: 0.7em;
                padding: 0.25em 0.5em;
            }
        }
        
        /* Button Adjustments */
        @media (max-width: 767.98px) {
            .btn {
                padding: 0.375rem 0.75rem;
                font-size: 0.875rem;
            }
            
            .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }
            
            .card-header .btn-sm {
                margin-top: 0.5rem;
            }
        }
        
        /* List Group Items */
        .list-group-item h6 {
            font-size: 0.9rem;
        }
        
        @media (max-width: 767.98px) {
            .list-group-item h6 {
                font-size: 0.8rem;
            }
            
            .list-group-item .small {
                font-size: 0.7rem;
            }
        }
        
        /* Form Labels */
        .form-label.text-muted {
            font-size: 0.875rem;
        }
        
        @media (max-width: 767.98px) {
            .form-label.text-muted {
                font-size: 0.75rem;
            }
            
            .card-body p {
                font-size: 0.875rem;
            }
            
            .card-body .small {
                font-size: 0.75rem;
            }
        }
        
        /* Test Settings Grid */
        @media (max-width: 767.98px) {
            .row.g-3 .col-12 {
                margin-bottom: 0.5rem;
            }
            
            .row.g-3 .d-flex {
                font-size: 0.875rem;
            }
            
            .row.g-3 .d-flex i {
                font-size: 0.875rem;
            }
        }
        
        /* Empty State */
        .text-center.py-4 i.fa-3x {
            font-size: 3rem;
        }
        
        @media (max-width: 767.98px) {
            .text-center.py-4 i.fa-3x {
                font-size: 2.5rem;
            }
            
            .text-center.py-4 p {
                font-size: 0.875rem;
            }
        }
        
        /* Sidebar Responsiveness */
        @media (max-width: 991.98px) {
            .col-lg-4 {
                margin-top: 2rem;
            }
            
            .card.mx-3.mx-md-0 {
                margin-left: 0 !important;
                margin-right: 0 !important;
            }
        }
        
        /* Ensure proper spacing on mobile */
        @media (max-width: 767.98px) {
            .main-content {
                padding-top: 0.5rem;
            }
            
            .page-header {
                margin-left: 0.75rem;
                margin-right: 0.75rem;
            }
        }
    </style>
</x-app-layout>