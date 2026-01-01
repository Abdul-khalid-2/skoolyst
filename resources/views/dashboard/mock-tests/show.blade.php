<x-app-layout>
    <main class="main-content">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="page-header mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-2">Mock Test Details</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('mock-tests.index') }}">Mock Tests</a></li>
                                <li class="breadcrumb-item active">{{ $mockTest->title }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <a href="{{ route('mock-tests.edit', $mockTest) }}" class="btn btn-primary me-2">
                            <i class="fas fa-edit me-2"></i> Edit
                        </a>
                        <a href="{{ route('mock-tests.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back
                        </a>
                    </div>
                </div>
            </div>

            <!-- Test Stats -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card card-hover">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Questions</h6>
                                    <h3 class="mb-0">{{ $mockTest->questions_count }}</h3>
                                    <small class="text-muted">Total questions</small>
                                </div>
                                <div class="bg-primary bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-question-circle fa-2x text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card card-hover">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Total Marks</h6>
                                    <h3 class="mb-0">{{ $mockTest->questions->sum('marks') }}</h3>
                                    <small class="text-muted">Maximum score</small>
                                </div>
                                <div class="bg-success bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-star fa-2x text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card card-hover">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Attempts</h6>
                                    <h3 class="mb-0">{{ $mockTest->attempts_count }}</h3>
                                    <small class="text-success">
                                        {{ $mockTest->attempts()->where('result_status', 'passed')->count() }} passed
                                    </small>
                                </div>
                                <div class="bg-warning bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-users fa-2x text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card card-hover">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Avg. Score</h6>
                                    <h3 class="mb-0">
                                        {{ $mockTest->attempts_count > 0 ? round($mockTest->attempts()->avg('percentage'), 1) : 0 }}%
                                    </h3>
                                    <small class="text-muted">Average percentage</small>
                                </div>
                                <div class="bg-info bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-chart-line fa-2x text-info"></i>
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
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">{{ $mockTest->title }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Title</label>
                                        <p class="fw-bold">{{ $mockTest->title }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Test Type</label>
                                        <p>
                                            @if($mockTest->testType)
                                            <span class="badge bg-light text-dark">
                                                <i class="{{ $mockTest->testType->icon ?? 'fas fa-tag' }} me-1"></i>
                                                {{ $mockTest->testType->name }}
                                            </span>
                                            @else
                                            <span class="text-muted">No type assigned</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Duration</label>
                                        <p>
                                            <i class="fas fa-clock me-1 text-muted"></i>
                                            {{ $mockTest->total_time_minutes }} minutes
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Passing Marks</label>
                                        <p>{{ $mockTest->passing_marks }} out of {{ $mockTest->total_marks }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Test Mode</label>
                                        <p>
                                            <span class="badge bg-{{ $mockTest->test_mode == 'exam' ? 'danger' : ($mockTest->test_mode == 'timed' ? 'warning' : 'primary') }}">
                                                {{ ucfirst($mockTest->test_mode) }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Access</label>
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
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Status</label>
                                        <p>
                                            <span class="badge bg-{{ $mockTest->status == 'published' ? 'success' : ($mockTest->status == 'draft' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($mockTest->status) }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Created</label>
                                        <p>{{ $mockTest->created_at->format('F j, Y g:i A') }}</p>
                                    </div>
                                </div>
                                
                                @if($mockTest->description)
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Description</label>
                                        <div class="border rounded p-3 bg-light">
                                            {{ $mockTest->description }}
                                        </div>
                                    </div>
                                </div>
                                @endif
                                
                                @if($mockTest->instructions)
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Instructions</label>
                                        <div class="border rounded p-3 bg-light">
                                            {{ $mockTest->instructions }}
                                        </div>
                                    </div>
                                </div>
                                @endif
                                
                                <!-- Test Settings -->
                                <div class="col-12">
                                    <h6 class="text-muted mb-3">Test Settings:</h6>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-random me-2 text-{{ $mockTest->shuffle_questions ? 'success' : 'secondary' }}"></i>
                                                <span>Shuffle Questions: {{ $mockTest->shuffle_questions ? 'Yes' : 'No' }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-eye me-2 text-{{ $mockTest->show_result_immediately ? 'success' : 'secondary' }}"></i>
                                                <span>Show Result: {{ $mockTest->show_result_immediately ? 'Immediately' : 'Later' }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-redo me-2 text-{{ $mockTest->allow_retake ? 'success' : 'secondary' }}"></i>
                                                <span>Allow Retake: {{ $mockTest->allow_retake ? 'Yes' : 'No' }}</span>
                                            </div>
                                        </div>
                                        @if($mockTest->max_attempts)
                                        <div class="col-md-4">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-times-circle me-2 text-info"></i>
                                                <span>Max Attempts: {{ $mockTest->max_attempts }}</span>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Questions List -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Test Questions ({{ $mockTest->questions->count() }})</h5>
                        </div>
                        <div class="card-body">
                            @if($mockTest->questions->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th width="60">#</th>
                                            <th>Question</th>
                                            <th>Subject/Topic</th>
                                            <th>Difficulty</th>
                                            <th>Marks</th>
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
                                                    {!! Str::limit(strip_tags($question->mcq->question), 60) !!}
                                                </div>
                                                <div class="small text-muted">
                                                    <span class="badge bg-{{ $question->mcq->question_type == 'single' ? 'primary' : 'info' }}">
                                                        {{ $question->mcq->question_type == 'single' ? 'Single' : 'Multiple' }}
                                                    </span>
                                                    @if($question->mcq->is_premium)
                                                    <span class="badge bg-warning ms-1">Premium</span>
                                                    @endif
                                                    @if($question->mcq->is_verified)
                                                    <span class="badge bg-success ms-1">Verified</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="small">
                                                    <div>{{ $question->mcq->subject->name ?? 'N/A' }}</div>
                                                    <div class="text-muted">{{ $question->mcq->topic->title ?? 'N/A' }}</div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $question->mcq->difficulty_level == 'easy' ? 'success' : ($question->mcq->difficulty_level == 'medium' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($question->mcq->difficulty_level) }}
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
                            @else
                            <div class="text-center py-4">
                                <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No questions added yet</p>
                                <a href="{{ route('mock-tests.add-questions', $mockTest) }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i> Add Questions
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Test Actions -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Test Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('mock-tests.edit', $mockTest) }}" class="btn btn-primary">
                                    <i class="fas fa-edit me-2"></i> Edit Test
                                </a>
                                <a href="{{ route('mock-tests.add-questions', $mockTest) }}" class="btn btn-success">
                                    <i class="fas fa-plus me-2"></i> Manage Questions
                                </a>
                                @if($mockTest->is_free)
                                <a href="{{ route('mock-test.preview', $mockTest) }}" class="btn btn-info" target="_blank">
                                    <i class="fas fa-eye me-2"></i> Preview Test
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Subject Distribution -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Subject Distribution</h5>
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
                                <canvas id="subjectChart" height="200"></canvas>
                            </div>
                            <div class="list-group list-group-flush">
                                @foreach($subjectDistribution as $subject => $count)
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span>{{ $subject }}</span>
                                    <span class="badge bg-primary">{{ $count }} questions</span>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-3">
                                <i class="fas fa-chart-pie fa-2x text-muted mb-2"></i>
                                <p class="text-muted">No subject data available</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Recent Attempts -->
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-history me-2"></i>Recent Attempts</h5>
                            <a href="{{ route('user-test-attempts.index', ['mock_test_id' => $mockTest->id]) }}" 
                               class="btn btn-sm btn-outline-primary">
                                View All
                            </a>
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
                                            <h6 class="mb-1">{{ $attempt->user->name ?? 'Anonymous' }}</h6>
                                            <div class="small text-muted">
                                                {{ $attempt->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-bold">{{ $attempt->percentage }}%</div>
                                            <span class="badge bg-{{ $attempt->result_status == 'passed' ? 'success' : ($attempt->result_status == 'failed' ? 'danger' : 'secondary') }}">
                                                {{ ucfirst($attempt->result_status) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-3">
                                <i class="fas fa-history fa-2x text-muted mb-2"></i>
                                <p class="text-muted">No attempts yet</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @push('scripts')
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
                                    padding: 20
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
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .question-preview {
            max-width: 300px;
        }
        
        #subjectChart {
            width: 100% !important;
        }
        
        .list-group-item h6 {
            font-size: 0.9rem;
        }
    </style>
</x-app-layout>