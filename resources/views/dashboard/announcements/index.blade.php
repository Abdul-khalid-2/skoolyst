<x-app-layout>
    <main class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Announcements</h3>
                            <div class="card-tools">
                                <a href="{{ route('announcements.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Create Announcement
                                </a>
                            </div>
                        </div>
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
                                                <span class="badge badge-{{ $announcement->status === 'published' ? 'success' : ($announcement->status === 'draft' ? 'warning' : 'secondary') }}" style="color: black">
                                                    {{ ucfirst($announcement->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $announcement->view_count }}</td>
                                            <td>{{ $announcement->publish_at ? $announcement->publish_at->format('M d, Y') : 'Not set' }}</td>
                                            <td>
                                                <a href="{{ route('announcements.show', $announcement->uuid) }}" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('announcements.edit', $announcement->uuid) }}" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('announcements.destroy', $announcement->uuid) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No announcements found.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-center">
                                {{ $announcements->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>