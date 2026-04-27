<x-app-layout>
    <main class="main-content">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="page-header mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-2">Topic Details</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('topics.index') }}">Topics</a></li>
                                <li class="breadcrumb-item active">{{ $topic->title }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <a href="{{ route('topics.edit', $topic) }}" class="btn btn-primary me-2">
                            <i class="fas fa-edit me-2"></i> Edit
                        </a>
                        <a href="{{ route('topics.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back
                        </a>
                    </div>
                </div>
            </div>

            <!-- Details & Stats -->
            <div class="row">
                <div class="col-lg-8">
                    <!-- Topic Details -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">{{ $topic->title }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Title</label>
                                        <p class="fw-bold">{{ $topic->title }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Slug</label>
                                        <p><code>{{ $topic->slug }}</code></p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Subject</label>
                                        <div class="d-flex align-items-center">
                                            @if($topic->subject)
                                            <div class="color-indicator me-2" 
                                                 style="background-color: {{ $topic->subject->color_code }};"></div>
                                            <div>
                                                <strong>{{ $topic->subject->name }}</strong>
                                                @if($topic->subject->testType)
                                                <div class="small text-muted">{{ $topic->subject->testType->name }}</div>
                                                @endif
                                            </div>
                                            @else
                                            <span class="text-muted">No subject assigned</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Difficulty Level</label>
                                        <div>
                                            <span class="badge bg-{{ $topic->difficulty_badge_variant }} fs-6">
                                                {{ $topic->formatted_difficulty }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Estimated Time</label>
                                        <p>
                                            <i class="fas fa-clock me-1 text-muted"></i>
                                            {{ $topic->estimated_time_minutes }} minutes
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Status</label>
                                        <div>
                                            <span class="badge bg-{{ $topic->status === \App\Enums\ActiveStatus::Active ? 'success' : 'secondary' }}">
                                                {{ ucfirst($topic->status->value) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Sort Order</label>
                                        <p>{{ $topic->sort_order }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Created</label>
                                        <p>{{ $topic->created_at->format('F j, Y g:i A') }}</p>
                                    </div>
                                </div>
                                
                                @if($topic->description)
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Description</label>
                                        <div class="border rounded p-3 bg-light">
                                            {{ $topic->description }}
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                            
                            <!-- Topic Content -->
                            @if($topic->content)
                            <div class="mt-4">
                                <label class="form-label text-muted">Full Content</label>
                                <div class="border rounded p-4 bg-white topic-content">
                                    {!! $topic->content !!}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Recent MCQs -->
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-question-circle me-2"></i>Recent MCQs</h5>
                            <a href="{{ route('mcqs.create', ['topic_id' => $topic->id]) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus me-1"></i> Add MCQ
                            </a>
                        </div>
                        <div class="card-body">
                            @if($topic->mcqs->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Question</th>
                                            <th>Type</th>
                                            <th>Difficulty</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($topic->mcqs as $mcq)
                                        <tr>
                                            <td>
                                                <div class="question-preview">
                                                    {!! Str::limit(strip_tags($mcq->question), 60) !!}
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $mcq->question_type == 'single' ? 'primary' : 'info' }}">
                                                    {{ $mcq->question_type == 'single' ? 'Single' : 'Multiple' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $mcq->difficulty_badge_variant }}">
                                                    {{ $mcq->difficulty_label }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $mcq->status == 'published' ? 'success' : ($mcq->status == 'draft' ? 'secondary' : 'dark') }}">
                                                    {{ $mcq->status_label }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('mcqs.edit', $mcq) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('mcqs.index', ['topic_id' => $topic->id]) }}" class="btn btn-outline-primary">
                                    View All MCQs
                                </a>
                            </div>
                            @else
                            <div class="text-center py-4">
                                <i class="fas fa-question fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No MCQs created yet for this topic</p>
                                <a href="{{ route('mcqs.create', ['topic_id' => $topic->id]) }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i> Create First MCQ
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Stats Card -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Statistics</h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <h1 class="display-4 text-primary">{{ $topic->mcqs_count ?? 0 }}</h1>
                                <p class="text-muted">Total MCQs</p>
                            </div>
                            
                            <div class="progress-stacked mb-3">
                                @php
                                $difficultyCounts = [
                                    'easy' => $topic->mcqs()->where('difficulty_level', 'easy')->count(),
                                    'medium' => $topic->mcqs()->where('difficulty_level', 'medium')->count(),
                                    'hard' => $topic->mcqs()->where('difficulty_level', 'hard')->count(),
                                ];
                                $totalMcqs = array_sum($difficultyCounts);
                                @endphp
                                
                                @if($totalMcqs > 0)
                                <div class="progress" style="width: {{ ($difficultyCounts['easy'] / $totalMcqs) * 100 }}%">
                                    <div class="progress-bar bg-success" role="progressbar"></div>
                                </div>
                                <div class="progress" style="width: {{ ($difficultyCounts['medium'] / $totalMcqs) * 100 }}%">
                                    <div class="progress-bar bg-warning" role="progressbar"></div>
                                </div>
                                <div class="progress" style="width: {{ ($difficultyCounts['hard'] / $totalMcqs) * 100 }}%">
                                    <div class="progress-bar bg-danger" role="progressbar"></div>
                                </div>
                                @endif
                            </div>
                            
                            <div class="row g-2 text-center">
                                <div class="col-4">
                                    <div class="p-2 border rounded">
                                        <div class="text-success fw-bold">{{ $difficultyCounts['easy'] }}</div>
                                        <small class="text-muted">Easy</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-2 border rounded">
                                        <div class="text-warning fw-bold">{{ $difficultyCounts['medium'] }}</div>
                                        <small class="text-muted">Medium</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-2 border rounded">
                                        <div class="text-danger fw-bold">{{ $difficultyCounts['hard'] }}</div>
                                        <small class="text-muted">Hard</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Actions -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('mcqs.create', ['topic_id' => $topic->id]) }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i> Add MCQ
                                </a>
                                <a href="{{ route('topics.index', ['subject_id' => $topic->subject_id]) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-list me-2"></i> View All Topics
                                </a>
                                <a href="{{ route('subjects.show', $topic->subject_id) }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-book me-2"></i> View Subject
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Subject Info -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-book me-2"></i>Subject Information</h5>
                        </div>
                        <div class="card-body">
                            @if($topic->subject)
                            <div class="d-flex align-items-center mb-3">
                                <div class="color-indicator me-3" 
                                     style="background-color: {{ $topic->subject->color_code }}; width: 30px; height: 30px; border-radius: 6px;"></div>
                                <div>
                                    <h6 class="mb-1">{{ $topic->subject->name }}</h6>
                                    @if($topic->subject->testType)
                                    <small class="text-muted">{{ $topic->subject->testType->name }}</small>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <div class="text-center p-2 border rounded">
                                        <div class="text-primary fw-bold">{{ $topic->subject->topics_count ?? 0 }}</div>
                                        <small class="text-muted">Topics</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center p-2 border rounded">
                                        <div class="text-success fw-bold">{{ $topic->subject->mcqs_count ?? 0 }}</div>
                                        <small class="text-muted">MCQs</small>
                                    </div>
                                </div>
                            </div>
                            
                            <a href="{{ route('subjects.show', $topic->subject_id) }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-eye me-2"></i> View Subject Details
                            </a>
                            @else
                            <div class="text-center py-3">
                                <i class="fas fa-exclamation-triangle fa-2x text-warning mb-2"></i>
                                <p class="text-muted">No subject assigned</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @push('js')
    <style>
        .color-indicator {
            width: 16px;
            height: 16px;
            border-radius: 3px;
            display: inline-block;
        }
        
        .topic-content {
            line-height: 1.6;
        }
        
        .topic-content h1, .topic-content h2, .topic-content h3, .topic-content h4 {
            margin-top: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .topic-content p {
            margin-bottom: 1rem;
        }
        
        .topic-content ul, .topic-content ol {
            padding-left: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .topic-content table {
            width: 100%;
            margin-bottom: 1rem;
            border-collapse: collapse;
        }
        
        .topic-content table th,
        .topic-content table td {
            padding: 0.5rem;
            border: 1px solid #dee2e6;
        }
        
        .question-preview {
            max-width: 300px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .progress-stacked {
            height: 6px;
        }
    </style>
    @endpush
</x-app-layout>