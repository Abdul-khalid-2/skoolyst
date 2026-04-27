<x-app-layout>
    <main class="main-content">
        <div class="container-fluid px-0 px-md-3">
            <!-- Page Header -->
            <div class="page-header mb-4 px-3 px-md-0">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                    <div class="mb-3 mb-md-0">
                        <h1 class="h3 mb-1 mb-md-2">Test Types</h1>
                        <p class="text-muted mb-0 d-none d-md-block">Manage different types of tests (Entry Test, Job Test, University Test, etc.)</p>
                        <p class="text-muted mb-0 d-block d-md-none">Manage test types</p>
                    </div>
                    <div class="w-100 w-md-auto">
                        <a href="{{ route('test-types.create') }}" class="btn btn-primary w-100 w-md-auto d-flex align-items-center justify-content-center">
                            <i class="fas fa-plus me-2"></i> 
                            <span class="d-none d-sm-inline">Add Test Type</span>
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

            <!-- Stats Cards - Mobile Optimized -->
            <div class="row mb-4 g-3 px-3 px-md-0">
                <div class="col-6 col-md-3">
                    <div class="card card-hover h-100">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1 small">Total Types</h6>
                                    <h4 class="mb-0">{{ $testTypes->total() }}</h4>
                                    <small class="text-muted d-none d-md-block">Test types</small>
                                </div>
                                <div class="bg-primary bg-opacity-10 p-2 rounded">
                                    <i class="fas fa-list text-primary"></i>
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
                                    <h4 class="mb-0">{{ \App\Models\TestType::where('status', 'active')->count() }}</h4>
                                    <small class="text-success d-none d-md-block">Active types</small>
                                    <small class="text-success d-block d-md-none">Active</small>
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
                                    <h6 class="text-muted mb-1 small">Subjects</h6>
                                    <h4 class="mb-0">{{ \App\Models\Subject::count() }}</h4>
                                    <small class="text-muted d-none d-md-block">Total subjects</small>
                                </div>
                                <div class="bg-info bg-opacity-10 p-2 rounded">
                                    <i class="fas fa-book text-info"></i>
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
                                    <h6 class="text-muted mb-1 small">MCQs</h6>
                                    <h4 class="mb-0">{{ \App\Models\Mcq::count() }}</h4>
                                    <small class="text-muted d-none d-md-block">Total questions</small>
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
                        <span class="me-md-3 mb-2 mb-md-0 text-center text-md-start" id="selectedCount">0 types selected</span>
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

            <!-- Test Types Table - Responsive -->
            <div class="card mx-3 mx-md-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0" id="testTypesTable">
                            <thead class="d-none d-md-table-header-group">
                                <tr>
                                    <th width="40">
                                        <input type="checkbox" class="form-check-input" id="selectAll">
                                    </th>
                                    <th width="40">#</th>
                                    <th>Test Type</th>
                                    <th width="60">Icon</th>
                                    <th>Description</th>
                                    <th width="80">Subjects</th>
                                    <th width="100">Status</th>
                                    <th width="100">Sort</th>
                                    <th width="150" class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($testTypes as $testType)
                                <!-- Desktop View -->
                                <tr data-id="{{ $testType->id }}" class="d-none d-md-table-row">
                                    <td>
                                        <input type="checkbox" class="form-check-input test-type-checkbox" value="{{ $testType->id }}">
                                    </td>
                                    <td>{{ ($testTypes->currentPage() - 1) * $testTypes->perPage() + $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <strong class="me-2">{{ Str::limit($testType->name, 20) }}</strong>
                                        </div>
                                        <small class="text-muted d-block mt-1">{{ Str::limit($testType->slug, 15) }}</small>
                                    </td>
                                    <td>
                                        @if($testType->icon)
                                        <i class="{{ $testType->icon }} text-primary" title="{{ $testType->icon }}"></i>
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($testType->description)
                                        <span data-bs-toggle="tooltip" title="{{ $testType->description }}">
                                            {{ Str::limit($testType->description, 30) }}
                                        </span>
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('subjects.index', ['test_type_id' => $testType->id]) }}" 
                                           class="badge bg-primary text-decoration-none">
                                            {{ $testType->subjects_count ?? 0 }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $testType->status === \App\Enums\ActiveStatus::Active ? 'success' : 'secondary' }}">
                                            {{ ucfirst($testType->status->value) }}
                                        </span>
                                    </td>
                                    <td>
                                        <input type="number" 
                                               class="form-control form-control-sm sort-order-input w-100" 
                                               value="{{ $testType->sort_order }}"
                                               data-id="{{ $testType->id }}"
                                               min="0"
                                               style="max-width: 80px;">
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('test-types.edit', $testType) }}" 
                                               class="btn btn-sm btn-outline-primary"
                                               data-bs-toggle="tooltip" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('test-types.show', $testType) }}" 
                                               class="btn btn-sm btn-outline-info"
                                               data-bs-toggle="tooltip" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($testType->subjects_count == 0)
                                            <form action="{{ route('test-types.destroy', $testType) }}" 
                                                  method="POST" class="d-inline"
                                                  data-bs-toggle="tooltip" title="Delete">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                        onclick="return confirm('Delete this test type?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @else
                                            <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                    data-bs-toggle="tooltip" 
                                                    title="Cannot delete (has subjects)">
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
                                                <input type="checkbox" class="form-check-input me-2 test-type-checkbox" value="{{ $testType->id }}">
                                                <strong class="me-2">#{{ ($testTypes->currentPage() - 1) * $testTypes->perPage() + $loop->iteration }}</strong>
                                                <span class="badge bg-{{ $testType->status === \App\Enums\ActiveStatus::Active ? 'success' : 'secondary' }}">
                                                    {{ ucfirst($testType->status->value) }}
                                                </span>
                                            </div>
                                            @if($testType->icon)
                                            <div>
                                                <i class="{{ $testType->icon }} text-primary"></i>
                                            </div>
                                            @endif
                                        </div>
                                        
                                        <div class="mb-3">
                                            <h6 class="fw-bold mb-1">{{ $testType->name }}</h6>
                                            <small class="text-muted d-block mb-2">{{ $testType->slug }}</small>
                                            
                                            @if($testType->description)
                                            <p class="small text-muted mb-2">
                                                {{ Str::limit($testType->description, 60) }}
                                            </p>
                                            @endif
                                        </div>
                                        
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div>
                                                <a href="{{ route('subjects.index', ['test_type_id' => $testType->id]) }}" 
                                                   class="badge bg-primary text-decoration-none">
                                                    <i class="fas fa-book me-1"></i> {{ $testType->subjects_count ?? 0 }} subjects
                                                </a>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <span class="small text-muted me-2">Order:</span>
                                                <input type="number" 
                                                       class="form-control form-control-sm sort-order-input" 
                                                       value="{{ $testType->sort_order }}"
                                                       data-id="{{ $testType->id }}"
                                                       min="0"
                                                       style="width: 60px;">
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('test-types.edit', $testType) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                    <span class="ms-1 d-none d-sm-inline">Edit</span>
                                                </a>
                                                <a href="{{ route('test-types.show', $testType) }}" 
                                                   class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-eye"></i>
                                                    <span class="ms-1 d-none d-sm-inline">View</span>
                                                </a>
                                            </div>
                                            @if($testType->subjects_count == 0)
                                            <form action="{{ route('test-types.destroy', $testType) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                        onclick="return confirm('Delete this test type?')">
                                                    <i class="fas fa-trash"></i>
                                                    <span class="ms-1 d-none d-sm-inline">Delete</span>
                                                </button>
                                            </form>
                                            @else
                                            <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                    data-bs-toggle="tooltip" 
                                                    title="Cannot delete (has subjects)">
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
                        
                        @if($testTypes->count() == 0)
                        <div class="text-center py-5">
                            <i class="fas fa-list fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No test types found</h5>
                            <p class="text-muted">Create your first test type to get started</p>
                            <a href="{{ route('test-types.create') }}" class="btn btn-primary mt-2">
                                <i class="fas fa-plus me-2"></i> Add Test Type
                            </a>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Pagination - Responsive -->
                    @if($testTypes->hasPages())
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center p-3 border-top">
                        <div class="text-muted small mb-2 mb-md-0 text-center text-md-start">
                            Showing {{ $testTypes->firstItem() }} to {{ $testTypes->lastItem() }} of {{ $testTypes->total() }}
                        </div>
                        <nav>
                            {{ $testTypes->onEachSide(1)->links('pagination::bootstrap-5') }}
                        </nav>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    @push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            tooltips.forEach(tooltip => new bootstrap.Tooltip(tooltip));
            
            // Bulk selection functionality
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.test-type-checkbox');
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
                    alert('Please select at least one test type');
                    return;
                }
                
                if (action === 'delete') {
                    if (!confirm(`Are you sure you want to delete ${selectedIds.length} test type(s)?`)) {
                        return;
                    }
                }
                
                // Submit bulk action
                fetch('{{ route("test-types.bulk.action") }}', {
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
                    
                    fetch('{{ route("test-types.update.sort") }}', {
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
                    selectedCount.textContent = `${selected} test type${selected === 1 ? '' : 's'} selected`;
                } else {
                    if (bulkActionsCard) {
                        bulkActionsCard.classList.add('d-none');
                        if (selectAll) selectAll.checked = false;
                    }
                }
            }
            
            // Mobile responsive table initialization
            function initMobileTable() {
                if (window.innerWidth < 768) {
                    // Hide desktop table, show mobile cards
                    document.querySelectorAll('#testTypesTable .d-none.d-md-table-row').forEach(row => {
                        row.style.display = 'none';
                    });
                    document.querySelectorAll('#testTypesTable .d-block.d-md-none').forEach(card => {
                        card.style.display = 'block';
                    });
                } else {
                    // Show desktop table, hide mobile cards
                    document.querySelectorAll('#testTypesTable .d-none.d-md-table-row').forEach(row => {
                        row.style.display = 'table-row';
                    });
                    document.querySelectorAll('#testTypesTable .d-block.d-md-none').forEach(card => {
                        card.style.display = 'none';
                    });
                }
            }
            
            // Initialize and add resize listener
            initMobileTable();
            window.addEventListener('resize', initMobileTable);
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
            
            #testTypesTable {
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
            
            .sort-order-input {
                width: 60px !important;
                min-width: 60px !important;
                font-size: 0.875rem;
                padding: 0.25rem 0.5rem;
            }
        }
        
        /* Mobile Card Layout */
        @media (max-width: 767.98px) {
            .card.mb-3.d-block.d-md-none .card-body {
                padding: 1rem;
            }
            
            .card.mb-3.d-block.d-md-none .btn-group {
                flex-wrap: nowrap;
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
        
        /* Sort Order Input */
        .sort-order-input {
            transition: all 0.2s ease;
        }
        
        .sort-order-input:focus {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 0.2rem rgba(var(--bs-primary-rgb), 0.25);
        }
        
        /* Action Buttons */
        .btn-group .form {
            display: inline;
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
            
            .container-fluid.px-0.px-md-3 {
                padding-left: 0 !important;
                padding-right: 0 !important;
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