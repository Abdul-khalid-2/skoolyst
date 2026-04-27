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

            <!-- Filters -->
            <x-card class="mb-4">
                <div class="card-body">
                    <form action="{{ route('users.index') }}" method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label for="search" class="form-label">Search</label>
                            <input type="text"
                                   class="form-control"
                                   id="search"
                                   name="search"
                                   placeholder="Name or email..."
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role" name="role">
                                <option value="">All Roles</option>
                                @foreach($roles ?? [] as $role)
                                <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @role('super-admin')
                        <div class="col-md-3">
                            <label for="school_id" class="form-label">School</label>
                            <select class="form-select" id="school_id" name="school_id">
                                <option value="">All Schools</option>
                                @foreach($schools ?? [] as $school)
                                <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                                    {{ $school->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @endrole
                        <div class="col-md-2 d-flex align-items-end">
                            <x-button type="submit" variant="primary" class="w-100">
                                <i class="fas fa-filter me-2"></i>Filter
                            </x-button>
                        </div>
                    </form>
                </div>
            </x-card>

            <!-- Users Table -->
            <x-card>
                <div class="table-responsive" style="overflow-x:auto; -webkit-overflow-scrolling: touch;">
                    <table class="table table-hover align-middle mb-0 text-nowrap">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>School</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td>{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-2">
                                            @if($user->profile_picture)
                                                <img src="{{ Storage::url($user->profile_picture) }}"
                                                     alt="{{ $user->name }}"
                                                     class="rounded-circle"
                                                     width="40"
                                                     height="40"
                                                     style="object-fit: cover;">
                                            @else
                                                <div class="avatar-initials rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                                     style="width: 40px; height: 40px; font-size: 16px;">
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
                                    @if($user->school)
                                        <x-badge variant="info">{{ $user->school->name }}</x-badge>
                                    @else
                                        <x-badge variant="secondary">No School</x-badge>
                                    @endif
                                </td>
                                <td>
                                    @foreach($user->roles as $role)
                                        <x-badge variant="primary">{{ ucfirst($role->name) }}</x-badge>
                                    @endforeach
                                </td>
                                <td>
                                    @if($user->email_verified_at)
                                        <x-badge variant="success">Verified</x-badge>
                                    @else
                                        <x-badge variant="warning">Unverified</x-badge>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <x-button href="{{ route('users.show', $user->id) }}"
                                           variant="outline-info" class="btn-sm"
                                           title="View">
                                            <i class="fas fa-eye"></i>
                                        </x-button>
                                        <x-button href="{{ route('users.edit', $user->id) }}"
                                           variant="outline-primary" class="btn-sm"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </x-button>
                                        @can('delete users')
                                            @if(auth()->id() !== $user->id)
                                            <x-button type="button"
                                                    variant="outline-danger" class="btn-sm"
                                                    title="Delete"
                                                    onclick="confirmDelete({{ $user->id }}, '{{ $user->name }}')">
                                                <i class="fas fa-trash"></i>
                                            </x-button>
                                            @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="border-0">
                                    <x-empty-state title="No users found" description="Get started by creating a new user." icon="fa-users" class="py-4">
                                        @can('create users')
                                        <x-slot name="actions">
                                            <x-button href="{{ route('users.create') }}" variant="primary">
                                                <i class="fas fa-plus me-2"></i>Add User
                                            </x-button>
                                        </x-slot>
                                        @endcan
                                    </x-empty-state>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($users->hasPages())
                <div class="card-footer">
                    {{ $users->appends(request()->query())->links() }}
                </div>
                @endif
            </x-card>
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

    @push('styles')
    <style>
        .avatar-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        function confirmDelete(userId, userName) {
            document.getElementById('deleteUserName').textContent = userName;
            document.getElementById('deleteForm').action = '{{ url("users") }}/' + userId;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }
    </script>
    @endpush
</x-app-layout>
