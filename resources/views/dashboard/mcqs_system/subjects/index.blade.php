<x-app-layout>
    <main class="main-content">
        <div class="container-fluid px-0 px-md-3">
            <!-- Page Header -->
            <div class="page-header mb-4 px-3 px-md-0">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                    <div class="mb-3 mb-md-0">
                        <h1 class="h3 mb-1 mb-md-2">Subjects</h1>
                        <p class="text-muted mb-0 d-none d-md-block">Manage subjects for different test types</p>
                        <p class="text-muted mb-0 d-block d-md-none">Manage subjects</p>
                    </div>
                    <div class="w-100 w-md-auto">
                        <a href="{{ route('subjects.create') }}" class="btn btn-primary w-100 w-md-auto d-flex align-items-center justify-content-center">
                            <i class="fas fa-plus me-2"></i> 
                            <span class="d-none d-sm-inline">Add Subject</span>
                            <span class="d-inline d-sm-none">Add</span>
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
                
                <div class="collapse d-md-block" style="visibility: visible" id="filterCollapse">
                    <div class="card-body">
                        <form action="{{ route('subjects.index') }}" method="GET" class="row g-3">
                            <div class="col-12 col-md-6 col-lg-4">
                                <label class="form-label small fw-bold">Test Type</label>
                                <select name="test_type_id" class="form-select form-select-sm">
                                    <option value="">All Test Types</option>
                                    @foreach($testTypes as $type)
                                    <option value="{{ $type->id }}" {{ request('test_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-12 col-md-6 col-lg-3">
                                <label class="form-label small fw-bold">Status</label>
                                <select name="status" class="form-select form-select-sm">
                                    <option value="">All Status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            
                            <div class="col-12 col-md-12 col-lg-4">
                                <label class="form-label small fw-bold">Search</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" name="search" class="form-control" 
                                           placeholder="Search by name or description..." 
                                           value="{{ request('search') }}">
                                    <button class="btn btn-outline-secondary" type="button" onclick="this.closest('form').reset()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="col-12 col-md-6 col-lg-1">
                                <label class="form-label small fw-bold d-md-none">&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-sm w-100 d-flex align-items-center justify-content-center">
                                    <i class="fas fa-filter me-1 me-md-0"></i>
                                    <span class="ms-1 d-md-none">Filter</span>
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
                                    <h6 class="text-muted mb-1 small">Total Subjects</h6>
                                    <h4 class="mb-0">{{ $subjects->total() }}</h4>
                                </div>
                                <div class="bg-primary bg-opacity-10 p-2 rounded">
                                    <i class="fas fa-book text-primary"></i>
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
                                    <h6 class="text-muted mb-1 small">Active</h6>
                                    <h4 class="mb-0">{{ \App\Models\Subject::where('status', 'active')->count() }}</h4>
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
                                    <h6 class="text-muted mb-1 small">Topics</h6>
                                    <h4 class="mb-0">{{ \App\Models\Topic::count() }}</h4>
                                </div>
                                <div class="bg-info bg-opacity-10 p-2 rounded">
                                    <i class="fas fa-folder text-info"></i>
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
                                    <h6 class="text-muted mb-1 small">Total MCQs</h6>
                                    <h4 class="mb-0">{{ \App\Models\Mcq::count() }}</h4>
                                </div>
                                <div class="bg-warning bg-opacity-10 p-2 rounded">
                                    <i class="fas fa-question-circle text-warning"></i>
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
                        <span class="me-md-3 mb-2 mb-md-0 text-center text-md-start" id="selectedCount">0 subjects selected</span>
                        <div class="d-flex flex-column flex-md-row align-items-center gap-2 w-100 w-md-auto">
                            <select class="form-select form-select-sm me-md-2 flex-grow-1" id="bulkActionSelect">
                                <option value="">Bulk Actions</option>
                                <option value="activate">Activate</option>
                                <option value="deactivate">Deactivate</option>
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

            <!-- Subjects Table - Responsive -->
            <div class="card mx-3 mx-md-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0" id="subjectsTable">
                            <thead class="d-none d-md-table-header-group">
                                <tr>
                                    <th width="40">
                                        <input type="checkbox" class="form-check-input" id="selectAll">
                                    </th>
                                    <th width="40">#</th>
                                    <th>Subject</th>
                                    <th>Test Type</th>
                                    <th width="80">Topics</th>
                                    <th width="80">MCQs</th>
                                    <th width="100">Status</th>
                                    <th width="100">Sort</th>
                                    <th width="150" class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subjects as $subject)
                                <tr data-id="{{ $subject->id }}" class="d-none d-md-table-row">
                                    <td>
                                        <input type="checkbox" class="form-check-input subject-checkbox" value="{{ $subject->id }}">
                                    </td>
                                    <td>
                                        <div class="color-indicator" style="background-color: {{ $subject->color_code }};"></div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($subject->icon)
                                            <i class="{{ $subject->icon }} me-2 text-primary"></i>
                                            @endif
                                            <div>
                                                <strong>{{ Str::limit($subject->name, 20) }}</strong>
                                                @if($subject->description)
                                                <small class="text-muted d-block mt-1">{{ Str::limit($subject->description, 30) }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($subject->testTypes->count() > 0)
                                            <div class="d-flex align-items-center">
                                                <!-- Show first 3 test types as badges -->
                                                @foreach($subject->testTypes->take(3) as $testType)
                                                    <span class="badge bg-light text-dark small me-1">
                                                        @if($testType->icon)
                                                            <i class="{{ $testType->icon }}"></i>
                                                        @else
                                                            {{ Str::limit($testType->name, 8) }}
                                                        @endif
                                                    </span>
                                                @endforeach
                                                
                                                <!-- Show dropdown button if more than 3 test types -->
                                                @if($subject->testTypes->count() > 3)
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle py-0 px-1" 
                                                                type="button" 
                                                                data-bs-toggle="dropdown" 
                                                                aria-expanded="false">
                                                            +{{ $subject->testTypes->count() - 3 }}
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            @foreach($subject->testTypes->skip(3) as $testType)
                                                                <li>
                                                                    <span class="dropdown-item-text">
                                                                        @if($testType->icon)
                                                                            <i class="{{ $testType->icon }} me-1"></i>
                                                                        @endif
                                                                        {{ $testType->name }}
                                                                    </span>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('topics.index', ['subject_id' => $subject->id]) }}" 
                                           class="badge bg-info text-decoration-none">
                                            {{ $subject->topics_count }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('mcqs.index', ['subject_id' => $subject->id]) }}" 
                                           class="badge bg-warning text-decoration-none">
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
                                               class="form-control form-control-sm sort-order-input w-100" 
                                               value="{{ $subject->sort_order }}"
                                               data-id="{{ $subject->id }}"
                                               min="0"
                                               style="max-width: 80px;">
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('subjects.edit', $subject) }}" 
                                               class="btn btn-sm btn-outline-primary"
                                               data-bs-toggle="tooltip" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('subjects.show', $subject) }}" 
                                               class="btn btn-sm btn-outline-info"
                                               data-bs-toggle="tooltip" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($subject->topics_count == 0 && $subject->mcqs_count == 0)
                                            <form action="{{ route('subjects.destroy', $subject) }}" 
                                                  method="POST" class="d-inline"
                                                  data-bs-toggle="tooltip" title="Delete">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                        onclick="return confirm('Delete this subject?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @else
                                            <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                    data-bs-toggle="tooltip" 
                                                    title="Cannot delete (has topics/MCQs)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                
                                <!-- Mobile View Card -->
                                <div class="card mb-3 d-block d-md-none mx-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div class="d-flex align-items-center">
                                                <input type="checkbox" class="form-check-input me-2 subject-checkbox" value="{{ $subject->id }}">
                                                <div class="color-indicator me-2" style="background-color: {{ $subject->color_code }};"></div>
                                                @if($subject->icon)
                                                <i class="{{ $subject->icon }} me-2 text-primary"></i>
                                                @endif
                                                <span class="badge bg-{{ $subject->status == 'active' ? 'success' : 'secondary' }}">
                                                    {{ ucfirst($subject->status) }}
                                                </span>
                                            </div>
                                            @if($subject->testTypes->count() > 0)
                                            <div class="text-end">
                                                <span class="badge bg-light text-dark small">
                                                    <i class="{{ $subject->testTypes->first()->icon ?? 'fas fa-list' }}"></i>
                                                    {{ $subject->testTypes->count() }}
                                                </span>
                                            </div>
                                            @endif
                                        </div>
                                        
                                        <div class="mb-3">
                                            <h6 class="fw-bold mb-1">{{ $subject->name }}</h6>
                                            @if($subject->description)
                                            <p class="small text-muted mb-2">
                                                {{ Str::limit($subject->description, 60) }}
                                            </p>
                                            @endif
                                            
                                            <!-- Test Types for Mobile -->
                                            @if($subject->testTypes->count() > 0)
                                                <div class="mt-2">
                                                    <div class="d-flex flex-wrap gap-1 align-items-center">
                                                        @foreach($subject->testTypes->take(2) as $testType)
                                                            <span class="badge bg-light text-dark small">
                                                                @if($testType->icon)
                                                                    <i class="{{ $testType->icon }} me-1"></i>
                                                                @endif
                                                                {{ Str::limit($testType->name, 10) }}
                                                            </span>
                                                        @endforeach
                                                        @if($subject->testTypes->count() > 2)
                                                            <div class="dropdown">
                                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle py-0 px-1" 
                                                                        type="button" 
                                                                        data-bs-toggle="dropdown" 
                                                                        aria-expanded="false">
                                                                    +{{ $subject->testTypes->count() - 2 }}
                                                                </button>
                                                                <ul class="dropdown-menu">
                                                                    @foreach($subject->testTypes->skip(2) as $testType)
                                                                        <li>
                                                                            <span class="dropdown-item-text">
                                                                                @if($testType->icon)
                                                                                    <i class="{{ $testType->icon }} me-1"></i>
                                                                                @endif
                                                                                {{ $testType->name }}
                                                                            </span>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('topics.index', ['subject_id' => $subject->id]) }}" 
                                                   class="badge bg-info text-decoration-none d-flex align-items-center">
                                                    <i class="fas fa-folder me-1"></i> {{ $subject->topics_count }}
                                                </a>
                                                <a href="{{ route('mcqs.index', ['subject_id' => $subject->id]) }}" 
                                                   class="badge bg-warning text-decoration-none d-flex align-items-center">
                                                    <i class="fas fa-question me-1"></i> {{ $subject->mcqs_count }}
                                                </a>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <span class="small text-muted me-2">Order:</span>
                                                <input type="number" 
                                                       class="form-control form-control-sm sort-order-input" 
                                                       value="{{ $subject->sort_order }}"
                                                       data-id="{{ $subject->id }}"
                                                       min="0"
                                                       style="width: 60px;">
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('subjects.edit', $subject) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                    <span class="ms-1 d-none d-sm-inline">Edit</span>
                                                </a>
                                                <a href="{{ route('subjects.show', $subject) }}" 
                                                   class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-eye"></i>
                                                    <span class="ms-1 d-none d-sm-inline">View</span>
                                                </a>
                                            </div>
                                            @if($subject->topics_count == 0 && $subject->mcqs_count == 0)
                                            <form action="{{ route('subjects.destroy', $subject) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                        onclick="return confirm('Delete this subject?')">
                                                    <i class="fas fa-trash"></i>
                                                    <span class="ms-1 d-none d-sm-inline">Delete</span>
                                                </button>
                                            </form>
                                            @else
                                            <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                    data-bs-toggle="tooltip" 
                                                    title="Cannot delete (has topics/MCQs)">
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
                        
                        @if($subjects->count() == 0)
                        <div class="text-center py-5">
                            <i class="fas fa-book fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No subjects found</h5>
                            <p class="text-muted">Create your first subject to get started</p>
                            <a href="{{ route('subjects.create') }}" class="btn btn-primary mt-2">
                                <i class="fas fa-plus me-2"></i> Add Subject
                            </a>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Pagination - Responsive -->
                    @if($subjects->hasPages())
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center p-3 border-top">
                        <div class="text-muted small mb-2 mb-md-0 text-center text-md-start">
                            Showing {{ $subjects->firstItem() }} to {{ $subjects->lastItem() }} of {{ $subjects->total() }}
                        </div>
                        <nav>
                            {{ $subjects->onEachSide(1)->links('pagination::bootstrap-5') }}
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
            }
            
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
                    if (bulkActionsCard) {
                        bulkActionsCard.classList.remove('d-none');
                        selectedCount.textContent = `${selected} subject${selected === 1 ? '' : 's'} selected`;
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
                    document.querySelectorAll('#subjectsTable .d-none.d-md-table-row').forEach(row => {
                        row.style.display = 'none';
                    });
                } else {
                    // Show desktop table, hide mobile cards
                    document.querySelectorAll('#subjectsTable .d-block.d-md-none').forEach(card => {
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
        
        .color-indicator {
            width: 20px;
            height: 20px;
            border-radius: 4px;
            display: inline-block;
            border: 1px solid #dee2e6;
        }
        
        @media (max-width: 767.98px) {
            .color-indicator {
                width: 16px;
                height: 16px;
            }
        }
        
        /* Test Type badges and dropdown */
        .badge.bg-light {
            border: 1px solid #dee2e6;
        }
        
        .dropdown-toggle.py-0.px-1 {
            padding: 0.125rem 0.25rem;
            font-size: 0.6875rem;
            line-height: 1.2;
            min-height: auto;
        }
        
        .dropdown-menu {
            font-size: 0.875rem;
            max-height: 200px;
            overflow-y: auto;
        }
        
        .dropdown-item-text {
            padding: 0.25rem 1rem;
            display: block;
        }
        
        @media (max-width: 767.98px) {
            .dropdown-toggle.py-0.px-1 {
                padding: 0.1rem 0.2rem;
                font-size: 0.625rem;
            }
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
            
            #subjectsTable {
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
            
            .sort-order-input {
                width: 60px !important;
                min-width: 60px !important;
                font-size: 0.875rem;
                padding: 0.25rem 0.5rem;
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
        
        /* Mobile Card Layout */
        @media (max-width: 767.98px) {
            .card.mb-3.d-block.d-md-none .card-body {
                padding: 1rem;
            }
            
            .card.mb-3.d-block.d-md-none h6 {
                font-size: 0.875rem;
                line-height: 1.3;
            }
            
            .card.mb-3.d-block.d-md-none p.small {
                font-size: 0.75rem;
                line-height: 1.4;
            }
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