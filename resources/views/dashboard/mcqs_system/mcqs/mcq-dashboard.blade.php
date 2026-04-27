<x-app-layout>
    <main class="main-content">
        <x-page-header class="mb-4">
            <x-slot name="heading">
                <h1 class="h3 mb-2">MCQ & Educational Content Management</h1>
                <p class="text-muted mb-0">Manage test types, subjects, MCQs, mock tests, and study materials</p>
            </x-slot>
            <x-slot name="actions">
                <div class="dropdown">
                    <x-button variant="primary" class="dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-plus me-2"></i> Add New
                    </x-button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('test-types.create') }}">Test Type</a></li>
                        <li><a class="dropdown-item" href="{{ route('subjects.create') }}">Subject</a></li>
                        <li><a class="dropdown-item" href="{{ route('topics.create') }}">Topic</a></li>
                        <li><a class="dropdown-item" href="{{ route('mcqs.create') }}">MCQ</a></li>
                        <li><a class="dropdown-item" href="{{ route('mock-tests.create') }}">Mock Test</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('book-categories.create') }}">Book Category</a></li>
                        <li><a class="dropdown-item" href="{{ route('study-materials.create') }}">Study Material</a></li>
                    </ul>
                </div>
            </x-slot>
        </x-page-header>

        @if(session('success'))
            <x-alert variant="success">{{ session('success') }}</x-alert>
        @endif

        <!-- Stats Overview -->
        <div class="row mb-4">
            <div class="col-md-3">
                <x-card class="card-hover">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-2">Total MCQs</h6>
                                <h3 class="mb-0">{{ \App\Models\Mcq::count() }}</h3>
                                <small class="text-success">
                                    <i class="fas fa-arrow-up me-1"></i>
                                    {{ \App\Models\Mcq::whereDate('created_at', today())->count() }} new today
                                </small>
                            </div>
                            <div class="bg-primary bg-opacity-10 p-3 rounded">
                                <i class="fas fa-question-circle fa-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </x-card>
            </div>
            
            <div class="col-md-3">
                <x-card class="card-hover">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-2">Mock Tests</h6>
                                <h3 class="mb-0">{{ \App\Models\MockTest::count() }}</h3>
                                <small class="text-success">
                                    {{ \App\Models\MockTest::where('status', 'published')->count() }} published
                                </small>
                            </div>
                            <div class="bg-success bg-opacity-10 p-3 rounded">
                                <i class="fas fa-file-alt fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </x-card>
            </div>
            
            <div class="col-md-3">
                <x-card class="card-hover">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-2">Subjects</h6>
                                <h3 class="mb-0">{{ \App\Models\Subject::count() }}</h3>
                                <small class="text-success">
                                    {{ \App\Models\Subject::where('status', 'active')->count() }} active
                                </small>
                            </div>
                            <div class="bg-info bg-opacity-10 p-3 rounded">
                                <i class="fas fa-book fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </x-card>
            </div>
            
            <div class="col-md-3">
                <x-card class="card-hover">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-2">Study Materials</h6>
                                <h3 class="mb-0">{{ \App\Models\StudyMaterial::count() }}</h3>
                                <small class="text-success">
                                    {{ \App\Models\StudyMaterial::where('is_free', true)->count() }} free
                                </small>
                            </div>
                            <div class="bg-warning bg-opacity-10 p-3 rounded">
                                <i class="fas fa-graduation-cap fa-2x text-warning"></i>
                            </div>
                        </div>
                    </div>
                </x-card>
            </div>
        </div>

        <!-- Quick Access Tabs -->
        <div class="row">
            <div class="col-lg-8">
                <x-card>
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs" id="mcqTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="recent-tab" data-bs-toggle="tab" data-bs-target="#recent">
                                    Recent MCQs
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tests-tab" data-bs-toggle="tab" data-bs-target="#tests">
                                    Mock Tests
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="materials-tab" data-bs-toggle="tab" data-bs-target="#materials">
                                    Study Materials
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="mcqTabsContent">
                            <!-- Recent MCQs Tab -->
                            <div class="tab-pane fade show active" id="recent">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Question</th>
                                                <th>Subject</th>
                                                <th>Topic</th>
                                                <th>Difficulty</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach(\App\Models\Mcq::with(['subject', 'topic'])->latest()->take(10)->get() as $mcq)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div>
                                                            <strong>{{ Str::limit(strip_tags($mcq->question), 50) }}</strong>
                                                            <div class="small text-muted">
                                                                {{ $mcq->question_type == 'single' ? 'Single Choice' : 'Multiple Choice' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $mcq->subject->name ?? 'N/A' }}</td>
                                                <td>{{ $mcq->topic->title ?? 'N/A' }}</td>
                                                <td>
                                                    <x-badge :variant="$mcq->difficulty_badge_variant">
                                                        {{ $mcq->difficulty_label }}
                                                    </x-badge>
                                                </td>
                                                <td>
                                                    <x-badge :variant="$mcq->status == 'published' ? 'success' : ($mcq->status == 'draft' ? 'secondary' : 'dark')">
                                                        {{ $mcq->status_label }}
                                                    </x-badge>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <x-button href="{{ route('mcqs.edit', $mcq) }}" variant="outline-primary" class="btn-sm">
                                                            <i class="fas fa-edit"></i>
                                                        </x-button>
                                                        <x-button
                                                            type="button"
                                                            variant="outline-info"
                                                            class="btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#previewMcqModal{{ $mcq->id }}"
                                                        >
                                                            <i class="fas fa-eye"></i>
                                                        </x-button>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-center mt-3">
                                    <x-button href="{{ route('mcqs.index') }}" variant="outline-primary">View All MCQs</x-button>
                                </div>
                            </div>

                            <!-- Mock Tests Tab -->
                            <div class="tab-pane fade" id="tests">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Test Title</th>
                                                <th>Test Type</th>
                                                <th>Questions</th>
                                                <th>Duration</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach(\App\Models\MockTest::with('testType')->latest()->take(10)->get() as $test)
                                            <tr>
                                                <td>
                                                    <strong>{{ $test->title }}</strong>
                                                    <div class="small text-muted">
                                                        {{ $test->is_free ? 'Free' : 'Premium' }}
                                                    </div>
                                                </td>
                                                <td>{{ $test->testType->name ?? 'N/A' }}</td>
                                                <td>{{ $test->total_questions }}</td>
                                                <td>{{ $test->total_time_minutes }} mins</td>
                                                <td>
                                                    <x-badge :variant="$test->status === \App\Enums\ContentStatus::Published ? 'success' : ($test->status === \App\Enums\ContentStatus::Draft ? 'secondary' : 'dark')">
                                                        {{ ucfirst($test->status->value) }}
                                                    </x-badge>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <x-button href="{{ route('mock-tests.edit', $test) }}" variant="outline-primary" class="btn-sm">
                                                            <i class="fas fa-edit"></i>
                                                        </x-button>
                                                        <x-button href="{{ route('mock-tests.show', $test) }}" variant="outline-info" class="btn-sm">
                                                            <i class="fas fa-eye"></i>
                                                        </x-button>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-center mt-3">
                                    <x-button href="{{ route('mock-tests.index') }}" variant="outline-primary">View All Tests</x-button>
                                </div>
                            </div>

                            <!-- Study Materials Tab -->
                            <div class="tab-pane fade" id="materials">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Type</th>
                                                <th>Subject</th>
                                                <th>File Type</th>
                                                <th>Downloads</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach(\App\Models\StudyMaterial::with('subject')->latest()->take(10)->get() as $material)
                                            <tr>
                                                <td>
                                                    <strong>{{ $material->title }}</strong>
                                                    <div class="small text-muted">
                                                        {{ $material->is_free ? 'Free' : 'Premium' }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <x-badge variant="info">{{ ucfirst($material->material_type) }}</x-badge>
                                                </td>
                                                <td>{{ $material->subject->name ?? 'N/A' }}</td>
                                                <td>
                                                    <x-badge variant="secondary">{{ strtoupper($material->file_type) }}</x-badge>
                                                </td>
                                                <td>{{ $material->download_count }}</td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <x-button href="{{ route('study-materials.edit', $material) }}" variant="outline-primary" class="btn-sm">
                                                            <i class="fas fa-edit"></i>
                                                        </x-button>
                                                        @if($material->file_path)
                                                        <x-button href="{{ asset('storage/' . $material->file_path) }}" variant="outline-success" class="btn-sm" target="_blank">
                                                            <i class="fas fa-download"></i>
                                                        </x-button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-center mt-3">
                                    <x-button href="{{ route('study-materials.index') }}" variant="outline-primary">View All Materials</x-button>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-card>
            </div>

            <!-- Right Sidebar -->
            <div class="col-lg-4">
                <!-- Quick Stats -->
                <x-card class="mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Quick Stats</h6>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span>Published MCQs</span>
                                <x-badge variant="primary">{{ \App\Models\Mcq::where('status', 'published')->count() }}</x-badge>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span>Active Topics</span>
                                <x-badge variant="success">{{ \App\Models\Topic::where('status', 'active')->count() }}</x-badge>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span>Premium MCQs</span>
                                <x-badge variant="warning">{{ \App\Models\Mcq::where('is_premium', true)->count() }}</x-badge>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span>Free Study Materials</span>
                                <x-badge variant="info">{{ \App\Models\StudyMaterial::where('is_free', true)->count() }}</x-badge>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span>Pending Reviews</span>
                                <x-badge variant="danger">{{ \App\Models\Mcq::where('is_verified', false)->count() }}</x-badge>
                            </div>
                        </div>
                    </div>
                </x-card>

                <!-- Quick Actions -->
                <x-card>
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            <div class="col-6">
                                <x-button href="{{ route('mcqs.create') }}" variant="outline-primary" class="w-100 mb-2">
                                    <i class="fas fa-plus me-1"></i> Add MCQ
                                </x-button>
                            </div>
                            <div class="col-6">
                                <x-button href="{{ route('mock-tests.create') }}" variant="outline-success" class="w-100 mb-2">
                                    <i class="fas fa-plus me-1"></i> Create Test
                                </x-button>
                            </div>
                            <div class="col-6">
                                <x-button href="{{ route('mcqs.index', ['status' => 'draft']) }}" variant="outline-warning" class="w-100 mb-2">
                                    <i class="fas fa-pen me-1"></i> Review Drafts
                                </x-button>
                            </div>
                            <div class="col-6">
                                <x-button href="{{ route('study-materials.create') }}" variant="outline-info" class="w-100 mb-2">
                                    <i class="fas fa-upload me-1"></i> Upload Material
                                </x-button>
                            </div>
                        </div>
                    </div>
                </x-card>

                <!-- Recent Activity -->
                <x-card class="mt-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-history me-2"></i>Recent Activity</h6>
                    </div>
                    <div class="card-body">
                        <div class="activity-feed">
                            @php
                            // Get recent activity from multiple models
                            $activities = collect();
                            
                            // Get recent MCQs
                            $mcqActivities = \App\Models\Mcq::with(['subject', 'createdBy'])
                                ->latest()
                                ->take(5)
                                ->get()
                                ->map(function($mcq) {
                                    return [
                                        'type' => 'mcq',
                                        'title' => 'MCQ Added',
                                        'description' => Str::limit(strip_tags($mcq->question), 30),
                                        'user' => $mcq->createdBy->name ?? 'System',
                                        'time' => $mcq->created_at,
                                        'icon' => 'fas fa-question',
                                        'color' => 'primary'
                                    ];
                                });
                            
                            // Get recent mock tests
                            $testActivities = \App\Models\MockTest::with(['createdBy'])
                                ->latest()
                                ->take(5)
                                ->get()
                                ->map(function($test) {
                                    return [
                                        'type' => 'test',
                                        'title' => 'Mock Test Created',
                                        'description' => $test->title,
                                        'user' => $test->createdBy->name ?? 'System',
                                        'time' => $test->created_at,
                                        'icon' => 'fas fa-file-alt',
                                        'color' => 'success'
                                    ];
                                });
                            
                            $activities = $mcqActivities->merge($testActivities)->sortByDesc('time')->take(5);
                            @endphp
                            
                            @foreach($activities as $activity)
                            <div class="activity-item d-flex mb-3">
                                <div class="activity-icon me-3">
                                    <span class="badge bg-{{ $activity['color'] }} rounded-circle p-2">
                                        <i class="{{ $activity['icon'] }}"></i>
                                    </span>
                                </div>
                                <div class="activity-content">
                                    <h6 class="mb-1">{{ $activity['title'] }}</h6>
                                    <p class="mb-1 small">{{ $activity['description'] }}</p>
                                    <div class="text-muted small">
                                        <i class="fas fa-user me-1"></i>{{ $activity['user'] }}
                                        <i class="fas fa-clock ms-3 me-1"></i>{{ $activity['time']->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </x-card>
            </div>
        </div>

        <!-- Category Overview -->
        <div class="row mt-4">
            <div class="col-md-6">
                <x-card>
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="fas fa-list me-2"></i>Test Types</h6>
                        <x-button href="{{ route('test-types.index') }}" variant="outline-primary" class="btn-sm">View All</x-button>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach(\App\Models\TestType::where('status', 'active')->take(6)->get() as $type)
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 border rounded">
                                    @if($type->icon)
                                    <div class="flex-shrink-0 me-3">
                                        <i class="{{ $type->icon }} fa-2x text-primary"></i>
                                    </div>
                                    @endif
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $type->name }}</h6>
                                        <small class="text-muted">
                                            {{ \App\Models\Subject::where('test_type_id', $type->id)->count() }} subjects
                                        </small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </x-card>
            </div>
            
            <div class="col-md-6">
                <x-card>
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="fas fa-book me-2"></i>Popular Subjects</h6>
                        <x-button href="{{ route('subjects.index') }}" variant="outline-primary" class="btn-sm">View All</x-button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Subject</th>
                                        <th>MCQs</th>
                                        <th>Topics</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(\App\Models\Subject::withCount(['mcqs', 'topics'])->orderBy('mcqs_count', 'desc')->take(5)->get() as $subject)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($subject->icon)
                                                <i class="{{ $subject->icon }} me-2 text-primary"></i>
                                                @endif
                                                <strong>{{ $subject->name }}</strong>
                                            </div>
                                        </td>
                                        <td>{{ $subject->mcqs_count }}</td>
                                        <td>{{ $subject->topics_count }}</td>
                                        <td>
                                            <x-badge :variant="$subject->status === \App\Enums\ActiveStatus::Active ? 'success' : 'secondary'">
                                                {{ ucfirst($subject->status->value) }}
                                            </x-badge>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </x-card>
            </div>
        </div>
    </main>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Bootstrap tooltips
            const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            tooltips.forEach(tooltip => new bootstrap.Tooltip(tooltip));
            
            // Tab persistence
            const mcqTabs = document.querySelectorAll('#mcqTabs button');
            mcqTabs.forEach(tab => {
                tab.addEventListener('shown.bs.tab', event => {
                    localStorage.setItem('activeMcqTab', event.target.id);
                });
            });
            
            // Restore active tab
            const activeTabId = localStorage.getItem('activeMcqTab');
            if (activeTabId) {
                const activeTab = document.getElementById(activeTabId);
                if (activeTab) {
                    new bootstrap.Tab(activeTab).show();
                }
            }
            
            // Auto-refresh stats every 60 seconds
            function refreshStats() {
                fetch('{{ route("mcq.stats") }}')
                    .then(response => response.json())
                    .then(data => {
                        // Update stats cards if needed
                        console.log('Stats refreshed:', data);
                    })
                    .catch(error => console.error('Error refreshing stats:', error));
            }
            
            // Uncomment to enable auto-refresh
            // setInterval(refreshStats, 60000);
        });
    </script>
    @endpush

    <style>
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .activity-feed {
            max-height: 300px;
            overflow-y: auto;
        }
        
        .activity-item {
            padding-bottom: 10px;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .nav-tabs .nav-link {
            border: none;
            color: #6c757d;
            font-weight: 500;
        }
        
        .nav-tabs .nav-link.active {
            color: #0d6efd;
            border-bottom: 2px solid #0d6efd;
            background: transparent;
        }
        
        .badge.bg-secondary {
            background-color: #6c757d !important;
        }
    </style>
</x-app-layout>