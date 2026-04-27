<x-app-layout>
    <main class="main-content">
        <section id="schools" class="page-section">
            <x-page-header class="mb-4 flex-wrap gap-2">
                <x-slot name="heading">
                    <h2 class="mb-0">Schools</h2>
                    <p class="text-muted">Manage registered schools</p>
                </x-slot>
                @role('super-admin')
                <x-slot name="actions">
                    <x-button href="{{ route('schools.create') }}" variant="primary">
                        <i class="fas fa-plus me-2"></i>Add School
                    </x-button>
                </x-slot>
                @endrole
            </x-page-header>

            <x-card>
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
                                    <x-badge variant="success">Active</x-badge>
                                    @else
                                    <x-badge variant="secondary">Inactive</x-badge>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <x-button href="{{ route('schools.edit', $school->id) }}" variant="outline-primary" class="btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </x-button>
                                        <x-button href="{{ route('schools.show', $school->id) }}" variant="outline-info" class="btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </x-button>
                                        @role('super-admin')
                                        <form action="{{ route('schools.destroy', $school->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <x-button type="submit" variant="outline-danger" class="btn-sm" onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash"></i>
                                            </x-button>
                                        </form>
                                        @endrole
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">
                                    <x-empty-state title="No schools found" icon="fa-school" class="py-4" />
                                </td>
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
            </x-card>
        </section>
    </main>
</x-app-layout>
