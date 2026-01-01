<x-app-layout>
    <main class="main-content">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="page-header mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-2">Mock Tests</h1>
                        <p class="text-muted mb-0">Create and manage mock tests for practice and exams</p>
                    </div>
                    <div>
                        <a href="{{ route('mock-tests.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i> Create Test
                        </a>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('mock-tests.index') }}" method="GET" class="row g-2">
                        <div class="col-md-3">
                            <label class="form-label">Test Type</label>
                            <select name="test_type_id" class="form-select">
                                <option value="">All Types</option>
                                @foreach($testTypes as $type)
                                <option value="{{ $type->id }}" {{ request('test_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                        </div>
                        
                        <div class="col-md-2">
                            <label class="form-label">Access</label>
                            <select name="is_free" class="form-select">
                                <option value="">All</option>
                                <option value="1" {{ request('is_free') == '1' ? 'selected' : '' }}>Free</option>
                                <option value="0" {{ request('is_free') == '0' ? 'selected' : '' }}>Premium</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Search</label>
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Search tests..." 
                                       value="{{ request('search') }}">
                                <button class="btn btn-outline-secondary" type="button" onclick="this.form.reset()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-filter me-2"></i> Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card card-hover">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Total Tests</h6>
                                    <h3 class="mb-0">{{ $mockTests->total() }}</h3>
                                </div>
                                <div class="bg-primary bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-file-alt fa-2x text-primary"></i>
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
                                    <h6 class="text-muted mb-2">Published</h6>
                                    <h3 class="mb-0">{{ \App\Models\MockTest::where('status', 'published')->count() }}</h3>
                                </div>
                                <div class="bg-success bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-check-circle fa-2x text-success"></i>
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
                                    <h6 class="text-muted mb-2">Free Tests</h6>
                                    <h3 class="mb-0">{{ \App\Models\MockTest::where('is_free', true)->count() }}</h3>
                                </div>
                                <div class="bg-info bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-unlock fa-2x text-info"></i>
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
                                    <h6 class="text-muted mb-2">Total Attempts</h6>
                                    <h3 class="mb-0">{{ \App\Models\UserTestAttempt::count() }}</h3>
                                </div>
                                <div class="bg-warning bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-users fa-2x text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bulk Actions -->
            <div class="card mb-3 d-none" id="bulkActionsCard">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <span class="me-3" id="selectedCount">0 tests selected</span>
                        <select class="form-select form-select-sm me-2" style="width: auto;" id="bulkActionSelect">
                            <option value="">Bulk Actions</option>
                            <option value="publish">Publish</option>
                            <option value="draft">Move to Draft</option>
                            <option value="archive">Archive</option>
                            <option value="delete">Delete</option>
                        </select>
                        <button class="btn btn-sm btn-primary" id="applyBulkAction">Apply</button>
                        <button class="btn btn-sm btn-outline-secondary ms-2" id="clearSelection">Clear</button>
                    </div>
                </div>
            </div>

            <!-- Mock Tests Table -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="mockTestsTable">
                            <thead>
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" class="form-check-input" id="selectAll">
                                    </th>
                                    <th width="60">#</th>
                                    <th>Test Title</th>
                                    <th>Type/Mode</th>
                                    <th>Questions/Marks</th>
                                    <th>Time</th>
                                    <th>Access</th>
                                    <th>Attempts</th>
                                    <th width="100">Status</th>
                                    <th width="180" class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($mockTests as $test)
                                <tr data-id="{{ $test->id }}" class="{{ $test->status == 'draft' ? 'table-warning' : ($test->status == 'archived' ? 'table-secondary' : '') }}">
                                    <td>
                                        <input type="checkbox" class="form-check-input test-checkbox" value="{{ $test->id }}">
                                    </td>
                                    <td>{{ ($mockTests->currentPage() - 1) * $mockTests->perPage() + $loop->iteration }}</td>
                                    <td>
                                        <div>
                                            <strong>{{ $test->title }}</strong>
                                            <div class="small text-muted">
                                                @if($test->testType)
                                                <i class="{{ $test->testType->icon ?? 'fas fa-tag' }} me-1"></i>{{ $test->testType->name }}
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <span class="badge bg-{{ $test->test_mode == 'exam' ? 'danger' : ($test->test_mode == 'timed' ? 'warning' : 'primary') }}">
                                                {{ ucfirst($test->test_mode) }}
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
                                                {{ $test->total_questions }} questions
                                            </div>
                                            <div>
                                                <i class="fas fa-star me-1"></i>
                                                {{ $test->total_marks }} marks
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ $test->total_time_minutes }} min
                                        </div>
                                        <div class="small text-muted">
                                            Pass: {{ $test->passing_marks }} marks
                                        </div>
                                    </td>
                                    <td>
                                        @if($test->is_free)
                                        <span class="badge bg-success">
                                            <i class="fas fa-unlock me-1"></i> Free
                                        </span>
                                        @else
                                        <span class="badge bg-warning">
                                            <i class="fas fa-lock me-1"></i> Premium
                                        </span>
                                        <div class="small text-muted">
                                            {{ config('app.currency') }}{{ number_format($test->price, 2) }}
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
                                        <span class="badge bg-{{ $test->status == 'published' ? 'success' : ($test->status == 'draft' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($test->status) }}
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
                                                        onclick="return confirm('Are you sure you want to delete this mock test?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @else
                                            <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                    data-bs-toggle="tooltip" 
                                                    title="Cannot delete test with attempts">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($mockTests->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted">
                            Showing {{ $mockTests->firstItem() }} to {{ $mockTests->lastItem() }} of {{ $mockTests->total() }} entries
                        </div>
                        <nav>
                            {{ $mockTests->links() }}
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
            
            // Select all checkbox
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateBulkActions();
            });
            
            // Individual checkbox change
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateBulkActions);
            });
            
            // Clear selection
            clearSelectionBtn.addEventListener('click', function() {
                checkboxes.forEach(checkbox => checkbox.checked = false);
                selectAll.checked = false;
                updateBulkActions();
            });
            
            // Apply bulk action
            document.getElementById('applyBulkAction').addEventListener('click', function() {
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
            
            function updateBulkActions() {
                const selected = Array.from(checkboxes).filter(cb => cb.checked).length;
                
                if (selected > 0) {
                    bulkActionsCard.classList.remove('d-none');
                    selectedCount.textContent = `${selected} test${selected === 1 ? '' : 's'} selected`;
                } else {
                    bulkActionsCard.classList.add('d-none');
                    selectAll.checked = false;
                }
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
        
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        #mockTestsTable {
            min-width: 992px;
        }

        @media (max-width: 768px) {
            .table {
                font-size: 0.875rem;
            }
            
            .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }
        }
    </style>
</x-app-layout>