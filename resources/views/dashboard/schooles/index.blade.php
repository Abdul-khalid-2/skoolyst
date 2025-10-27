<x-app-layout>
    <main class="main-content">
        <section id="schools" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <div>
                    <h2 class="mb-0">Schools</h2>
                    <p class="text-muted">Manage registered schools</p>
                </div>
                @role('super-admin')
                <a href="{{ route('schools.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add School
                </a>
                @endrole
            </div>

            <div class="card">
                <div class="table-responsive" style="overflow-x:auto; -webkit-overflow-scrolling: touch;">
                    <table class="table table-hover align-middle mb-0 text-nowrap">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($schools as $school)
                            <tr>
                                <td>{{ ($schools->currentPage() - 1) * $schools->perPage() + $loop->iteration }}</td>
                                <td>{{ $school->name }}</td>
                                <td class="text-truncate" style="max-width:150px;">{{ $school->email ?? '-' }}</td>
                                <td>{{ $school->contact_number ?? '-' }}</td>
                                <td class="text-truncate" style="max-width:180px;">{{ $school->address ?? '-' }}</td>
                                <td>
                                    @if($school->status === 'active')
                                    <span class="badge bg-success">Active</span>
                                    @else
                                    <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('schools.edit', $school->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('schools.show', $school->id) }}" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @role('super-admin')
                                        <form action="{{ route('schools.destroy', $school->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endrole
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No schools found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Links -->
                @if($schools->hasPages())
                <div class="card-footer">
                    {{ $schools->links() }}
                </div>
                @endif
            </div>
        </section>
    </main>
</x-app-layout>