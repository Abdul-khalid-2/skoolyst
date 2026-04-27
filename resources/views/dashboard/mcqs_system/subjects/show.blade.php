<x-app-layout>
    <main class="main-content">
        <div class="container-fluid">
            <x-page-header>
                <x-slot name="heading">
                    <h1 class="h3 mb-2">Subject Details</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('subjects.index') }}">Subjects</a></li>
                            <li class="breadcrumb-item active">{{ $subject->name }}</li>
                        </ol>
                    </nav>
                </x-slot>
                <x-slot name="actions">
                    <div class="d-flex flex-wrap gap-2 justify-content-md-end">
                        <x-button href="{{ route('subjects.edit', $subject) }}" variant="primary" class="me-md-2">
                            <i class="fas fa-edit me-2"></i> Edit
                        </x-button>
                        <x-button href="{{ route('subjects.index') }}" variant="outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back
                        </x-button>
                    </div>
                </x-slot>
            </x-page-header>

            <!-- Details & Stats -->
            <div class="row">
                <div class="col-lg-8">
                    <!-- Subject Details -->
                    <x-card class="mb-4">
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
                                            <x-badge variant="light">
                                                <i class="{{ $subject->testType->icon ?? 'fas fa-list' }} me-1"></i>
                                                {{ $subject->testType->name }}
                                            </x-badge>
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
                                            <x-badge :variant="$subject->status === \App\Enums\ActiveStatus::Active ? 'success' : 'secondary'">
                                                {{ ucfirst($subject->status->value) }}
                                            </x-badge>
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
                    </x-card>

                    <!-- Recent Topics -->
                    <x-card>
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-folder me-2"></i>Recent Topics</h5>
                            <x-button href="{{ route('topics.index', ['subject_id' => $subject->id]) }}" variant="outline-primary" class="btn-sm">
                                View All
                            </x-button>
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
                                                <x-badge :variant="$topic->difficulty_badge_variant">
                                                    {{ $topic->formatted_difficulty }}
                                                </x-badge>
                                            </td>
                                            <td>{{ $topic->estimated_time_minutes }} mins</td>
                                            <td>
                                                <x-badge :variant="$topic->status === \App\Enums\ActiveStatus::Active ? 'success' : 'secondary'">
                                                    {{ ucfirst($topic->status->value) }}
                                                </x-badge>
                                            </td>
                                            <td>
                                                <x-button href="{{ route('topics.edit', $topic) }}" variant="outline-primary" class="btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </x-button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <x-empty-state title="No topics created yet" icon="fa-folder-open">
                                <x-slot name="actions">
                                    <x-button href="{{ route('topics.create', ['subject_id' => $subject->id]) }}" variant="primary">
                                        <i class="fas fa-plus me-2"></i> Create First Topic
                                    </x-button>
                                </x-slot>
                            </x-empty-state>
                            @endif
                        </div>
                    </x-card>
                </div>
                
                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Stats Card -->
                    <x-card class="mb-4">
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
                    </x-card>
                    
                    <!-- Quick Actions -->
                    <x-card class="mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <x-button href="{{ route('topics.create', ['subject_id' => $subject->id]) }}" variant="outline-primary">
                                    <i class="fas fa-plus me-2"></i> Add Topic
                                </x-button>
                                <x-button href="{{ route('mcqs.create', ['subject_id' => $subject->id]) }}" variant="outline-success">
                                    <i class="fas fa-question me-2"></i> Add MCQ
                                </x-button>
                                <x-button href="{{ route('subjects.index') }}" variant="outline-secondary">
                                    <i class="fas fa-list me-2"></i> View All Subjects
                                </x-button>
                            </div>
                        </div>
                    </x-card>
                    
                    <!-- Recent MCQs -->
                    <x-card>
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-question-circle me-2"></i>Recent MCQs</h5>
                            <x-button href="{{ route('mcqs.index', ['subject_id' => $subject->id]) }}" variant="outline-primary" class="btn-sm">
                                View All
                            </x-button>
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
                                        <x-badge :variant="$mcq->difficulty_badge_variant">
                                            {{ $mcq->difficulty_label }}
                                        </x-badge>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <x-empty-state title="No MCQs created yet" icon="fa-question" class="py-3">
                            </x-empty-state>
                            @endif
                        </div>
                    </x-card>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>