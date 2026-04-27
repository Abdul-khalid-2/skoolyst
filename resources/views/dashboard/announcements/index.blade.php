<x-app-layout>
    <main class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <x-page-header class="mb-3">
                        <x-slot name="heading">
                            <h3 class="h5 mb-0">Announcements</h3>
                        </x-slot>
                        <x-slot name="actions">
                            <x-button href="{{ route('announcements.create') }}" variant="primary">
                                <i class="fas fa-plus"></i> Create Announcement
                            </x-button>
                        </x-slot>
                    </x-page-header>

                    <x-card>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Branch</th>
                                            <th>Status</th>
                                            <th>Views</th>
                                            <th>Publish Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($announcements as $announcement)
                                        <tr>
                                            <td>{{ $announcement->title }}</td>
                                            <td>{{ $announcement->branch ? $announcement->branch->name : 'All Branches' }}</td>
                                            <td>
                                                @php
                                                    $anVariant = $announcement->status === 'published' ? 'success' : ($announcement->status === 'draft' ? 'warning' : 'secondary');
                                                @endphp
                                                <x-badge :variant="$anVariant" class="text-dark">
                                                    {{ ucfirst($announcement->status) }}
                                                </x-badge>
                                            </td>
                                            <td>{{ $announcement->view_count }}</td>
                                            <td>{{ $announcement->publish_at ? $announcement->publish_at->format('M d, Y') : 'Not set' }}</td>
                                            <td>
                                                <x-button href="{{ route('announcements.show', $announcement->uuid) }}" variant="info" class="btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </x-button>
                                                <x-button href="{{ route('announcements.edit', $announcement->uuid) }}" variant="primary" class="btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </x-button>
                                                <form action="{{ route('announcements.destroy', $announcement->uuid) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <x-button type="submit" variant="danger" class="btn-sm" onclick="return confirm('Are you sure?')">
                                                        <i class="fas fa-trash"></i>
                                                    </x-button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="border-0 text-center">
                                                <x-empty-state title="No announcements found." class="py-3" />
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-center">
                                {{ $announcements->links() }}
                            </div>
                        </div>
                    </x-card>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>
