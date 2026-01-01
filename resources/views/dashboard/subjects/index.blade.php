<x-app-layout>
    <main class="main-content">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="page-header mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-2">Subjects</h1>
                        <p class="text-muted mb-0">Manage subjects for different test types</p>
                    </div>
                    <div>
                        <a href="{{ route('subjects.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i> Add Subject
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
                    <form action="{{ route('subjects.index') }}" method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Test Type</label>
                            <select name="test_type_id" class="form-select">
                                <option value="">All Test Types</option>
                                @foreach($testTypes as $type)
                                <option value="{{ $type->id }}" {{ request('test_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Search</label>
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Search by name or description..." 
                                       value="{{ request('search') }}">
                                <button class="btn btn-outline-secondary" type="button" onclick="this.form.reset()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="col-md-1">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-filter"></i>
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
                                    <h6 class="text-muted mb-2">Total Subjects</h6>
                                    <h3 class="mb-0">{{ $subjects->total() }}</h3>
                                </div>
                                <div class="bg-primary bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-book fa-2x text-primary"></i>
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
                                    <h6 class="text-muted mb-2">Active Subjects</h6>
                                    <h3 class="mb-0">{{ \App\Models\Subject::where('status', 'active')->count() }}</h3>
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
                                    <h6 class="text-muted mb-2">Topics</h6>
                                    <h3 class="mb-0">{{ \App\Models\Topic::count() }}</h3>
                                </div>
                                <div class="bg-info bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-folder fa-2x text-info"></i>
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
                                    <h6 class="text-muted mb-2">Total MCQs</h6>
                                    <h3 class="mb-0">{{ \App\Models\Mcq::count() }}</h3>
                                </div>
                                <div class="bg-warning bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-question-circle fa-2x text-warning"></i>
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
                        <span class="me-3" id="selectedCount">0 subjects selected</span>
                        <select class="form-select form-select-sm me-2" style="width: auto;" id="bulkActionSelect">
                            <option value="">Bulk Actions</option>
                            <option value="activate">Activate</option>
                            <option value="deactivate">Deactivate</option>
                            <option value="delete">Delete</option>
                        </select>
                        <button class="btn btn-sm btn-primary" id="applyBulkAction">Apply</button>
                        <button class="btn btn-sm btn-outline-secondary ms-2" id="clearSelection">Clear</button>
                    </div>
                </div>
            </div>

            <!-- Subjects Table -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="subjectsTable">
                            <thead>
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" class="form-check-input" id="selectAll">
                                    </th>
                                    <th width="60">#</th>
                                    <th>Subject</th>
                                    <th>Test Type</th>
                                    <th>Topics</th>
                                    <th>MCQs</th>
                                    <th width="100">Status</th>
                                    <th width="120">Sort Order</th>
                                    <th width="150" class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subjects as $subject)
                                <tr data-id="{{ $subject->id }}">
                                    <td>
                                        <input type="checkbox" class="form-check-input subject-checkbox" value="{{ $subject->id }}">
                                    </td>
                                    <td>
                                        <div class="color-indicator" style="background-color: {{ $subject->color_code }};"></div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($subject->icon)
                                            <i class="{{ $subject->icon }} me-3 text-primary"></i>
                                            @endif
                                            <div>
                                                <strong>{{ $subject->name }}</strong>
                                                @if($subject->description)
                                                <small class="text-muted d-block mt-1">{{ Str::limit($subject->description, 50) }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($subject->testType)
                                        <span class="badge bg-light text-dark">
                                            <i class="{{ $subject->testType->icon ?? 'fas fa-list' }} me-1"></i>
                                            {{ $subject->testType->name }}
                                        </span>
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('topics.index', ['subject_id' => $subject->id]) }}" 
                                           class="badge bg-info">
                                            {{ $subject->topics_count }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('mcqs.index', ['subject_id' => $subject->id]) }}" 
                                           class="badge bg-warning">
                                            {{ $subject->mcqs_count }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $subject->status == 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($subject->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <input type="number" 
                                               class="form-control form-control-sm sort-order-input" 
                                               value="{{ $subject->sort_order }}"
                                               data-id="{{ $subject->id }}"
                                               style="width: 80px;">
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('subjects.edit', $subject) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('subjects.show', $subject) }}" 
                                               class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($subject->topics_count == 0 && $subject->mcqs_count == 0)
                                            <form action="{{ route('subjects.destroy', $subject) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                        onclick="return confirm('Are you sure you want to delete this subject?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @else
                                            <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                    data-bs-toggle="tooltip" 
                                                    title="Cannot delete subject with topics or MCQs">
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
                    @if($subjects->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted">
                            Showing {{ $subjects->firstItem() }} to {{ $subjects->lastItem() }} of {{ $subjects->total() }} entries
                        </div>
                        <nav>
                            {{ $subjects->links() }}
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
            const checkboxes = document.querySelectorAll('.subject-checkbox');
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
                    alert('Please select at least one subject');
                    return;
                }
                
                if (action === 'delete') {
                    if (!confirm(`Are you sure you want to delete ${selectedIds.length} subject(s)?`)) {
                        return;
                    }
                }
                
                // Submit bulk action
                fetch('{{ route("subjects.bulk.action") }}', {
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
            
            // Update sort order on change
            document.querySelectorAll('.sort-order-input').forEach(input => {
                input.addEventListener('change', function() {
                    const id = this.dataset.id;
                    const sortOrder = this.value;
                    
                    fetch('{{ route("subjects.update.sort") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            categories: [{
                                id: id,
                                sort_order: sortOrder
                            }]
                        })
                    });
                });
            });
            
            function updateBulkActions() {
                const selected = Array.from(checkboxes).filter(cb => cb.checked).length;
                
                if (selected > 0) {
                    bulkActionsCard.classList.remove('d-none');
                    selectedCount.textContent = `${selected} subject${selected === 1 ? '' : 's'} selected`;
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
        
        .color-indicator {
            width: 20px;
            height: 20px;
            border-radius: 4px;
            display: inline-block;
            border: 1px solid #dee2e6;
        }
        
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        #subjectsTable {
            min-width: 768px;
        }

        .sort-order-input {
            min-width: 60px;
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