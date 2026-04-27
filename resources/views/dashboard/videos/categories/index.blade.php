<x-app-layout>
    <main class="main-content">
        <section id="video-categories" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0">Video Categories</h2>
                    <p class="mb-0 text-muted">Manage video categories and organization</p>
                </div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                    <i class="fas fa-plus me-2"></i> Add Category
                </button>
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

            <!-- Bulk Actions -->
            <div class="card mb-3 d-none" id="bulkActionsCard">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <span class="me-3" id="selectedCount">0 categories selected</span>
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

            <!-- Categories Table -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="categoriesTable">
                            <thead>
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" class="form-check-input" id="selectAll">
                                    </th>
                                    <th width="80">#</th>
                                    <th>Category Name</th>
                                    <th>Slug</th>
                                    <th width="100">Videos</th>
                                    <th width="120">Status</th>
                                    <th width="120">Sort Order</th>
                                    <th width="150" class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $category)
                                <tr data-id="{{ $category->id }}">
                                    <td>
                                        <input type="checkbox" class="form-check-input category-checkbox" value="{{ $category->id }}">
                                    </td>
                                    <td>{{ ($categories->currentPage() - 1) * $categories->perPage() + $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <strong>{{ $category->name }}</strong>
                                        </div>
                                        @if($category->description)
                                        <small class="text-muted d-block mt-1">{{ Str::limit($category->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <code>{{ $category->slug }}</code>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $category->videos_count }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $category->status === \App\Enums\ActiveStatus::Active ? 'success' : 'secondary' }}">
                                            {{ ucfirst($category->status->value) }}
                                        </span>
                                    </td>
                                    <td>
                                        <input type="number" 
                                               class="form-control form-control-sm sort-order-input" 
                                               value="{{ $category->sort_order }}"
                                               data-id="{{ $category->id }}"
                                               style="width: 80px;">
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('video-categories.edit', $category) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($category->videos_count == 0)
                                            <form action="{{ route('video-categories.destroy', $category) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                        onclick="return confirm('Are you sure you want to delete this category?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @else
                                            <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                    data-bs-toggle="tooltip" 
                                                    title="Cannot delete category with videos">
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
                    @if($categories->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted">
                            Showing {{ $categories->firstItem() }} to {{ $categories->lastItem() }} of {{ $categories->total() }} entries
                        </div>
                        <nav>
                            {{ $categories->links() }}
                        </nav>
                    </div>
                    @endif
                </div>
            </div>
        </section>
    </main>

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('video-categories.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Category Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status">
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="sort_order" class="form-label">Sort Order</label>
                                <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                       id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}">
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            tooltips.forEach(tooltip => new bootstrap.Tooltip(tooltip));
            
            // Bulk selection functionality
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.category-checkbox');
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
                    alert('Please select at least one category');
                    return;
                }
                
                if (action === 'delete') {
                    if (!confirm(`Are you sure you want to delete ${selectedIds.length} category(ies)?`)) {
                        return;
                    }
                }
                
                // Submit bulk action
                fetch('{{ route("video-categories.bulk.action") }}', {
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
                    }
                })
                .catch(error => console.error('Error:', error));
            });
            
            // Update sort order on change
            document.querySelectorAll('.sort-order-input').forEach(input => {
                input.addEventListener('change', function() {
                    const id = this.dataset.id;
                    const sortOrder = this.value;
                    
                    fetch('{{ route("video-categories.update.sort") }}', {
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
                    selectedCount.textContent = `${selected} categor${selected === 1 ? 'y' : 'ies'} selected`;
                } else {
                    bulkActionsCard.classList.add('d-none');
                    selectAll.checked = false;
                }
            }
        });
    </script>
    @endpush

    <style>
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        #categoriesTable {
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

        @media (max-width: 576px) {
            .table td:nth-child(3),
            .table th:nth-child(3) {
                display: none;
            }
        }
    </style>
</x-app-layout>