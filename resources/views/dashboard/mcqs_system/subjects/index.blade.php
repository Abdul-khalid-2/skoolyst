<x-app-layout>
    <main class="main-content">
        <div class="container-fluid px-0 px-md-3">
            <x-page-header class="px-3 px-md-0">
                <x-slot name="heading">
                    <h1 class="h3 mb-1 mb-md-2">Subjects</h1>
                    <p class="text-muted mb-0 d-none d-md-block">Manage subjects for different test types</p>
                    <p class="text-muted mb-0 d-block d-md-none">Manage subjects</p>
                </x-slot>
                <x-slot name="actions">
                    <x-button href="{{ route('subjects.create') }}" variant="primary" class="w-100 w-md-auto d-flex align-items-center justify-content-center">
                        <i class="fas fa-plus me-2"></i>
                        <span class="d-none d-sm-inline">Add Subject</span>
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
                        <form action="{{ route('subjects.index') }}" method="GET" class="row g-3 align-items-end">
                            @if (request()->filled('sort_by'))
                                <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
                            @endif
                            @if (request()->filled('sort_dir'))
                                <input type="hidden" name="sort_dir" value="{{ request('sort_dir') }}">
                            @endif
                            @if (request()->filled('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                            <div class="col-12 col-md-6 col-lg-4">
                                <label class="form-label small fw-bold">Test Type</label>
                                <select name="test_type_id" class="form-select form-select-sm">
                                    <option value="">All Test Types</option>
                                    @foreach ($testTypes as $type)
                                        <option value="{{ $type->id }}" {{ (string) request('test_type_id') === (string) $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-6 col-lg-3">
                                <label class="form-label small fw-bold">Status</label>
                                <select name="status" class="form-select form-select-sm">
                                    <option value="">All Status</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-auto d-flex flex-wrap gap-2">
                                <x-button type="submit" variant="primary" class="btn-sm">
                                    <i class="fas fa-filter me-1"></i> Apply
                                </x-button>
                                <x-button href="{{ route('subjects.index') }}" variant="outline-secondary" class="btn-sm">
                                    Reset
                                </x-button>
                            </div>
                        </form>
                    </div>
                </div>
            </x-card>

            <div class="row mb-4 g-3 px-3 px-md-0">
                <div class="col-6 col-md-3">
                    <x-card class="card-hover h-100">
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
                    </x-card>
                </div>
                <div class="col-6 col-md-3">
                    <x-card class="card-hover h-100">
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
                    </x-card>
                </div>
                <div class="col-6 col-md-3">
                    <x-card class="card-hover h-100">
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
                    </x-card>
                </div>
                <div class="col-6 col-md-3">
                    <x-card class="card-hover h-100">
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
                    </x-card>
                </div>
            </div>

            <div class="mb-3 mx-3 mx-md-0 border rounded bg-light px-3 py-3 d-none" id="bulkActionsCard">
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
                            <x-button type="button" variant="primary" class="btn-sm flex-grow-1" id="applyBulkAction">
                                <span class="d-none d-md-inline">Apply</span>
                                <i class="fas fa-check d-inline d-md-none"></i>
                            </x-button>
                            <x-button type="button" variant="outline-secondary" class="btn-sm" id="clearSelection">
                                <span class="d-none d-md-inline">Clear</span>
                                <i class="fas fa-times d-inline d-md-none"></i>
                            </x-button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="subject-data-table-wrap" class="mx-3 mx-md-0">
                <x-data-table
                    class="mb-0 shadow-sm"
                    bulkActions="true"
                    :headers="[
                        ['label' => '#', 'key' => 'id', 'sortable' => true],
                        ['label' => 'Subject', 'key' => 'name', 'sortable' => true],
                        ['label' => 'Test types', 'key' => 'test_types', 'sortable' => false],
                        ['label' => 'Topics', 'key' => 'topics_count', 'sortable' => true],
                        ['label' => 'MCQs', 'key' => 'mcqs_count', 'sortable' => true],
                        ['label' => 'Status', 'key' => 'status', 'sortable' => true],
                        ['label' => 'Sort', 'key' => 'sort_order', 'sortable' => true],
                        ['label' => 'Actions', 'key' => 'actions', 'sortable' => false],
                    ]"
                    :records="$subjects"
                    :sortBy="request('sort_by', 'sort_order')"
                    :sortDir="request('sort_dir', 'asc')"
                    :searchValue="request('search')"
                    emptyTitle="No subjects found"
                    emptyDescription="Try adjusting filters or create your first subject."
                    emptyIcon="fa-book"
                >
                    <x-slot name="emptyActions">
                        <x-button href="{{ route('subjects.create') }}" variant="primary">
                            <i class="fas fa-plus me-2"></i>Add Subject
                        </x-button>
                    </x-slot>
                    <x-slot name="rows">
                        @foreach ($subjects as $subject)
                            <tr data-id="{{ $subject->id }}">
                                <td>
                                    <input
                                        type="checkbox"
                                        class="form-check-input data-table-row-checkbox subject-checkbox"
                                        value="{{ $subject->id }}"
                                    >
                                </td>
                                <td>{{ ($subjects->currentPage() - 1) * $subjects->perPage() + $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="color-indicator flex-shrink-0 me-2" style="background-color: {{ $subject->color_code }};"></div>
                                        @if ($subject->icon)
                                            <i class="{{ $subject->icon }} me-2 text-primary flex-shrink-0"></i>
                                        @endif
                                        <div class="min-w-0">
                                            <strong>{{ Str::limit($subject->name, 40) }}</strong>
                                            @if ($subject->description)
                                                <small class="text-muted d-block mt-1">{{ Str::limit($subject->description, 50) }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if ($subject->testTypes->count() > 0)
                                        <div class="d-flex align-items-center flex-wrap gap-1">
                                            @foreach ($subject->testTypes->take(3) as $testType)
                                                <x-badge variant="light" class="small">
                                                    @if ($testType->icon)
                                                        <i class="{{ $testType->icon }}"></i>
                                                    @else
                                                        {{ Str::limit($testType->name, 8) }}
                                                    @endif
                                                </x-badge>
                                            @endforeach
                                            @if ($subject->testTypes->count() > 3)
                                                <div class="dropdown">
                                                    <x-button type="button" variant="outline-secondary" class="btn-sm dropdown-toggle py-0 px-1"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        +{{ $subject->testTypes->count() - 3 }}
                                                    </x-button>
                                                    <ul class="dropdown-menu">
                                                        @foreach ($subject->testTypes->skip(3) as $testType)
                                                            <li>
                                                                <span class="dropdown-item-text">
                                                                    @if ($testType->icon)
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
                                    <a href="{{ route('topics.index', ['subject_id' => $subject->id]) }}" class="text-decoration-none">
                                        <x-badge variant="info">{{ $subject->topics_count }}</x-badge>
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('mcqs.index', ['subject_id' => $subject->id]) }}" class="text-decoration-none">
                                        <x-badge variant="warning">{{ $subject->mcqs_count }}</x-badge>
                                    </a>
                                </td>
                                <td>
                                    <x-badge :variant="$subject->status === \App\Enums\ActiveStatus::Active ? 'success' : 'secondary'">
                                        {{ ucfirst($subject->status->value) }}
                                    </x-badge>
                                </td>
                                <td>
                                    <input
                                        type="number"
                                        class="form-control form-control-sm sort-order-input"
                                        value="{{ $subject->sort_order }}"
                                        data-id="{{ $subject->id }}"
                                        min="0"
                                        style="max-width: 80px;"
                                    >
                                </td>
                                <td class="text-end">
                                    <x-table-action>
                                        <x-table-action-item href="{{ route('subjects.edit', $subject) }}" icon="fa-edit">
                                            Edit
                                        </x-table-action-item>
                                        <x-table-action-item href="{{ route('subjects.show', $subject) }}" icon="fa-eye">
                                            View
                                        </x-table-action-item>
                                        @if ($subject->topics_count == 0 && $subject->mcqs_count == 0)
                                            <x-table-action-item
                                                href="{{ route('subjects.destroy', $subject) }}"
                                                icon="fa-trash"
                                                variant="danger"
                                                method="DELETE"
                                                onclick="return confirm('Delete this subject?')"
                                            >
                                                Delete
                                            </x-table-action-item>
                                        @else
                                            <li>
                                                <span
                                                    class="dropdown-item disabled text-muted"
                                                    data-bs-toggle="tooltip"
                                                    title="Cannot delete (has topics/MCQs)"
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
            .color-indicator {
                width: 20px;
                height: 20px;
                border-radius: 4px;
                display: inline-block;
                border: 1px solid #dee2e6;
            }
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
            .card-header[data-bs-toggle="collapse"] {
                cursor: pointer;
                user-select: none;
            }
            @media (max-width: 767.98px) {
                .color-indicator {
                    width: 16px;
                    height: 16px;
                }
            }
        </style>
    @endpush

    @push('js')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
                tooltips.forEach(tooltip => new bootstrap.Tooltip(tooltip));

                const tableWrap = document.getElementById('subject-data-table-wrap');
                const selectAll = tableWrap ? tableWrap.querySelector('table thead tr th input.form-check-input') : null;
                const checkboxes = document.querySelectorAll('.subject-checkbox');
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
                            alert('Please select at least one subject');
                            return;
                        }
                        if (action === 'delete' && !confirm('Are you sure you want to delete ' + selectedIds.length + ' subject(s)?')) {
                            return;
                        }
                        fetch(@js(route('subjects.bulk.action')), {
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
                        const id = this.dataset.id;
                        const sortOrder = this.value;
                        fetch(@js(route('subjects.update.sort')), {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                categories: [{ id: id, sort_order: sortOrder }]
                            })
                        });
                    });
                });

                function updateBulkActions() {
                    const n = Array.from(checkboxes).filter(cb => cb.checked).length;
                    if (n > 0 && bulkActionsCard) {
                        bulkActionsCard.classList.remove('d-none');
                        selectedCount.textContent = n + ' subject' + (n === 1 ? '' : 's') + ' selected';
                    } else if (bulkActionsCard) {
                        bulkActionsCard.classList.add('d-none');
                        if (selectAll) selectAll.checked = false;
                    }
                }

                const filterCollapse = document.getElementById('filterCollapse');
                if (filterCollapse) {
                    const header = filterCollapse.previousElementSibling;
                    const icon = header ? header.querySelector('.fa-chevron-down, .fa-chevron-up') : null;
                    filterCollapse.addEventListener('show.bs.collapse', function() {
                        if (icon) icon.className = 'fas fa-chevron-up';
                    });
                    filterCollapse.addEventListener('hide.bs.collapse', function() {
                        if (icon) icon.className = 'fas fa-chevron-down';
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
