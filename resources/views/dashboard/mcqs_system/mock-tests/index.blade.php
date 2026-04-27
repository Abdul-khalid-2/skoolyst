<x-app-layout>
    <main class="main-content">
        <div class="container-fluid px-0 px-md-3">
            <x-page-header>
                <x-slot name="heading">
                    <h1 class="h3 mb-1 mb-md-2">Mock Tests</h1>
                    <p class="text-muted mb-0 d-none d-md-block">Create and manage mock tests for practice and exams</p>
                    <p class="text-muted mb-0 d-block d-md-none">Manage mock tests</p>
                </x-slot>
                <x-slot name="actions">
                    <x-button
                        href="{{ route('mock-tests.create') }}"
                        variant="primary"
                        class="w-100 w-md-auto d-flex align-items-center justify-content-center"
                    >
                        <i class="fas fa-plus me-2"></i>
                        <span class="d-none d-sm-inline">Create Test</span>
                        <span class="d-inline d-sm-none">Create</span>
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

                <div class="collapse d-md-block" id="filterCollapse" style="visibility: visible;">
                    <div class="card-body">
                        <form action="{{ route('mock-tests.index') }}" method="GET" class="row g-3 align-items-end">
                            @if (request()->filled('sort_by'))
                                <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
                            @endif
                            @if (request()->filled('sort_dir'))
                                <input type="hidden" name="sort_dir" value="{{ request('sort_dir') }}">
                            @endif
                            @if (request()->filled('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                            <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                                <label class="form-label small fw-bold">Test Type</label>
                                <select name="test_type_id" class="form-select form-select-sm">
                                    <option value="">All Types</option>
                                    @foreach ($testTypes as $type)
                                        <option value="{{ $type->id }}" {{ (string) request('test_type_id') === (string) $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 col-xl-2">
                                <label class="form-label small fw-bold">Status</label>
                                <select name="status" class="form-select form-select-sm">
                                    <option value="">All Status</option>
                                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 col-xl-2">
                                <label class="form-label small fw-bold">Access</label>
                                <select name="is_free" class="form-select form-select-sm">
                                    <option value="">All</option>
                                    <option value="1" {{ request('is_free') === '1' ? 'selected' : '' }}>Free</option>
                                    <option value="0" {{ request('is_free') === '0' ? 'selected' : '' }}>Premium</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-auto d-flex flex-wrap gap-2">
                                <x-button type="submit" variant="primary" class="btn-sm">
                                    <i class="fas fa-filter me-1"></i> Apply
                                </x-button>
                                <x-button href="{{ route('mock-tests.index') }}" variant="outline-secondary" class="btn-sm">
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
                                    <h6 class="text-muted mb-1 small">Total Tests</h6>
                                    <h4 class="mb-0">{{ $mockTests->total() }}</h4>
                                </div>
                                <div class="bg-primary bg-opacity-10 p-2 rounded">
                                    <i class="fas fa-file-alt text-primary"></i>
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
                                    <h6 class="text-muted mb-1 small">Published</h6>
                                    <h4 class="mb-0">{{ \App\Models\MockTest::where('status', 'published')->count() }}</h4>
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
                                    <h6 class="text-muted mb-1 small">Free Tests</h6>
                                    <h4 class="mb-0">{{ \App\Models\MockTest::where('is_free', true)->count() }}</h4>
                                </div>
                                <div class="bg-info bg-opacity-10 p-2 rounded">
                                    <i class="fas fa-unlock text-info"></i>
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
                                    <h6 class="text-muted mb-1 small">Total Attempts</h6>
                                    <h4 class="mb-0">{{ \App\Models\UserTestAttempt::count() }}</h4>
                                </div>
                                <div class="bg-warning bg-opacity-10 p-2 rounded">
                                    <i class="fas fa-users text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </x-card>
                </div>
            </div>

            <div class="mb-3 mx-3 mx-md-0 border rounded bg-light px-3 py-3 d-none" id="bulkActionsCard">
                <div class="d-flex flex-column flex-md-row align-items-center gap-3">
                    <span class="me-md-3 mb-2 mb-md-0 text-center text-md-start" id="selectedCount">0 mock tests selected</span>
                    <div class="d-flex flex-column flex-md-row align-items-center gap-2 w-100 w-md-auto">
                        <select class="form-select form-select-sm me-md-2 flex-grow-1" id="bulkActionSelect">
                            <option value="">Bulk Actions</option>
                            <option value="publish">Publish</option>
                            <option value="draft">Move to Draft</option>
                            <option value="archive">Archive</option>
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

            <div id="mock-test-data-table-wrap" class="mx-3 mx-md-0">
                <x-data-table
                    class="mb-0 shadow-sm"
                    bulkActions="true"
                    :headers="[
                        ['label' => '#', 'key' => 'id', 'sortable' => true],
                        ['label' => 'Test', 'key' => 'title', 'sortable' => true],
                        ['label' => 'Test type', 'key' => 'test_type', 'sortable' => true],
                        ['label' => 'Mode', 'key' => 'test_mode', 'sortable' => true],
                        ['label' => 'Questions', 'key' => 'total_questions', 'sortable' => true],
                        ['label' => 'Marks', 'key' => 'total_marks', 'sortable' => true],
                        ['label' => 'Time', 'key' => 'total_time_minutes', 'sortable' => true],
                        ['label' => 'Access', 'key' => 'is_free', 'sortable' => true],
                        ['label' => 'Attempts', 'key' => 'attempts_count', 'sortable' => true],
                        ['label' => 'Status', 'key' => 'status', 'sortable' => true],
                        ['label' => 'Actions', 'key' => 'actions', 'sortable' => false],
                    ]"
                    :records="$mockTests"
                    :sortBy="request('sort_by', 'created_at')"
                    :sortDir="request('sort_dir', 'desc')"
                    :searchValue="request('search')"
                    emptyTitle="No mock tests found"
                    emptyDescription="Try adjusting filters or create your first mock test."
                    emptyIcon="fa-file-alt"
                >
                    <x-slot name="emptyActions">
                        <x-button href="{{ route('mock-tests.create') }}" variant="primary">
                            <i class="fas fa-plus me-2"></i>Create Test
                        </x-button>
                    </x-slot>
                    <x-slot name="rows">
                        @foreach ($mockTests as $test)
                            <tr
                                data-id="{{ $test->id }}"
                                @class([
                                    'table-warning' => $test->status === \App\Enums\ContentStatus::Draft,
                                    'table-secondary' => $test->status === \App\Enums\ContentStatus::Archived,
                                ])
                            >
                                <td>
                                    <input
                                        type="checkbox"
                                        class="form-check-input data-table-row-checkbox test-checkbox"
                                        value="{{ $test->id }}"
                                    >
                                </td>
                                <td>{{ ($mockTests->currentPage() - 1) * $mockTests->perPage() + $loop->iteration }}</td>
                                <td>
                                    <strong class="d-block">{{ Str::limit($test->title, 36) }}</strong>
                                    @if ($test->shuffle_questions)
                                        <x-badge variant="info" class="small mt-1">Shuffle</x-badge>
                                    @endif
                                </td>
                                <td>
                                    @if ($test->testType)
                                        <span class="small">
                                            <i class="{{ $test->testType->icon ?? 'fas fa-tag' }} me-1"></i>{{ Str::limit($test->testType->name, 20) }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <x-badge :variant="$test->test_mode === \App\Enums\MockTestMode::Exam ? 'danger' : ($test->test_mode === \App\Enums\MockTestMode::Timed ? 'warning' : 'primary')">
                                        {{ ucfirst($test->test_mode->value) }}
                                    </x-badge>
                                </td>
                                <td>{{ $test->total_questions }}</td>
                                <td>{{ $test->total_marks }}</td>
                                <td>
                                    <div class="small">
                                        <i class="fas fa-clock me-1"></i>{{ $test->total_time_minutes }}m
                                    </div>
                                    <div class="small text-muted">Pass {{ $test->passing_marks }}</div>
                                </td>
                                <td>
                                    @if ($test->is_free)
                                        <x-badge variant="success" class="small">
                                            <i class="fas fa-unlock me-1"></i>Free
                                        </x-badge>
                                    @else
                                        <x-badge variant="warning" class="small">
                                            <i class="fas fa-lock me-1"></i>Premium
                                        </x-badge>
                                        <div class="small text-muted">{{ config('app.currency') }}{{ number_format($test->price, 0) }}</div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold">{{ $test->attempts_count }}</span>
                                </td>
                                <td>
                                    <x-badge :variant="$test->status === \App\Enums\ContentStatus::Published ? 'success' : ($test->status === \App\Enums\ContentStatus::Draft ? 'warning' : 'secondary')">
                                        {{ ucfirst($test->status->value) }}
                                    </x-badge>
                                </td>
                                <td class="text-end">
                                    <x-table-action>
                                        <x-table-action-item href="{{ route('mock-tests.show', $test) }}" icon="fa-eye">
                                            View
                                        </x-table-action-item>
                                        <x-table-action-item href="{{ route('mock-tests.edit', $test) }}" icon="fa-edit">
                                            Edit
                                        </x-table-action-item>
                                        <x-table-action-item href="{{ route('mock-tests.add-questions', $test) }}" icon="fa-question">
                                            Add questions
                                        </x-table-action-item>
                                        @if ($test->attempts_count === 0)
                                            <x-table-action-item
                                                href="{{ route('mock-tests.destroy', $test) }}"
                                                icon="fa-trash"
                                                variant="danger"
                                                method="DELETE"
                                                onclick="return confirm('Delete this mock test?')"
                                            >
                                                Delete
                                            </x-table-action-item>
                                        @else
                                            <li>
                                                <span
                                                    class="dropdown-item disabled text-muted"
                                                    data-bs-toggle="tooltip"
                                                    title="Cannot delete (has attempts)"
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
            .card-header[data-bs-toggle="collapse"] {
                cursor: pointer;
                user-select: none;
            }
            #mock-test-data-table-wrap tr.table-warning {
                background-color: rgba(255, 243, 205, 0.35) !important;
            }
            #mock-test-data-table-wrap tr.table-secondary {
                background-color: rgba(108, 117, 125, 0.12) !important;
            }
        </style>
    @endpush

    @push('js')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

                const tableWrap = document.getElementById('mock-test-data-table-wrap');
                const selectAll = tableWrap ? tableWrap.querySelector('table thead tr th input.form-check-input') : null;
                const checkboxes = document.querySelectorAll('.test-checkbox');
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
                            alert('Please select at least one test');
                            return;
                        }
                        if (action === 'delete' && !confirm('Are you sure you want to delete ' + selectedIds.length + ' test(s)?')) {
                            return;
                        }
                        fetch(@js(route('mock-tests.bulk.action')), {
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

                function updateBulkActions() {
                    const n = Array.from(checkboxes).filter(cb => cb.checked).length;
                    if (n > 0 && bulkActionsCard) {
                        bulkActionsCard.classList.remove('d-none');
                        selectedCount.textContent = n + ' mock test' + (n === 1 ? '' : 's') + ' selected';
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
