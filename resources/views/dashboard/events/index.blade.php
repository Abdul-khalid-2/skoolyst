<x-app-layout>
    @push('css')
        <link rel="stylesheet" href="{{ asset('css/dashboard/events/index.css') }}">
    @endpush
    <main class="main-content">
        <section id="events" class="page-section">
            <x-page-header>
                <x-slot name="heading">
                    <h2 class="mb-0">Events</h2>
                    <p class="text-muted">Manage school events</p>
                </x-slot>
                <x-slot name="actions">
                    <x-button href="{{ route('events.create') }}" variant="primary">
                        <i class="fas fa-plus me-2"></i>Add Event
                    </x-button>
                </x-slot>
            </x-page-header>

            <div id="alert-container"></div>

            <x-card>
                <div class="table-responsive events-table-wrap">
                    <table class="table table-hover align-middle mb-0 text-nowrap events-table">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Event Name</th>
                                <th>School</th>
                                <th>Date</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($events as $event)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-truncate event-name-col">{{ $event->event_name }}</td>
                                <td>{{ $event->school->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</td>
                                <td class="text-truncate event-location-col">{{ $event->event_location }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <label class="switch">
                                            <input type="checkbox"
                                                class="status-toggle"
                                                data-event-id="{{ $event->id }}"
                                                {{ $event->status === 'active' ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </label>
                                        <span class="status-text {{ $event->status === 'active' ? 'status-active' : 'status-inactive' }}">
                                            {{ $event->status === 'active' ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group events-table-actions" role="group">
                                        <x-button href="{{ route('events.edit', $event->id) }}" variant="outline-primary" class="btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </x-button>
                                        <x-button href="{{ route('events.show', $event->id) }}" variant="outline-info" class="btn-sm" title="View">
                                            <i class="fas fa-eye"></i>
                                        </x-button>
                                        <form action="{{ route('events.destroy', $event->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <x-button type="submit" variant="outline-danger" class="btn-sm" onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash"></i>
                                            </x-button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="p-0 border-0">
                                    <x-empty-state title="No events found" />
                                </td>
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
            </x-card>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const statusToggles = document.querySelectorAll('.status-toggle');

            statusToggles.forEach(toggle => {
                toggle.addEventListener('change', function() {
                    const eventId = this.getAttribute('data-event-id');
                    const newStatus = this.checked ? 1 : 0;

                    updateEventStatus(eventId, newStatus, this);
                });
            });

            function updateEventStatus(eventId, status, toggleElement) {
                const originalState = toggleElement.checked;
                toggleElement.disabled = true;

                fetch(`/events/${eventId}/status`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            status: status
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        toggleElement.disabled = false;

                        const statusText = toggleElement.closest('tr').querySelector('.status-text');
                        if (statusText) {
                            statusText.textContent = status ? 'Active' : 'Inactive';
                            statusText.className = `status-text ${status ? 'status-active' : 'status-inactive'}`;
                        }

                        showAlert('Event status updated successfully!', 'success');
                    })
                    .catch(error => {
                        console.error('Error updating event status:', error);
                        toggleElement.checked = !originalState;
                        toggleElement.disabled = false;

                        showAlert('Failed to update event status. Please try again.', 'danger');
                    });
            }

            function showAlert(message, type) {
                const alertContainer = document.getElementById('alert-container');

                const existingAlert = alertContainer.querySelector('.alert');
                if (existingAlert) {
                    existingAlert.remove();
                }

                const iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';

                const alertDiv = document.createElement('div');
                alertDiv.setAttribute('role', 'alert');
                alertDiv.className = 'alert alert-' + type + ' alert-dismissible fade show mx-3 mx-md-0 mb-4';
                alertDiv.innerHTML =
                    '<div class="d-flex align-items-center">' +
                    '<i class="fas ' + iconClass + ' me-2" aria-hidden="true"></i>' +
                    '<div class="flex-grow-1">' + message + '</div>' +
                    '</div>' +
                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';

                alertContainer.appendChild(alertDiv);

                setTimeout(() => {
                    if (alertDiv.parentNode) {
                        alertDiv.remove();
                    }
                }, 5000);
            }
        });
    </script>
</x-app-layout>
