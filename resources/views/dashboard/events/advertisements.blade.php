<x-app-layout>
    <main class="main-content">
        <section id="advertisements" class="page-section">
            <x-page-header>
                <x-slot name="heading">
                    <h2 class="mb-0">Advertisement Pages</h2>
                    <p class="text-muted">Manage dynamic advertisement pages</p>
                </x-slot>
                <x-slot name="actions">
                    <x-button href="{{ route('pages.create', [$school_uuid, $id]) }}" variant="primary" class="me-2">
                        <i class="fas fa-edit me-2"></i> Create Banner
                    </x-button>
                </x-slot>
            </x-page-header>

            <x-card>
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
                                    <div class="btn-group events-table-actions" role="group">
                                        <x-button href="{{ route('pages.show', [$page->slug, $page->uuid]) }}" variant="outline-info" class="btn-sm" target="_blank" title="View">
                                            <i class="fas fa-eye"></i>
                                        </x-button>
                                        <x-button href="{{ route('pages.edit', $page->id) }}" variant="outline-primary" class="btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </x-button>
                                        <form action="{{ route('pages.destroy', $page->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <x-button type="submit" variant="outline-danger" class="btn-sm" onclick="return confirm('Delete this page?')">
                                                <i class="fas fa-trash"></i>
                                            </x-button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="p-0 border-0">
                                    <x-empty-state title="No advertisement pages found" />
                                </td>
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
            </x-card>
        </section>
    </main>
</x-app-layout>