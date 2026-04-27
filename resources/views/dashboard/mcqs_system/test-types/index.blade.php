<x-app-layout>
    <main class="main-content">
        <div class="container-fluid px-0 px-md-3">
            <x-page-header>
                <x-slot name="heading">
                    <h1 class="h3 mb-1 mb-md-2">Test Types</h1>
                    <p class="text-muted mb-0 d-none d-md-block">Manage different types of tests (Entry Test, Job Test, University Test, etc.)</p>
                    <p class="text-muted mb-0 d-block d-md-none">Manage test types</p>
                </x-slot>
                <x-slot name="actions">
                    <x-button
                        href="{{ route('test-types.create') }}"
                        variant="primary"
                        class="w-100 w-md-auto d-flex align-items-center justify-content-center"
                    >
                        <i class="fas fa-plus me-2"></i>
                        <span class="d-none d-sm-inline">Add Test Type</span>
                        <span class="d-inline d-sm-none">Add</span>
                    </x-button>
                </x-slot>
            </x-page-header>

            @if (session('success'))
                <x-alert variant="success" class="mb-4 mx-3 mx-md-0">{{ session('success') }}</x-alert>
            @endif

            @if (session('error'))
                <x-alert variant="danger" class="mb-4 mx-3 mx-md-0">{{ session('error') }}</x-alert>
            @endif

            <x-card class="mb-4 mx-3 mx-md-0">
                <div class="card-body py-3">
                    <form action="{{ route('test-types.index') }}" method="GET" class="row g-3 align-items-end">
                        @if (request()->filled('sort_by'))
                            <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
                        @endif
                        @if (request()->filled('sort_dir'))
                            <input type="hidden" name="sort_dir" value="{{ request('sort_dir') }}">
                        @endif
                        @if (request()->filled('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        <div class="col-12 col-md-4 col-lg-3">
                            <label class="form-label small fw-bold mb-1">Status</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="">All status</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-auto d-flex flex-wrap gap-2">
                            <x-button type="submit" variant="primary" class="btn-sm">
                                <i class="fas fa-filter me-1"></i> Apply
                            </x-button>
                            <x-button href="{{ route('test-types.index') }}" variant="outline-secondary" class="btn-sm">
                                Reset
                            </x-button>
                        </div>
                    </form>
                </div>
            </x-card>

            <div class="row mb-4 g-3 px-3 px-md-0">
                <div class="col-6 col-md-3">
                    <x-card class="card-hover h-100">
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
                    </x-card>
                </div>
                <div class="col-6 col-md-3">
                    <x-card class="card-hover h-100">
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
                    </x-card>
                </div>
                <div class="col-6 col-md-3">
                    <x-card class="card-hover h-100">
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
                    </x-card>
                </div>
                <div class="col-6 col-md-3">
                    <x-card class="card-hover h-100">
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
                    </x-card>
                </div>
            </div>

            <div class="mb-3 mx-3 mx-md-0 border rounded bg-light px-3 py-3 d-none" id="bulkActionsCard">
                <div class="d-flex flex-column flex-md-row align-items-center gap-3">
                    <span class="me-md-3 mb-2 mb-md-0 text-center text-md-start" id="selectedCount">0 test types selected</span>
                    <div class="d-flex flex-column flex-md-row align-items-center gap-2 w-100 w-md-auto">
                        <select class="form-select form-select-sm me-md-2 flex-grow-1" id="bulkActionSelect">
                            <option value="">Bulk Actions</option>
                            <option value="activate">Activate</option>
                            <option value="deactivate">Deactivate</option>
                            <option value="delete">Delete</option>
                        </select>
                        <div class="d-flex gap-2 mt-2 mt-md-0">
                            <x-button variant="primary" type="button" class="btn-sm flex-grow-1" id="applyBulkAction">
                                <span class="d-none d-md-inline">Apply</span>
                                <i class="fas fa-check d-inline d-md-none"></i>
                            </x-button>
                            <x-button variant="outline-secondary" type="button" class="btn-sm" id="clearSelection">
                                <span class="d-none d-md-inline">Clear</span>
                                <i class="fas fa-times d-inline d-md-none"></i>
                            </x-button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="test-type-data-table-wrap" class="mx-3 mx-md-0">
                <x-data-table
                    class="mb-0 shadow-sm"
                    bulkActions="true"
                    :headers="[
                        ['label' => '#', 'key' => 'id', 'sortable' => true],
                        ['label' => 'Test type', 'key' => 'name', 'sortable' => true],
                        ['label' => 'Icon', 'key' => 'icon', 'sortable' => false],
                        ['label' => 'Description', 'key' => 'description', 'sortable' => true],
                        ['label' => 'Subjects', 'key' => 'subjects_count', 'sortable' => true],
                        ['label' => 'Status', 'key' => 'status', 'sortable' => true],
                        ['label' => 'Sort', 'key' => 'sort_order', 'sortable' => true],
                        ['label' => 'Actions', 'key' => 'actions', 'sortable' => false],
                    ]"
                    :records="$testTypes"
                    :sortBy="request('sort_by', 'sort_order')"
                    :sortDir="request('sort_dir', 'asc')"
                    :searchValue="request('search')"
                    emptyTitle="No test types found"
                    emptyDescription="Try adjusting filters or create your first test type."
                    emptyIcon="fa-list"
                >
                    <x-slot name="emptyActions">
                        <x-button href="{{ route('test-types.create') }}" variant="primary">
                            <i class="fas fa-plus me-2"></i>Add Test Type
                        </x-button>
                    </x-slot>
                    <x-slot name="rows">
                        @foreach ($testTypes as $testType)
                            <tr data-id="{{ $testType->id }}">
                                <td>
                                    <input
                                        type="checkbox"
                                        class="form-check-input data-table-row-checkbox test-type-checkbox"
                                        value="{{ $testType->id }}"
                                    >
                                </td>
                                <td>{{ ($testTypes->currentPage() - 1) * $testTypes->perPage() + $loop->iteration }}</td>
                                <td>
                                    <strong>{{ Str::limit($testType->name, 40) }}</strong>
                                    <small class="text-muted d-block mt-1"><code class="small">{{ Str::limit($testType->slug, 24) }}</code></small>
                                </td>
                                <td>
                                    @if ($testType->icon)
                                        <i class="{{ $testType->icon }} text-primary" title="{{ $testType->icon }}"></i>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($testType->description)
                                        <span data-bs-toggle="tooltip" title="{{ $testType->description }}">
                                            {{ Str::limit($testType->description, 40) }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('subjects.index', ['test_type_id' => $testType->id]) }}" class="text-decoration-none">
                                        <x-badge variant="primary">{{ $testType->subjects_count }}</x-badge>
                                    </a>
                                </td>
                                <td>
                                    <x-badge :variant="$testType->status === \App\Enums\ActiveStatus::Active ? 'success' : 'secondary'">
                                        {{ ucfirst($testType->status->value) }}
                                    </x-badge>
                                </td>
                                <td>
                                    <input
                                        type="number"
                                        class="form-control form-control-sm sort-order-input"
                                        value="{{ $testType->sort_order }}"
                                        data-id="{{ $testType->id }}"
                                        min="0"
                                        style="max-width: 80px;"
                                    >
                                </td>
                                <td class="text-end">
                                    <x-table-action>
                                        <x-table-action-item href="{{ route('test-types.edit', $testType) }}" icon="fa-edit">
                                            Edit
                                        </x-table-action-item>
                                        <x-table-action-item href="{{ route('test-types.show', $testType) }}" icon="fa-eye">
                                            View
                                        </x-table-action-item>
                                        @if ($testType->subjects_count === 0)
                                            <x-table-action-item
                                                href="{{ route('test-types.destroy', $testType) }}"
                                                icon="fa-trash"
                                                variant="danger"
                                                method="DELETE"
                                                onclick="return confirm('Delete this test type?')"
                                            >
                                                Delete
                                            </x-table-action-item>
                                        @else
                                            <li>
                                                <span
                                                    class="dropdown-item disabled text-muted"
                                                    data-bs-toggle="tooltip"
                                                    title="Cannot delete (has subjects)"
                                                >
                                                    <i class="fas fa-trash me-2" aria-hidden="true"></i>Delete
                                                </span>
                                            </li>
                                        @endif
                                    </x-table-action>
                                </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-data-table>
            </div>
        </div>
    </main>

    @push('css')
        <style>
            .card-hover:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
            }
            .sort-order-input:focus {
                border-color: var(--bs-primary);
                box-shadow: 0 0 0 0.2rem rgba(var(--bs-primary-rgb), 0.25);
            }
        </style>
    @endpush

    @push('js')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

                const tableWrap = document.getElementById('test-type-data-table-wrap');
                const selectAll = tableWrap ? tableWrap.querySelector('table thead tr th input.form-check-input') : null;
                const checkboxes = document.querySelectorAll('.test-type-checkbox');
                const bulkActionsCard = document.getElementById('bulkActionsCard');
                const selectedCount = document.getElementById('selectedCount');
                const clearSelectionBtn = document.getElementById('clearSelection');

                if (selectAll) {
                    selectAll.addEventListener('change', function() {
                        checkboxes.forEach(cb => { cb.checked = this.checked; });
                        updateBulkActions();
                    });
                }

                checkboxes.forEach(cb => cb.addEventListener('change', updateBulkActions));

                if (clearSelectionBtn) {
                    clearSelectionBtn.addEventListener('click', function() {
                        checkboxes.forEach(cb => { cb.checked = false; });
                        if (selectAll) selectAll.checked = false;
                        updateBulkActions();
                    });
                }

                const applyBtn = document.getElementById('applyBulkAction');
                if (applyBtn) {
                    applyBtn.addEventListener('click', function() {
                        const action = document.getElementById('bulkActionSelect').value;
                        const selectedIds = Array.from(checkboxes).filter(cb => cb.checked).map(cb => cb.value);
                        if (!action) {
                            alert('Please select an action');
                            return;
                        }
                        if (selectedIds.length === 0) {
                            alert('Please select at least one test type');
                            return;
                        }
                        if (action === 'delete' && !confirm('Are you sure you want to delete ' + selectedIds.length + ' test type(s)?')) {
                            return;
                        }
                        fetch(@js(route('test-types.bulk.action')), {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ action: action, ids: selectedIds })
                        })
                            .then(r => r.json())
                            .then(data => {
                                if (data.success) {
                                    window.location.reload();
                                } else {
                                    alert(data.message || 'An error occurred');
                                }
                            })
                            .catch(err => console.error('Error:', err));
                    });
                }

                document.querySelectorAll('.sort-order-input').forEach(input => {
                    input.addEventListener('change', function() {
                        fetch(@js(route('test-types.update.sort')), {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                categories: [{ id: this.dataset.id, sort_order: this.value }]
                            })
                        });
                    });
                });

                function updateBulkActions() {
                    const n = Array.from(checkboxes).filter(cb => cb.checked).length;
                    if (n > 0 && bulkActionsCard) {
                        bulkActionsCard.classList.remove('d-none');
                        selectedCount.textContent = n + ' test type' + (n === 1 ? '' : 's') + ' selected';
                    } else if (bulkActionsCard) {
                        bulkActionsCard.classList.add('d-none');
                        if (selectAll) selectAll.checked = false;
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>
