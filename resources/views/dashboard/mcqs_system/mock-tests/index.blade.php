<x-app-layout>
    <main class="main-content">
        <div class="container-fluid px-0 px-md-3">
            <!-- Page Header -->
            <div class="page-header mb-4 px-3 px-md-0">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                    <div class="mb-3 mb-md-0">
                        <h1 class="h3 mb-1 mb-md-2">Mock Tests</h1>
                        <p class="text-muted mb-0 d-none d-md-block">Create and manage mock tests for practice and exams</p>
                        <p class="text-muted mb-0 d-block d-md-none">Manage mock tests</p>
                    </div>
                    <div class="w-100 w-md-auto">
                        <a href="{{ route('mock-tests.create') }}" class="btn btn-primary w-100 w-md-auto d-flex align-items-center justify-content-center">
                            <i class="fas fa-plus me-2"></i> 
                            <span class="d-none d-sm-inline">Create Test</span>
                            <span class="d-inline d-sm-none">Create</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mx-3 mx-md-0 mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle me-2"></i>
                        <div class="flex-grow-1">{{ session('success') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mx-3 mx-md-0 mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <div class="flex-grow-1">{{ session('error') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Filters - Collapsible on Mobile -->
            <div class="card mb-4 mx-3 mx-md-0">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-3 d-md-none" 
                     data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="false">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-filter me-2"></i>
                        <span>Filters</span>
                    </div>
                    <i class="fas fa-chevron-down"></i>
                </div>
                
                <div class="collapse d-md-block" id="filterCollapse" style="visibility: visible;">
                    <div class="card-body">
                        <form action="{{ route('mock-tests.index') }}" method="GET" class="row g-3">
                            <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                                <label class="form-label small fw-bold">Test Type</label>
                                <select name="test_type_id" class="form-select form-select-sm">
                                    <option value="">All Types</option>
                                    @foreach($testTypes as $type)
                                    <option value="{{ $type->id }}" {{ request('test_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-12 col-md-6 col-lg-4 col-xl-2">
                                <label class="form-label small fw-bold">Status</label>
                                <select name="status" class="form-select form-select-sm">
                                    <option value="">All Status</option>
                                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                                </select>
                            </div>
                            
                            <div class="col-12 col-md-6 col-lg-4 col-xl-2">
                                <label class="form-label small fw-bold">Access</label>
                                <select name="is_free" class="form-select form-select-sm">
                                    <option value="">All</option>
                                    <option value="1" {{ request('is_free') == '1' ? 'selected' : '' }}>Free</option>
                                    <option value="0" {{ request('is_free') == '0' ? 'selected' : '' }}>Premium</option>
                                </select>
                            </div>
                            
                            <div class="col-12 col-md-6 col-lg-8 col-xl-4">
                                <label class="form-label small fw-bold">Search</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" name="search" class="form-control" 
                                           placeholder="Search tests..." 
                                           value="{{ request('search') }}">
                                    <button class="btn btn-outline-secondary" type="button" onclick="this.closest('form').reset()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="col-12 col-md-6 col-lg-4 col-xl-1 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary btn-sm w-100 d-flex align-items-center justify-content-center">
                                    <i class="fas fa-filter me-2"></i> 
                                    <span class="d-none d-md-inline">Filter</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Stats Cards - Mobile Optimized -->
            <div class="row mb-4 g-3 px-3 px-md-0">
                <div class="col-6 col-md-3">
                    <div class="card card-hover h-100">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1 small">Total Tests</h6>
                                    <h4 class="mb-0">{{ $mockTests->total() }}</h4>
                                </div>
                                <div class="bg-primary bg-opacity-10 p-2 rounded">
                                    <i class="fas fa-file-alt text-primary"></i>
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
                                    <h6 class="text-muted mb-1 small">Published</h6>
                                    <h4 class="mb-0">{{ \App\Models\MockTest::where('status', 'published')->count() }}</h4>
                                </div>
                                <div class="bg-success bg-opacity-10 p-2 rounded">
                                    <i class="fas fa-check-circle text-success"></i>
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
                                    <h6 class="text-muted mb-1 small">Free Tests</h6>
                                    <h4 class="mb-0">{{ \App\Models\MockTest::where('is_free', true)->count() }}</h4>
                                </div>
                                <div class="bg-info bg-opacity-10 p-2 rounded">
                                    <i class="fas fa-unlock text-info"></i>
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
                                    <h6 class="text-muted mb-1 small">Total Attempts</h6>
                                    <h4 class="mb-0">{{ \App\Models\UserTestAttempt::count() }}</h4>
                                </div>
                                <div class="bg-warning bg-opacity-10 p-2 rounded">
                                    <i class="fas fa-users text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bulk Actions - Responsive -->
            <div class="card mb-3 mx-3 mx-md-0 d-none" id="bulkActionsCard">
                <div class="card-body p-3">
                    <div class="d-flex flex-column flex-md-row align-items-center gap-3">
                        <span class="me-md-3 mb-2 mb-md-0 text-center text-md-start" id="selectedCount">0 tests selected</span>
                        <div class="d-flex flex-column flex-md-row align-items-center gap-2 w-100 w-md-auto">
                            <select class="form-select form-select-sm me-md-2 flex-grow-1" id="bulkActionSelect">
                                <option value="">Bulk Actions</option>
                                <option value="publish">Publish</option>
                                <option value="draft">Move to Draft</option>
                                <option value="archive">Archive</option>
                                <option value="delete">Delete</option>
                            </select>
                            <div class="d-flex gap-2 mt-2 mt-md-0">
                                <button class="btn btn-sm btn-primary flex-grow-1" id="applyBulkAction">
                                    <span class="d-none d-md-inline">Apply</span>
                                    <i class="fas fa-check d-inline d-md-none"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-secondary" id="clearSelection">
                                    <span class="d-none d-md-inline">Clear</span>
                                    <i class="fas fa-times d-inline d-md-none"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mock Tests Table - Responsive -->
            <div class="card mx-3 mx-md-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0" id="mockTestsTable">
                            <thead class="d-none d-md-table-header-group">
                                <tr>
                                    <th width="40">
                                        <input type="checkbox" class="form-check-input" id="selectAll">
                                    </th>
                                    <th width="40">#</th>
                                    <th>Test Title</th>
                                    <th>Type/Mode</th>
                                    <th>Questions/Marks</th>
                                    <th>Time</th>
                                    <th width="90">Access</th>
                                    <th width="90">Attempts</th>
                                    <th width="100">Status</th>
                                    <th width="150" class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($mockTests as $test)
                                <!-- Desktop View -->
                                <tr data-id="{{ $test->id }}" class="d-none d-md-table-row {{ $test->status === \App\Enums\ContentStatus::Draft ? 'table-warning' : ($test->status === \App\Enums\ContentStatus::Archived ? 'table-secondary' : '') }}">
                                    <td>
                                        <input type="checkbox" class="form-check-input test-checkbox" value="{{ $test->id }}">
                                    </td>
                                    <td>{{ ($mockTests->currentPage() - 1) * $mockTests->perPage() + $loop->iteration }}</td>
                                    <td>
                                        <div>
                                            <strong class="d-block">{{ Str::limit($test->title, 30) }}</strong>
                                            <div class="small text-muted">
                                                @if($test->testType)
                                                <i class="{{ $test->testType->icon ?? 'fas fa-tag' }} me-1"></i>{{ Str::limit($test->testType->name, 15) }}
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <span class="badge bg-{{ $test->test_mode === \App\Enums\MockTestMode::Exam ? 'danger' : ($test->test_mode === \App\Enums\MockTestMode::Timed ? 'warning' : 'primary') }}">
                                                {{ ucfirst($test->test_mode->value) }}
                                            </span>
                                            @if($test->shuffle_questions)
                                            <span class="badge bg-info mt-1">Shuffle</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <div>
                                                <i class="fas fa-question-circle me-1"></i>
                                                {{ $test->total_questions }}
                                            </div>
                                            <div>
                                                <i class="fas fa-star me-1"></i>
                                                {{ $test->total_marks }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ $test->total_time_minutes }}m
                                        </div>
                                        <div class="small text-muted">
                                            Pass: {{ $test->passing_marks }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($test->is_free)
                                        <span class="badge bg-success small">
                                            <i class="fas fa-unlock me-1"></i> Free
                                        </span>
                                        @else
                                        <span class="badge bg-warning small">
                                            <i class="fas fa-lock me-1"></i> Premium
                                        </span>
                                        <div class="small text-muted">
                                            {{ config('app.currency') }}{{ number_format($test->price, 0) }}
                                        </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <span class="fw-bold">{{ $test->attempts_count }}</span>
                                            <div class="small text-muted">attempts</div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $test->status === \App\Enums\ContentStatus::Published ? 'success' : ($test->status === \App\Enums\ContentStatus::Draft ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($test->status->value) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('mock-tests.show', $test) }}" 
                                               class="btn btn-sm btn-outline-info"
                                               data-bs-toggle="tooltip" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('mock-tests.edit', $test) }}" 
                                               class="btn btn-sm btn-outline-primary"
                                               data-bs-toggle="tooltip" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('mock-tests.add-questions', $test) }}" 
                                               class="btn btn-sm btn-outline-success"
                                               data-bs-toggle="tooltip" title="Add Questions">
                                                <i class="fas fa-question"></i>
                                            </a>
                                            @if($test->attempts_count == 0)
                                            <form action="{{ route('mock-tests.destroy', $test) }}" 
                                                  method="POST" class="d-inline"
                                                  data-bs-toggle="tooltip" title="Delete">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                        onclick="return confirm('Delete this mock test?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @else
                                            <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                    data-bs-toggle="tooltip" 
                                                    title="Cannot delete (has attempts)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                
                                <!-- Mobile View Card -->
                                <div class="card mb-3 d-block d-md-none mx-3 {{ $test->status === \App\Enums\ContentStatus::Draft ? 'border-warning' : ($test->status === \App\Enums\ContentStatus::Archived ? 'border-secondary' : '') }}">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div class="d-flex align-items-center">
                                                <input type="checkbox" class="form-check-input me-2 test-checkbox" value="{{ $test->id }}">
                                                <strong class="me-2">#{{ ($mockTests->currentPage() - 1) * $mockTests->perPage() + $loop->iteration }}</strong>
                                                <span class="badge bg-{{ $test->status === \App\Enums\ContentStatus::Published ? 'success' : ($test->status === \App\Enums\ContentStatus::Draft ? 'warning' : 'secondary') }}">
                                                    {{ ucfirst($test->status->value) }}
                                                </span>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-{{ $test->test_mode === \App\Enums\MockTestMode::Exam ? 'danger' : ($test->test_mode === \App\Enums\MockTestMode::Timed ? 'warning' : 'primary') }}">
                                                    {{ ucfirst($test->test_mode->value) }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <h6 class="fw-bold mb-1">{{ $test->title }}</h6>
                                            <div class="small text-muted mb-2">
                                                @if($test->testType)
                                                <i class="{{ $test->testType->icon ?? 'fas fa-tag' }} me-1"></i>{{ $test->testType->name }}
                                                @endif
                                            </div>
                                            
                                            @if($test->shuffle_questions)
                                            <span class="badge bg-info small mb-2">
                                                <i class="fas fa-random me-1"></i> Shuffle
                                            </span>
                                            @endif
                                        </div>
                                        
                                        <div class="row g-2 mb-3">
                                            <div class="col-6">
                                                <div class="border rounded p-2 text-center">
                                                    <div class="small text-muted">Questions</div>
                                                    <div class="fw-bold">{{ $test->total_questions }}</div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="border rounded p-2 text-center">
                                                    <div class="small text-muted">Marks</div>
                                                    <div class="fw-bold">{{ $test->total_marks }}</div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="border rounded p-2 text-center">
                                                    <div class="small text-muted">Time</div>
                                                    <div class="fw-bold">{{ $test->total_time_minutes }}m</div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="border rounded p-2 text-center">
                                                    <div class="small text-muted">Attempts</div>
                                                    <div class="fw-bold">{{ $test->attempts_count }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div>
                                                @if($test->is_free)
                                                <span class="badge bg-success">
                                                    <i class="fas fa-unlock me-1"></i> Free
                                                </span>
                                                @else
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-lock me-1"></i> Premium
                                                </span>
                                                @endif
                                            </div>
                                            <div class="small">
                                                <span class="text-muted">Pass:</span> 
                                                <strong>{{ $test->passing_marks }}</strong>
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('mock-tests.show', $test) }}" 
                                                   class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-eye"></i>
                                                    <span class="ms-1 d-none d-sm-inline">View</span>
                                                </a>
                                                <a href="{{ route('mock-tests.edit', $test) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                    <span class="ms-1 d-none d-sm-inline">Edit</span>
                                                </a>
                                                <a href="{{ route('mock-tests.add-questions', $test) }}" 
                                                   class="btn btn-sm btn-outline-success">
                                                    <i class="fas fa-question"></i>
                                                    <span class="ms-1 d-none d-sm-inline">Add Qs</span>
                                                </a>
                                            </div>
                                            @if($test->attempts_count == 0)
                                            <form action="{{ route('mock-tests.destroy', $test) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                        onclick="return confirm('Delete this test?')">
                                                    <i class="fas fa-trash"></i>
                                                    <span class="ms-1 d-none d-sm-inline">Delete</span>
                                                </button>
                                            </form>
                                            @else
                                            <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                    data-bs-toggle="tooltip" 
                                                    title="Cannot delete (has attempts)">
                                                <i class="fas fa-trash"></i>
                                                <span class="ms-1 d-none d-sm-inline">Delete</span>
                                            </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                        
                        @if($mockTests->count() == 0)
                        <div class="text-center py-5">
                            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No mock tests found</h5>
                            <p class="text-muted">Create your first mock test to get started</p>
                            <a href="{{ route('mock-tests.create') }}" class="btn btn-primary mt-2">
                                <i class="fas fa-plus me-2"></i> Create Test
                            </a>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Pagination - Responsive -->
                    @if($mockTests->hasPages())
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center p-3 border-top">
                        <div class="text-muted small mb-2 mb-md-0 text-center text-md-start">
                            Showing {{ $mockTests->firstItem() }} to {{ $mockTests->lastItem() }} of {{ $mockTests->total() }}
                        </div>
                        <nav>
                            {{ $mockTests->onEachSide(1)->links('pagination::bootstrap-5') }}
                        </nav>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            tooltips.forEach(tooltip => new bootstrap.Tooltip(tooltip));
            
            // Bulk selection functionality
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.test-checkbox');
            const bulkActionsCard = document.getElementById('bulkActionsCard');
            const selectedCount = document.getElementById('selectedCount');
            const clearSelectionBtn = document.getElementById('clearSelection');
            
            // Select all checkbox (desktop only)
            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                    updateBulkActions();
                });
            }
            
            // Individual checkbox change
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateBulkActions);
            });
            
            // Clear selection
            if (clearSelectionBtn) {
                clearSelectionBtn.addEventListener('click', function() {
                    checkboxes.forEach(checkbox => checkbox.checked = false);
                    if (selectAll) selectAll.checked = false;
                    updateBulkActions();
                });
            }
            
            // Apply bulk action
            const applyBulkActionBtn = document.getElementById('applyBulkAction');
            if (applyBulkActionBtn) {
                applyBulkActionBtn.addEventListener('click', function() {
                    const action = document.getElementById('bulkActionSelect').value;
                    const selectedIds = Array.from(checkboxes)
                        .filter(cb => cb.checked)
                        .map(cb => cb.value);
                    
                    if (!action) {
                        alert('Please select an action');
                        return;
                    }
                    
                    if (selectedIds.length === 0) {
                        alert('Please select at least one test');
                        return;
                    }
                    
                    if (action === 'delete') {
                        if (!confirm(`Are you sure you want to delete ${selectedIds.length} test(s)?`)) {
                            return;
                        }
                    }
                    
                    // Submit bulk action
                    fetch('{{ route("mock-tests.bulk.action") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            action: action,
                            ids: selectedIds
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        } else {
                            alert(data.message || 'An error occurred');
                        }
                    })
                    .catch(error => console.error('Error:', error));
                });
            }
            
            function updateBulkActions() {
                const selected = Array.from(checkboxes).filter(cb => cb.checked).length;
                
                if (selected > 0) {
                    if (bulkActionsCard) {
                        bulkActionsCard.classList.remove('d-none');
                        selectedCount.textContent = `${selected} test${selected === 1 ? '' : 's'} selected`;
                    }
                } else {
                    if (bulkActionsCard) {
                        bulkActionsCard.classList.add('d-none');
                        if (selectAll) selectAll.checked = false;
                    }
                }
            }
            
            // Filter collapse icon toggle
            const filterCollapse = document.getElementById('filterCollapse');
            if (filterCollapse) {
                filterCollapse.addEventListener('show.bs.collapse', function() {
                    this.previousElementSibling.querySelector('.fa-chevron-down').className = 'fas fa-chevron-up';
                });
                filterCollapse.addEventListener('hide.bs.collapse', function() {
                    this.previousElementSibling.querySelector('.fa-chevron-up').className = 'fas fa-chevron-down';
                });
            }
            
            // Mobile responsive table initialization
            function initMobileTable() {
                if (window.innerWidth < 768) {
                    // Hide desktop table, show mobile cards
                    document.querySelectorAll('#mockTestsTable .d-none.d-md-table-row').forEach(row => {
                        row.style.display = 'none';
                    });
                } else {
                    // Show desktop table, hide mobile cards
                    document.querySelectorAll('#mockTestsTable .d-block.d-md-none').forEach(card => {
                        card.style.display = 'none';
                    });
                }
            }
            
            // Initialize and add resize listener
            initMobileTable();
            window.addEventListener('resize', initMobileTable);
            
            // Filter form reset
            document.querySelectorAll('button[type="button"]').forEach(button => {
                if (button.textContent.includes('fa-times') && button.closest('.input-group')) {
                    button.addEventListener('click', function() {
                        const form = this.closest('form');
                        form.reset();
                        form.submit();
                    });
                }
            });
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
            
            #mockTestsTable {
                min-width: auto;
            }
            
            .card.mb-3.d-block.d-md-none {
                margin-left: 0 !important;
                margin-right: 0 !important;
                border-radius: 0.375rem;
                border: 1px solid var(--bs-border-color);
            }
            
            .card.mb-3.d-block.d-md-none:first-child {
                margin-top: 1rem;
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
        
        /* Button Group Responsiveness */
        @media (max-width: 575.98px) {
            .btn-group .btn {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }
            
            .btn-group .btn i {
                margin: 0;
            }
        }
        
        @media (max-width: 767.98px) {
            .card.mb-3.d-block.d-md-none .btn {
                font-size: 0.75rem;
                padding: 0.25rem 0.5rem;
            }
            
            .card.mb-3.d-block.d-md-none .btn-group {
                flex-wrap: wrap;
                gap: 0.25rem;
            }
        }
        
        /* Pagination for Mobile */
        .pagination {
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .pagination .page-item {
            margin: 0.125rem;
        }
        
        /* Form Controls */
        @media (max-width: 767.98px) {
            .form-select-sm {
                font-size: 0.875rem;
                padding: 0.25rem 0.5rem;
            }
            
            .input-group-sm > .form-control,
            .input-group-sm > .form-select {
                font-size: 0.875rem;
            }
        }
        
        /* Mobile Card Layout */
        @media (max-width: 767.98px) {
            .card.mb-3.d-block.d-md-none .card-body {
                padding: 1rem;
            }
            
            .card.mb-3.d-block.d-md-none h6 {
                font-size: 0.875rem;
                line-height: 1.3;
            }
            
            .card.mb-3.d-block.d-md-none .row.g-2 {
                margin-left: -0.25rem;
                margin-right: -0.25rem;
            }
            
            .card.mb-3.d-block.d-md-none .col-6 {
                padding-left: 0.25rem;
                padding-right: 0.25rem;
            }
            
            .border.rounded.p-2 {
                padding: 0.5rem !important;
            }
        }
        
        /* Filter Collapse */
        .card-header[data-bs-toggle="collapse"] {
            cursor: pointer;
            user-select: none;
        }
        
        .card-header[data-bs-toggle="collapse"] .fa-chevron-down,
        .card-header[data-bs-toggle="collapse"] .fa-chevron-up {
            transition: transform 0.3s ease;
        }
        
        /* Status Colors */
        .table-warning {
            background-color: rgba(255, 243, 205, 0.3) !important;
        }
        
        .table-secondary {
            background-color: rgba(108, 117, 125, 0.1) !important;
        }
        
        .border-warning {
            border-color: #ffc107 !important;
        }
        
        .border-secondary {
            border-color: #6c757d !important;
        }
        
        /* Ensure proper spacing on mobile */
        @media (max-width: 767.98px) {
            .main-content {
                padding-top: 0.5rem;
            }
            
            .page-header,
            .alert,
            .card.mb-4 {
                margin-left: 0.75rem;
                margin-right: 0.75rem;
            }
        }
        
        /* Empty State */
        .text-center.py-5 i.fa-3x {
            font-size: 3rem;
        }
        
        @media (max-width: 767.98px) {
            .text-center.py-5 i.fa-3x {
                font-size: 2.5rem;
            }
            
            .text-center.py-5 h5 {
                font-size: 1.25rem;
            }
            
            .text-center.py-5 p {
                font-size: 0.875rem;
            }
        }
        
        /* Bulk Actions Card */
        @media (max-width: 767.98px) {
            #bulkActionsCard .card-body {
                padding: 1rem !important;
            }
            
            #bulkActionsCard .d-flex {
                flex-direction: column;
                align-items: stretch;
            }
            
            #bulkActionsCard .form-select-sm {
                margin-bottom: 0.5rem;
            }
            
            #bulkActionsCard .d-flex.gap-2 {
                width: 100%;
            }
            
            #bulkActionsCard .btn {
                flex: 1;
            }
        }
    </style>
</x-app-layout>