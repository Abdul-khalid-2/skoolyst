<x-app-layout>
    <main class="main-content">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="page-header mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-2">Subject Details</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('subjects.index') }}">Subjects</a></li>
                                <li class="breadcrumb-item active">{{ $subject->name }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <a href="{{ route('subjects.edit', $subject) }}" class="btn btn-primary me-2">
                            <i class="fas fa-edit me-2"></i> Edit
                        </a>
                        <a href="{{ route('subjects.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back
                        </a>
                    </div>
                </div>
            </div>

            <!-- Details & Stats -->
            <div class="row">
                <div class="col-lg-8">
                    <!-- Subject Details -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">{{ $subject->name }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Name</label>
                                        <p class="fw-bold">{{ $subject->name }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Slug</label>
                                        <p><code>{{ $subject->slug }}</code></p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Test Type</label>
                                        <div>
                                            @if($subject->testType)
                                            <span class="badge bg-light text-dark">
                                                <i class="{{ $subject->testType->icon ?? 'fas fa-list' }} me-1"></i>
                                                {{ $subject->testType->name }}
                                            </span>
                                            @else
                                            <span class="text-muted">No test type assigned</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Icon</label>
                                        <div>
                                            @if($subject->icon)
                                            <i class="{{ $subject->icon }} fa-2x text-primary me-2"></i>
                                            <span>{{ $subject->icon }}</span>
                                            @else
                                            <span class="text-muted">No icon</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Color</label>
                                        <div class="d-flex align-items-center">
                                            <div class="color-box me-2" 
                                                 style="background-color: {{ $subject->color_code }}; width: 30px; height: 30px; border-radius: 4px;"></div>
                                            <span>{{ $subject->color_code }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Status</label>
                                        <div>
                                            <span class="badge bg-{{ $subject->status === \App\Enums\ActiveStatus::Active ? 'success' : 'secondary' }}">
                                                {{ ucfirst($subject->status->value) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Sort Order</label>
                                        <p>{{ $subject->sort_order }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Created</label>
                                        <p>{{ $subject->created_at->format('F j, Y g:i A') }}</p>
                                    </div>
                                </div>
                                
                                @if($subject->description)
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Description</label>
                                        <div class="border rounded p-3 bg-light">
                                            {{ $subject->description }}
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Recent Topics -->
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-folder me-2"></i>Recent Topics</h5>
                            <a href="{{ route('topics.index', ['subject_id' => $subject->id]) }}" class="btn btn-sm btn-outline-primary">
                                View All
                            </a>
                        </div>
                        <div class="card-body">
                            @if($subject->topics->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Topic</th>
                                            <th>Difficulty</th>
                                            <th>Time</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($subject->topics as $topic)
                                        <tr>
                                            <td>
                                                <strong>{{ $topic->title }}</strong>
                                                @if($topic->description)
                                                <div class="small text-muted">{{ Str::limit($topic->description, 30) }}</div>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $topic->difficulty_badge_variant }}">
                                                    {{ $topic->formatted_difficulty }}
                                                </span>
                                            </td>
                                            <td>{{ $topic->estimated_time_minutes }} mins</td>
                                            <td>
                                                <span class="badge bg-{{ $topic->status === \App\Enums\ActiveStatus::Active ? 'success' : 'secondary' }}">
                                                    {{ ucfirst($topic->status->value) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('topics.edit', $topic) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center py-4">
                                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No topics created yet</p>
                                <a href="{{ route('topics.create', ['subject_id' => $subject->id]) }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i> Create First Topic
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
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="text-center p-3 border rounded">
                                        <h2 class="text-primary mb-1">{{ $subject->topics_count ?? 0 }}</h2>
                                        <small class="text-muted">Topics</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center p-3 border rounded">
                                        <h2 class="text-success mb-1">{{ $subject->mcqs_count ?? 0 }}</h2>
                                        <small class="text-muted">MCQs</small>
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
                                <a href="{{ route('topics.create', ['subject_id' => $subject->id]) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-plus me-2"></i> Add Topic
                                </a>
                                <a href="{{ route('mcqs.create', ['subject_id' => $subject->id]) }}" class="btn btn-outline-success">
                                    <i class="fas fa-question me-2"></i> Add MCQ
                                </a>
                                <a href="{{ route('subjects.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-list me-2"></i> View All Subjects
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recent MCQs -->
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-question-circle me-2"></i>Recent MCQs</h5>
                            <a href="{{ route('mcqs.index', ['subject_id' => $subject->id]) }}" class="btn btn-sm btn-outline-warning">
                                View All
                            </a>
                        </div>
                        <div class="card-body">
                            @if($subject->mcqs->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($subject->mcqs as $mcq)
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <strong>{{ Str::limit(strip_tags($mcq->question), 40) }}</strong>
                                            <div class="small text-muted">
                                                {{ $mcq->question_type == 'single' ? 'Single' : 'Multiple' }} Choice
                                            </div>
                                        </div>
                                        <span class="badge bg-{{ $mcq->difficulty_badge_variant }}">
                                            {{ $mcq->difficulty_label }}
                                        </span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-3">
                                <i class="fas fa-question fa-2x text-muted mb-2"></i>
                                <p class="text-muted">No MCQs created yet</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>