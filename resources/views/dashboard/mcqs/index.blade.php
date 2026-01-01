<x-app-layout>
    <main class="main-content">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="page-header mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-2">MCQs Management</h1>
                        <p class="text-muted mb-0">Create and manage multiple choice questions</p>
                    </div>
                    <div>
                        <a href="{{ route('mcqs.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i> Add MCQ
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
                    <form action="{{ route('mcqs.index') }}" method="GET" class="row g-2">
                        <div class="col-md-2">
                            <label class="form-label">Subject</label>
                            <select name="subject_id" class="form-select">
                                <option value="">All Subjects</option>
                                @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-2">
                            <label class="form-label">Topic</label>
                            <select name="topic_id" class="form-select">
                                <option value="">All Topics</option>
                                @foreach($topics as $topic)
                                <option value="{{ $topic->id }}" {{ request('topic_id') == $topic->id ? 'selected' : '' }}>
                                    {{ $topic->title }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-2">
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
                            <label class="form-label">Difficulty</label>
                            <select name="difficulty_level" class="form-select">
                                <option value="">All Levels</option>
                                <option value="easy" {{ request('difficulty_level') == 'easy' ? 'selected' : '' }}>Easy</option>
                                <option value="medium" {{ request('difficulty_level') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="hard" {{ request('difficulty_level') == 'hard' ? 'selected' : '' }}>Hard</option>
                            </select>
                        </div>
                        
                        <div class="col-md-2">
                            <label class="form-label">Type</label>
                            <select name="question_type" class="form-select">
                                <option value="">All Types</option>
                                <option value="single" {{ request('question_type') == 'single' ? 'selected' : '' }}>Single Choice</option>
                                <option value="multiple" {{ request('question_type') == 'multiple' ? 'selected' : '' }}>Multiple Choice</option>
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
                            <label class="form-label">Premium</label>
                            <select name="is_premium" class="form-select">
                                <option value="">All</option>
                                <option value="1" {{ request('is_premium') == '1' ? 'selected' : '' }}>Premium</option>
                                <option value="0" {{ request('is_premium') == '0' ? 'selected' : '' }}>Free</option>
                            </select>
                        </div>
                        
                        <div class="col-md-2">
                            <label class="form-label">Verified</label>
                            <select name="is_verified" class="form-select">
                                <option value="">All</option>
                                <option value="1" {{ request('is_verified') == '1' ? 'selected' : '' }}>Verified</option>
                                <option value="0" {{ request('is_verified') == '0' ? 'selected' : '' }}>Unverified</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Search</label>
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Search questions..." 
                                       value="{{ request('search') }}">
                                <button class="btn btn-outline-secondary" type="button" onclick="this.form.reset()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="col-md-2 d-flex align-items-end">
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
                                    <h6 class="text-muted mb-2">Total MCQs</h6>
                                    <h3 class="mb-0">{{ $mcqs->total() }}</h3>
                                    <small class="text-success">
                                        {{ \App\Models\Mcq::whereDate('created_at', today())->count() }} new today
                                    </small>
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
                                    <h6 class="text-muted mb-2">Published</h6>
                                    <h3 class="mb-0">{{ \App\Models\Mcq::where('status', 'published')->count() }}</h3>
                                    <small class="text-success">
                                        {{ \App\Models\Mcq::where('status', 'published')->where('is_verified', true)->count() }} verified
                                    </small>
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
                                    <h6 class="text-muted mb-2">Premium</h6>
                                    <h3 class="mb-0">{{ \App\Models\Mcq::where('is_premium', true)->count() }}</h3>
                                    <small class="text-warning">
                                        {{ \App\Models\Mcq::where('is_premium', true)->where('status', 'published')->count() }} published
                                    </small>
                                </div>
                                <div class="bg-warning bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-crown fa-2x text-warning"></i>
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
                                    <h6 class="text-muted mb-2">Needs Review</h6>
                                    <h3 class="mb-0">{{ \App\Models\Mcq::where('is_verified', false)->count() }}</h3>
                                    <small class="text-danger">
                                        {{ \App\Models\Mcq::where('is_verified', false)->where('status', 'published')->count() }} published
                                    </small>
                                </div>
                                <div class="bg-danger bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-exclamation-circle fa-2x text-danger"></i>
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
                        <span class="me-3" id="selectedCount">0 MCQs selected</span>
                        <select class="form-select form-select-sm me-2" style="width: auto;" id="bulkActionSelect">
                            <option value="">Bulk Actions</option>
                            <option value="publish">Publish</option>
                            <option value="draft">Move to Draft</option>
                            <option value="archive">Archive</option>
                            <option value="verify">Verify</option>
                            <option value="unverify">Unverify</option>
                            <option value="delete">Delete</option>
                        </select>
                        <button class="btn btn-sm btn-primary" id="applyBulkAction">Apply</button>
                        <button class="btn btn-sm btn-outline-secondary ms-2" id="clearSelection">Clear</button>
                    </div>
                </div>
            </div>

            <!-- MCQs Table -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="mcqsTable">
                            <thead>
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" class="form-check-input" id="selectAll">
                                    </th>
                                    <th width="60">#</th>
                                    <th>Question</th>
                                    <th>Subject/Topic</th>
                                    <th>Type/Level</th>
                                    <th>Marks</th>
                                    <th>Status</th>
                                    <th width="180" class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($mcqs as $mcq)
                                <tr data-id="{{ $mcq->id }}" class="{{ $mcq->status == 'draft' ? 'table-warning' : ($mcq->status == 'archived' ? 'table-secondary' : '') }}">
                                    <td>
                                        <input type="checkbox" class="form-check-input mcq-checkbox" value="{{ $mcq->id }}">
                                    </td>
                                    <td>{{ ($mcqs->currentPage() - 1) * $mcqs->perPage() + $loop->iteration }}</td>
                                    <td>
                                        <div class="question-preview">
                                            <div class="fw-bold mb-1">
                                                {!! Str::limit(strip_tags($mcq->question), 80) !!}
                                            </div>
                                            <div class="small text-muted">
                                                <span class="badge bg-{{ $mcq->question_type == 'single' ? 'primary' : 'info' }}">
                                                    {{ $mcq->question_type == 'single' ? 'Single Choice' : 'Multiple Choice' }}
                                                </span>
                                                @if($mcq->is_premium)
                                                <span class="badge bg-warning ms-1">Premium</span>
                                                @endif
                                                @if($mcq->is_verified)
                                                <span class="badge bg-success ms-1">Verified</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <div class="fw-bold">{{ $mcq->subject->name ?? 'N/A' }}</div>
                                            <div class="text-muted">{{ $mcq->topic->title ?? 'N/A' }}</div>
                                            @if($mcq->testType)
                                            <div class="text-muted">
                                                <i class="fas fa-tag fa-xs me-1"></i>{{ $mcq->testType->name }}
                                            </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <div>
                                                <span class="badge bg-{{ $mcq->difficulty_level == 'easy' ? 'success' : ($mcq->difficulty_level == 'medium' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($mcq->difficulty_level) }}
                                                </span>
                                            </div>
                                            @if($mcq->time_limit_seconds)
                                            <div class="text-muted mt-1">
                                                <i class="fas fa-clock fa-xs me-1"></i>{{ $mcq->time_limit_seconds }}s
                                            </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <span class="fw-bold">{{ $mcq->marks }}</span>
                                            @if($mcq->negative_marks > 0)
                                            <div class="small text-danger">-{{ $mcq->negative_marks }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $mcq->status == 'published' ? 'success' : ($mcq->status == 'draft' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($mcq->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('mcqs.show', $mcq) }}" 
                                               class="btn btn-sm btn-outline-info"
                                               data-bs-toggle="tooltip" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('mcqs.edit', $mcq) }}" 
                                               class="btn btn-sm btn-outline-primary"
                                               data-bs-toggle="tooltip" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if(!$mcq->is_verified)
                                            <form action="{{ route('mcqs.verify', $mcq) }}" 
                                                  method="POST" class="d-inline"
                                                  data-bs-toggle="tooltip" title="Verify">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            @else
                                            <form action="{{ route('mcqs.unverify', $mcq) }}" 
                                                  method="POST" class="d-inline"
                                                  data-bs-toggle="tooltip" title="Unverify">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-warning">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                            @endif
                                            @if($mcq->mock_tests_count == 0)
                                            <form action="{{ route('mcqs.destroy', $mcq) }}" 
                                                  method="POST" class="d-inline"
                                                  data-bs-toggle="tooltip" title="Delete">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                        onclick="return confirm('Are you sure you want to delete this MCQ?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @else
                                            <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                    data-bs-toggle="tooltip" 
                                                    title="Cannot delete MCQ used in mock tests">
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
                    @if($mcqs->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted">
                            Showing {{ $mcqs->firstItem() }} to {{ $mcqs->lastItem() }} of {{ $mcqs->total() }} entries
                        </div>
                        <nav>
                            {{ $mcqs->links() }}
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
            const checkboxes = document.querySelectorAll('.mcq-checkbox');
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
                    alert('Please select at least one MCQ');
                    return;
                }
                
                if (action === 'delete') {
                    if (!confirm(`Are you sure you want to delete ${selectedIds.length} MCQ(s)?`)) {
                        return;
                    }
                }
                
                // Submit bulk action
                fetch('{{ route("mcqs.bulk.action") }}', {
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
                    selectedCount.textContent = `${selected} MCQ${selected === 1 ? '' : 's'} selected`;
                } else {
                    bulkActionsCard.classList.add('d-none');
                    selectAll.checked = false;
                }
            }
            
            // Filter form reset
            document.querySelectorAll('button[type="button"]').forEach(button => {
                if (button.textContent.includes('fa-times')) {
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
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .question-preview {
            max-width: 400px;
        }
        
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        #mcqsTable {
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
            
            .question-preview {
                max-width: 200px;
            }
        }
        
        .table-warning {
            background-color: rgba(255, 243, 205, 0.3);
        }
        
        .table-secondary {
            background-color: rgba(108, 117, 125, 0.1);
        }
    </style>
</x-app-layout>