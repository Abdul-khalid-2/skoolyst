<x-app-layout>
    <main class="main-content">
        <section id="branches" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-0">Branches of {{ $school->name }}</h2>
                    <p class="text-muted">Manage school branches</p>
                </div>
                <div>
                    <a href="{{ route('schools.show', $school) }}" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left me-2"></i>Back to School
                    </a>
                    <a href="{{ route('schools.branches.create', $school) }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add Branch
                    </a>
                </div>
            </div>

            <div class="card">
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
                                    <span class="badge bg-primary ms-1">Main</span>
                                    @endif
                                </td>
                                <td>{{ $branch->address }}</td>
                                <td>{{ $branch->city }}</td>
                                <td>{{ $branch->contact_number ?? '-' }}</td>
                                <td>
                                    @if($branch->status === 'active')
                                    <span class="badge bg-success">Active</span>
                                    @else
                                    <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('schools.branches.edit', [$school, $branch]) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('schools.branches.show', [$school, $branch]) }}" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('schools.branches.destroy', [$school, $branch]) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure? This will also delete all events and reviews associated with this branch.')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No branches found</td>
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
            </div>
        </section>
    </main>
</x-app-layout>