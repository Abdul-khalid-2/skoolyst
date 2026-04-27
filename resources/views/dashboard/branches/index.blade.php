<x-app-layout>
    <main class="main-content">
        <section id="branches" class="page-section">
            <x-page-header class="mb-4">
                <x-slot name="heading">
                    <h2 class="mb-0">Branches of {{ $school->name }}</h2>
                    <p class="text-muted">Manage school branches</p>
                </x-slot>
                <x-slot name="actions">
                    <x-button href="{{ route('schools.show', $school) }}" variant="secondary" class="me-2">
                        <i class="fas fa-arrow-left me-2"></i>Back to School
                    </x-button>
                    <x-button href="{{ route('schools.branches.create', $school) }}" variant="primary">
                        <i class="fas fa-plus me-2"></i>Add Branch
                    </x-button>
                </x-slot>
            </x-page-header>

            <x-card>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>City</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($branches as $branch)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    {{ $branch->name }}
                                    @if($branch->is_main_branch)
                                    <x-badge variant="primary" class="ms-1">Main</x-badge>
                                    @endif
                                </td>
                                <td>{{ $branch->address }}</td>
                                <td>{{ $branch->city }}</td>
                                <td>{{ $branch->contact_number ?? '-' }}</td>
                                <td>
                                    @if($branch->status === 'active')
                                    <x-badge variant="success">Active</x-badge>
                                    @else
                                    <x-badge variant="secondary">Inactive</x-badge>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <x-button href="{{ route('schools.branches.edit', [$school, $branch]) }}" variant="outline-primary" class="btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </x-button>
                                        <x-button href="{{ route('schools.branches.show', [$school, $branch]) }}" variant="outline-info" class="btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </x-button>
                                        <form action="{{ route('schools.branches.destroy', [$school, $branch]) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <x-button type="submit" variant="outline-danger" class="btn-sm" onclick="return confirm('Are you sure? This will also delete all events and reviews associated with this branch.')">
                                                <i class="fas fa-trash"></i>
                                            </x-button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">
                                    <x-empty-state title="No branches found" class="py-3" />
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($branches->hasPages())
                <div class="card-footer">
                    {{ $branches->links() }}
                </div>
                @endif
            </x-card>
        </section>
    </main>
</x-app-layout>
