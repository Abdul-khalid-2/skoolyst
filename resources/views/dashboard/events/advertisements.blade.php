<x-app-layout>
    <main class="main-content">
        <section id="advertisements" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-0">Advertisement Pages</h2>
                    <p class="text-muted">Manage dynamic advertisement pages</p>
                </div>
                <a href="{{ route('pages.create', [$school_id, $id]) }}" class="btn btn-primary me-2">
                    <i class="fas fa-edit me-2"></i> Create Banner
                </a>
            </div>

            <div class="card">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Page Name</th>
                                <th>School</th>
                                <th>Linked Event</th>
                                <th>Slug</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pages as $page)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $page->name }}</td>
                                <td>
                                    @if($page->school)
                                    {{ $page->school->name }}
                                    @else
                                    <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($page->event)
                                    {{ $page->event->event_name }}
                                    @else
                                    <span class="text-muted">No Event</span>
                                    @endif
                                </td>
                                <td>
                                    <code>{{ $page->slug }}</code>
                                </td>
                                <td>{{ $page->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('pages.show', [$page->slug, $page->uuid]) }}" class="btn btn-sm btn-outline-info" target="_blank">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('pages.edit', $page->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('pages.destroy', $page->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this page?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No advertisement pages found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($pages->hasPages())
                <div class="card-footer">
                    {{ $pages->links() }}
                </div>
                @endif
            </div>
        </section>
    </main>
</x-app-layout>