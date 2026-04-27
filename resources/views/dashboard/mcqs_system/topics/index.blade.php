<x-app-layout>
    <main class="main-content">
        <div class="container-fluid">
            <x-page-header>
                <x-slot name="heading">
                    <h1 class="h3 mb-2">Topics</h1>
                    <p class="text-muted mb-0">Manage topics within subjects</p>
                </x-slot>
                <x-slot name="actions">
                    <x-button href="{{ route('topics.create') }}" variant="primary">
                        <i class="fas fa-plus me-2"></i> Add Topic
                    </x-button>
                </x-slot>
            </x-page-header>

            @if(session('success'))
                <x-alert variant="success">{{ session('success') }}</x-alert>
            @endif

            @if(session('error'))
                <x-alert variant="danger">{{ session('error') }}</x-alert>
            @endif

            <!-- Filters -->
            <x-card class="mb-4">
                <div class="card-body">
                    <form action="{{ route('topics.index') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
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
                            <label class="form-label">Difficulty</label>
                            <select name="difficulty_level" class="form-select">
                                <option value="">All Levels</option>
                                <option value="beginner" {{ request('difficulty_level') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                <option value="intermediate" {{ request('difficulty_level') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                <option value="advanced" {{ request('difficulty_level') == 'advanced' ? 'selected' : '' }}>Advanced</option>
                            </select>
                        </div>
                        
                        <div class="col-md-2">
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
                                       placeholder="Search by title or description..." 
                                       value="{{ request('search') }}">
                                <x-button variant="outline-secondary" type="button" onclick="this.form.reset()">
                                    <i class="fas fa-times"></i>
                                </x-button>
                            </div>
                        </div>
                        
                        <div class="col-md-1">
                            <label class="form-label">&nbsp;</label>
                            <x-button type="submit" variant="primary" class="w-100">
                                <i class="fas fa-filter"></i>
                            </x-button>
                        </div>
                    </form>
                </div>
            </x-card>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <x-card class="card-hover">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Total Topics</h6>
                                    <h3 class="mb-0">{{ $topics->total() }}</h3>
                                </div>
                                <div class="bg-primary bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-folder fa-2x text-primary"></i>
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
                                    <h6 class="text-muted mb-2">Active Topics</h6>
                                    <h3 class="mb-0">{{ \App\Models\Topic::where('status', 'active')->count() }}</h3>
                                </div>
                                <div class="bg-success bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-check-circle fa-2x text-success"></i>
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
                                    <h6 class="text-muted mb-2">Beginner Topics</h6>
                                    <h3 class="mb-0">{{ \App\Models\Topic::where('difficulty_level', 'beginner')->count() }}</h3>
                                </div>
                                <div class="bg-info bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-signal fa-2x text-info"></i>
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
                                    <h6 class="text-muted mb-2">Topic MCQs</h6>
                                    <h3 class="mb-0">{{ \App\Models\Topic::withCount('mcqs')->get()->sum('mcqs_count') }}</h3>
                                </div>
                                <div class="bg-warning bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-question-circle fa-2x text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </x-card>
                </div>
            </div>

            <!-- Bulk Actions -->
            <x-card class="mb-3 d-none" id="bulkActionsCard">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <span class="me-3" id="selectedCount">0 topics selected</span>
                        <select class="form-select form-select-sm me-2" style="width: auto;" id="bulkActionSelect">
                            <option value="">Bulk Actions</option>
                            <option value="activate">Activate</option>
                            <option value="deactivate">Deactivate</option>
                            <option value="delete">Delete</option>
                        </select>
                        <x-button type="button" variant="primary" class="btn-sm" id="applyBulkAction">Apply</x-button>
                        <x-button type="button" variant="outline-secondary" class="btn-sm ms-2" id="clearSelection">Clear</x-button>
                    </div>
                </div>
            </x-card>

            <!-- Topics Table -->
            <x-card>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="topicsTable">
                            <thead>
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" class="form-check-input" id="selectAll">
                                    </th>
                                    <th width="60">#</th>
                                    <th>Topic</th>
                                    <th>Subject</th>
                                    <th>Difficulty</th>
                                    <th>Time</th>
                                    <th>MCQs</th>
                                    <th width="100">Status</th>
                                    <th width="120">Sort Order</th>
                                    <th width="150" class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topics as $topic)
                                <tr data-id="{{ $topic->id }}">
                                    <td>
                                        <input type="checkbox" class="form-check-input topic-checkbox" value="{{ $topic->id }}">
                                    </td>
                                    <td>{{ ($topics->currentPage() - 1) * $topics->perPage() + $loop->iteration }}</td>
                                    <td>
                                        <div>
                                            <strong>{{ $topic->title }}</strong>
                                            @if($topic->description)
                                            <small class="text-muted d-block mt-1">{{ Str::limit($topic->description, 60) }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($topic->subject)
                                            <div class="color-indicator me-2" style="background-color: {{ $topic->subject->color_code }};"></div>
                                            <span>{{ $topic->subject->name }}</span>
                                            @else
                                            <span class="text-muted">-</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <x-badge :variant="$topic->difficulty_badge_variant">
                                            {{ $topic->formatted_difficulty }}
                                        </x-badge>
                                    </td>
                                    <td>
                                        <x-badge variant="light">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ $topic->estimated_time_minutes }} min
                                        </x-badge>
                                    </td>
                                    <td>
                                        <a href="{{ route('mcqs.index', ['topic_id' => $topic->id]) }}" class="text-decoration-none">
                                            <x-badge variant="warning">{{ $topic->mcqs_count }}</x-badge>
                                        </a>
                                    </td>
                                    <td>
                                        <x-badge :variant="$topic->status === \App\Enums\ActiveStatus::Active ? 'success' : 'secondary'">
                                            {{ ucfirst($topic->status->value) }}
                                        </x-badge>
                                    </td>
                                    <td>
                                        <input type="number" 
                                               class="form-control form-control-sm sort-order-input" 
                                               value="{{ $topic->sort_order }}"
                                               data-id="{{ $topic->id }}"
                                               style="width: 80px;">
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group" role="group">
                                            <x-button href="{{ route('topics.edit', $topic) }}"
                                               variant="outline-primary" class="btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </x-button>
                                            <x-button href="{{ route('topics.show', $topic) }}"
                                               variant="outline-info" class="btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </x-button>
                                            @if($topic->mcqs_count == 0)
                                            <form action="{{ route('topics.destroy', $topic) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <x-button type="submit" variant="outline-danger" class="btn-sm" 
                                                        onclick="return confirm('Are you sure you want to delete this topic?')">
                                                    <i class="fas fa-trash"></i>
                                                </x-button>
                                            </form>
                                            @else
                                            <x-button type="button" variant="outline-secondary" class="btn-sm" 
                                                    data-bs-toggle="tooltip" 
                                                    title="Cannot delete topic with MCQs">
                                                <i class="fas fa-trash"></i>
                                            </x-button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($topics->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted">
                            Showing {{ $topics->firstItem() }} to {{ $topics->lastItem() }} of {{ $topics->total() }} entries
                        </div>
                        <nav>
                            {{ $topics->links() }}
                        </nav>
                    </div>
                    @endif
                </div>
            </x-card>
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
            const checkboxes = document.querySelectorAll('.topic-checkbox');
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
                    alert('Please select at least one topic');
                    return;
                }
                
                if (action === 'delete') {
                    if (!confirm(`Are you sure you want to delete ${selectedIds.length} topic(s)?`)) {
                        return;
                    }
                }
                
                // Submit bulk action
                fetch('{{ route("topics.bulk.action") }}', {
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
                    
                    fetch('{{ route("topics.update.sort") }}', {
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
                    selectedCount.textContent = `${selected} topic${selected === 1 ? '' : 's'} selected`;
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
            width: 16px;
            height: 16px;
            border-radius: 3px;
            display: inline-block;
        }
        
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        #topicsTable {
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