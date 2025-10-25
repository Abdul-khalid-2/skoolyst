<x-app-layout>
    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked+.slider {
            background-color: #2196F3;
        }

        input:focus+.slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked+.slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }

        /* Status indicator */
        .status-text {
            margin-left: 10px;
            font-weight: 500;
        }

        .status-active {
            color: #28a745;
        }

        .status-inactive {
            color: #dc3545;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

            <!-- Success/Error Alert -->
            <div id="alert-container"></div>

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
                                <th>Status</th>
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
                                    <div class="d-flex align-items-center">
                                        <label class="switch">
                                            <input type="checkbox"
                                                class="status-toggle"
                                                data-event-id="{{ $event->id }}"
                                                {{ $event->status ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </label>
                                        <span class="status-text {{ $event->status ? 'status-active' : 'status-inactive' }}">
                                            {{ $event->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                </td>
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
                                <td colspan="7" class="text-center">No events found</td>
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

    <!-- AJAX Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // CSRF Token setup for AJAX requests
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Status toggle event listeners
            const statusToggles = document.querySelectorAll('.status-toggle');

            statusToggles.forEach(toggle => {
                toggle.addEventListener('change', function() {
                    const eventId = this.getAttribute('data-event-id');
                    const newStatus = this.checked ? 1 : 0;

                    updateEventStatus(eventId, newStatus, this);
                });
            });

            function updateEventStatus(eventId, status, toggleElement) {
                // Show loading state
                const originalState = toggleElement.checked;
                toggleElement.disabled = true;

                // Make AJAX request
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
                        // Success handling
                        toggleElement.disabled = false;

                        // Update status text
                        const statusText = toggleElement.closest('tr').querySelector('.status-text');
                        if (statusText) {
                            statusText.textContent = status ? 'Active' : 'Inactive';
                            statusText.className = `status-text ${status ? 'status-active' : 'status-inactive'}`;
                        }

                        // Show success message
                        showAlert('Event status updated successfully!', 'success');
                    })
                    .catch(error => {
                        // Error handling
                        console.error('Error updating event status:', error);
                        toggleElement.checked = !originalState; // Revert toggle
                        toggleElement.disabled = false;

                        // Show error message
                        showAlert('Failed to update event status. Please try again.', 'danger');
                    });
            }

            function showAlert(message, type) {
                const alertContainer = document.getElementById('alert-container');

                // Remove existing alerts
                const existingAlert = alertContainer.querySelector('.alert');
                if (existingAlert) {
                    existingAlert.remove();
                }

                // Create new alert
                const alertDiv = document.createElement('div');
                alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
                alertDiv.innerHTML = `
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;

                alertContainer.appendChild(alertDiv);

                // Auto dismiss after 5 seconds
                setTimeout(() => {
                    if (alertDiv.parentNode) {
                        alertDiv.remove();
                    }
                }, 5000);
            }
        });
    </script>
</x-app-layout>