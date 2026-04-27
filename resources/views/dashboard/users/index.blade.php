<x-app-layout>
    <main class="main-content">
        <section id="users" class="page-section">
            <x-page-header class="mb-4 flex-wrap gap-2">
                <x-slot name="heading">
                    <h2 class="mb-0">Users</h2>
                    <p class="text-muted">Manage system users</p>
                </x-slot>
                @can('create users')
                    <x-slot name="actions">
                        <x-button href="{{ route('users.create') }}" variant="primary">
                            <i class="fas fa-plus me-2"></i>Add User
                        </x-button>
                    </x-slot>
                @endcan
            </x-page-header>

            @if (session('success'))
                <x-alert variant="success" class="mb-4">
                    {{ session('success') }}
                </x-alert>
            @endif

            @if (session('error'))
                <x-alert variant="error" class="mb-4">
                    {{ session('error') }}
                </x-alert>
            @endif

            <!-- Filters -->
            <x-card class="mb-4">
                <div class="card-body">
                    <form action="{{ route('users.index') }}" method="GET" class="row g-3">
                        @if (request()->filled('sort_by'))
                            <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
                        @endif
                        @if (request()->filled('sort_dir'))
                            <input type="hidden" name="sort_dir" value="{{ request('sort_dir') }}">
                        @endif
                        @if (request()->filled('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        <div class="col-md-4">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role" name="role">
                                <option value="">All Roles</option>
                                @foreach ($roles ?? [] as $role)
                                    <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @role('super-admin')
                            <div class="col-md-4">
                                <label for="school_id" class="form-label">School</label>
                                <select class="form-select" id="school_id" name="school_id">
                                    <option value="">All Schools</option>
                                    @foreach ($schools ?? [] as $school)
                                        <option value="{{ $school->id }}" {{ (string) request('school_id') === (string) $school->id ? 'selected' : '' }}>
                                            {{ $school->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endrole
                        <div class="col-md-4 d-flex align-items-end gap-2">
                            <x-button type="submit" variant="primary" class="flex-grow-1">
                                <i class="fas fa-filter me-2"></i>Filter
                            </x-button>
                            <x-button href="{{ route('users.index') }}" variant="outline-secondary" type="button">
                                <i class="fas fa-redo"></i>
                            </x-button>
                        </div>
                    </form>
                </div>
            </x-card>

            <x-data-table
                class="mb-0 shadow-sm"
                :headers="[
                    ['label' => '#', 'key' => 'id', 'sortable' => true],
                    ['label' => 'User', 'key' => 'name', 'sortable' => true],
                    ['label' => 'Email', 'key' => 'email', 'sortable' => true],
                    ['label' => 'Phone', 'key' => 'phone', 'sortable' => true],
                    ['label' => 'School', 'key' => 'school', 'sortable' => true],
                    ['label' => 'Role', 'key' => 'roles', 'sortable' => false],
                    ['label' => 'Status', 'key' => 'email_verified_at', 'sortable' => true],
                    ['label' => 'Joined', 'key' => 'created_at', 'sortable' => true],
                    ['label' => 'Actions', 'key' => 'actions', 'sortable' => false],
                ]"
                :records="$users"
                :sortBy="request('sort_by')"
                :sortDir="request('sort_dir', 'desc')"
                :searchValue="request('search')"
                emptyTitle="No users found"
                emptyDescription="Get started by creating a new user."
                emptyIcon="fa-users"
            >
                @can('create users')
                    <x-slot name="emptyActions">
                        <x-button href="{{ route('users.create') }}" variant="primary">
                            <i class="fas fa-plus me-2"></i>Add User
                        </x-button>
                    </x-slot>
                @endcan
                <x-slot name="rows">
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-2">
                                        @if ($user->profile_picture)
                                            <img
                                                src="{{ Storage::url($user->profile_picture) }}"
                                                alt="{{ $user->name }}"
                                                class="rounded-circle"
                                                width="40"
                                                height="40"
                                                style="object-fit: cover;"
                                            >
                                        @else
                                            <div
                                                class="avatar-initials rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                                style="width: 40px; height: 40px; font-size: 16px;"
                                            >
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <strong>{{ $user->name }}</strong>
                                        <small class="d-block text-muted">ID: {{ $user->uuid }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                            </td>
                            <td>{{ $user->phone ?? '-' }}</td>
                            <td>
                                @if ($user->school)
                                    <x-badge variant="info">{{ $user->school->name }}</x-badge>
                                @else
                                    <x-badge variant="secondary">No School</x-badge>
                                @endif
                            </td>
                            <td>
                                @foreach ($user->roles as $role)
                                    <x-badge variant="primary">{{ ucfirst($role->name) }}</x-badge>
                                @endforeach
                            </td>
                            <td>
                                @if ($user->email_verified_at)
                                    <x-badge variant="success">Verified</x-badge>
                                @else
                                    <x-badge variant="warning">Unverified</x-badge>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="text-end">
                                <x-table-action>
                                    <x-table-action-item href="{{ route('users.show', $user->id) }}" icon="fa-eye">
                                        View
                                    </x-table-action-item>
                                    <x-table-action-item href="{{ route('users.edit', $user->id) }}" icon="fa-edit">
                                        Edit
                                    </x-table-action-item>
                                    @can('delete users')
                                        @if (auth()->id() !== $user->id)
                                            <li>
                                                <button
                                                    type="button"
                                                    class="dropdown-item text-danger d-flex align-items-center w-100 border-0 bg-transparent text-start"
                                                    onclick="confirmDelete({{ $user->id }}, @js($user->name))"
                                                >
                                                    <i class="fas fa-trash me-2" aria-hidden="true"></i>
                                                    Delete
                                                </button>
                                            </li>
                                        @endif
                                    @endcan
                                </x-table-action>
                            </td>
                        </tr>
                    @endforeach
                </x-slot>
            </x-data-table>
        </section>
    </main>

    <x-bs-modal id="deleteModal" title="Confirm Delete">
        <p>Are you sure you want to delete <strong id="deleteUserName"></strong>?</p>
        <p class="text-danger">This action cannot be undone.</p>
        <x-slot name="footer">
            <x-button type="button" variant="secondary" data-bs-dismiss="modal">Cancel</x-button>
            <form id="deleteForm" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <x-button type="submit" variant="danger">Delete User</x-button>
            </form>
        </x-slot>
    </x-bs-modal>

    @push('css')
        <style>
            .avatar-circle {
                width: 40px;
                height: 40px;
                border-radius: 50%;
            }
        </style>
    @endpush

    @push('js')
        <script>
            function confirmDelete(userId, userName) {
                document.getElementById('deleteUserName').textContent = userName;
                document.getElementById('deleteForm').action = '{{ url('users') }}/' + userId;
                new bootstrap.Modal(document.getElementById('deleteModal')).show();
            }
        </script>
    @endpush
</x-app-layout>
