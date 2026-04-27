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
                <x-alert variant="success" class="mb-4">{{ session('success') }}</x-alert>
            @endif

            @if(session('error'))
                <x-alert variant="danger" class="mb-4">{{ session('error') }}</x-alert>
            @endif

            <x-card class="mb-4">
                <div class="card-body">
                    <form action="{{ route('topics.index') }}" method="GET" class="row g-3 align-items-end">
                        @if (request()->filled('sort_by'))
                            <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
                        @endif
                        @if (request()->filled('sort_dir'))
                            <input type="hidden" name="sort_dir" value="{{ request('sort_dir') }}">
                        @endif
                        @if (request()->filled('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        <div class="col-md-3">
                            <label class="form-label">Subject</label>
                            <select name="subject_id" class="form-select">
                                <option value="">All Subjects</option>
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ (string) request('subject_id') === (string) $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Difficulty</label>
                            <select name="difficulty_level" class="form-select">
                                <option value="">All Levels</option>
                                <option value="beginner" {{ request('difficulty_level') === 'beginner' ? 'selected' : '' }}>Beginner</option>
                                <option value="intermediate" {{ request('difficulty_level') === 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                <option value="advanced" {{ request('difficulty_level') === 'advanced' ? 'selected' : '' }}>Advanced</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-auto d-flex flex-wrap gap-2">
                            <x-button type="submit" variant="primary">
                                <i class="fas fa-filter me-1"></i> Apply
                            </x-button>
                            <x-button href="{{ route('topics.index') }}" variant="outline-secondary">
                                Reset
                            </x-button>
                        </div>
                    </form>
                </div>
            </x-card>

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

            <div class="mb-3 border rounded bg-light px-3 py-3 d-none" id="bulkActionsCard">
                <div class="d-flex flex-column flex-md-row align-items-center gap-3">
                    <span class="me-md-3 mb-2 mb-md-0 text-center text-md-start" id="selectedCount">0 topics selected</span>
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

            <div id="topic-data-table-wrap">
                <x-data-table
                    class="mb-0"
                    bulkActions="true"
                    :headers="[
                        ['label' => '#', 'key' => 'id', 'sortable' => true],
                        ['label' => 'Topic', 'key' => 'title', 'sortable' => true],
                        ['label' => 'Subject', 'key' => 'subject', 'sortable' => true],
                        ['label' => 'Difficulty', 'key' => 'difficulty_level', 'sortable' => true],
                        ['label' => 'Time', 'key' => 'estimated_time_minutes', 'sortable' => true],
                        ['label' => 'MCQs', 'key' => 'mcqs_count', 'sortable' => true],
                        ['label' => 'Status', 'key' => 'status', 'sortable' => true],
                        ['label' => 'Sort Order', 'key' => 'sort_order', 'sortable' => true],
                        ['label' => 'Actions', 'key' => 'actions', 'sortable' => false],
                    ]"
                    :records="$topics"
                    :sortBy="request('sort_by', 'sort_order')"
                    :sortDir="request('sort_dir', 'asc')"
                    :searchValue="request('search')"
                    emptyTitle="No topics found"
                    emptyDescription="Try adjusting filters or add a new topic."
                    emptyIcon="fa-folder-open"
                >
                    <x-slot name="emptyActions">
                        <x-button href="{{ route('topics.create') }}" variant="primary">
                            <i class="fas fa-plus me-2"></i>Add Topic
                        </x-button>
                    </x-slot>
                    <x-slot name="rows">
                        @foreach ($topics as $topic)
                            <tr data-id="{{ $topic->id }}">
                                <td>
                                    <input
                                        type="checkbox"
                                        class="form-check-input data-table-row-checkbox topic-checkbox"
                                        value="{{ $topic->id }}"
                                    >
                                </td>
                                <td>{{ ($topics->currentPage() - 1) * $topics->perPage() + $loop->iteration }}</td>
                                <td>
                                    <div>
                                        <strong>{{ $topic->title }}</strong>
                                        @if ($topic->description)
                                            <small class="text-muted d-block mt-1">{{ Str::limit($topic->description, 60) }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if ($topic->subject)
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
                                    <input
                                        type="number"
                                        class="form-control form-control-sm sort-order-input"
                                        value="{{ $topic->sort_order }}"
                                        data-id="{{ $topic->id }}"
                                        style="width: 80px;"
                                    >
                                </td>
                                <td class="text-end">
                                    <x-table-action>
                                        <x-table-action-item href="{{ route('topics.edit', $topic) }}" icon="fa-edit">
                                            Edit
                                        </x-table-action-item>
                                        <x-table-action-item href="{{ route('topics.show', $topic) }}" icon="fa-eye">
                                            View
                                        </x-table-action-item>
                                        @if ($topic->mcqs_count == 0)
                                            <x-table-action-item
                                                href="{{ route('topics.destroy', $topic) }}"
                                                icon="fa-trash"
                                                variant="danger"
                                                method="DELETE"
                                                onclick="return confirm('Are you sure you want to delete this topic?')"
                                            >
                                                Delete
                                            </x-table-action-item>
                                        @else
                                            <li>
                                                <span
                                                    class="dropdown-item disabled text-muted"
                                                    data-bs-toggle="tooltip"
                                                    title="Cannot delete topic with MCQs"
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
                width: 16px;
                height: 16px;
                border-radius: 3px;
                display: inline-block;
            }
            .sort-order-input {
                min-width: 60px;
            }
            @media (max-width: 768px) {
                .btn-sm {
                    padding: 0.25rem 0.5rem;
                    font-size: 0.75rem;
                }
            }
        </style>
    @endpush

    @push('js')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
                tooltips.forEach(tooltip => new bootstrap.Tooltip(tooltip));

                const tableWrap = document.getElementById('topic-data-table-wrap');
                const selectAll = tableWrap ? tableWrap.querySelector('table thead tr th input.form-check-input') : null;
                const checkboxes = document.querySelectorAll('.topic-checkbox');
                const bulkActionsCard = document.getElementById('bulkActionsCard');
                const selectedCount = document.getElementById('selectedCount');
                const clearSelectionBtn = document.getElementById('clearSelection');

                if (selectAll) {
                    selectAll.addEventListener('change', function() {
                        checkboxes.forEach(checkbox => {
                            checkbox.checked = this.checked;
                        });
                        updateBulkActions();
                    });
                }

                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', updateBulkActions);
                });

                clearSelectionBtn.addEventListener('click', function() {
                    checkboxes.forEach(checkbox => { checkbox.checked = false; });
                    if (selectAll) selectAll.checked = false;
                    updateBulkActions();
                });

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
                    if (action === 'delete' && !confirm('Are you sure you want to delete ' + selectedIds.length + ' topic(s)?')) {
                        return;
                    }

                    fetch(@js(route('topics.bulk.action')), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ action: action, ids: selectedIds })
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

                document.querySelectorAll('.sort-order-input').forEach(input => {
                    input.addEventListener('change', function() {
                        const id = this.dataset.id;
                        const sortOrder = this.value;
                        fetch(@js(route('topics.update.sort')), {
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
                    const selected = Array.from(checkboxes).filter(cb => cb.checked).length;
                    if (selected > 0) {
                        bulkActionsCard.classList.remove('d-none');
                        selectedCount.textContent = selected + ' topic' + (selected === 1 ? '' : 's') + ' selected';
                    } else {
                        bulkActionsCard.classList.add('d-none');
                        if (selectAll) selectAll.checked = false;
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>
