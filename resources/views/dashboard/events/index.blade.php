<x-app-layout>
    <main class="main-content">
        <section id="events" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-0">Events</h2>
                    <p class="text-muted">Manage school events</p>
                </div>
                <a href="{{ route('events.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add Event
                </a>
            </div>

            <div class="card">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Event Name</th>
                                <th>School</th>
                                <th>Date</th>
                                <th>Location</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($events as $event)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $event->event_name }}</td>
                                <td>{{ $event->school->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</td>
                                <td>{{ $event->event_location }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('events.edit', $event->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('events.show', $event->id) }}" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('events.destroy', $event->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No events found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($events->hasPages())
                <div class="card-footer">
                    {{ $events->links() }}
                </div>
                @endif
            </div>
        </section>
    </main>
</x-app-layout>