<x-app-layout>
    <main class="main-content">
        <section id="event-show" class="page-section">
            <x-page-header>
                <x-slot name="heading">
                    <h2 class="h4 mb-0">Event Details</h2>
                    <p class="mb-0 text-muted">View complete information about {{ $event->event_name }}</p>
                </x-slot>
                <x-slot name="actions">
                    <x-button href="{{ route('pages.index', $event->id) }}" variant="primary" class="me-2">
                        <i class="fas fa-edit me-2"></i> Create Banner
                    </x-button>
                    <x-button href="{{ route('events.edit', $event->id) }}" variant="primary" class="me-2">
                        <i class="fas fa-edit me-2"></i> Edit Event
                    </x-button>
                    <x-button href="{{ route('events.index') }}" variant="secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back to Events
                    </x-button>
                </x-slot>
            </x-page-header>

            <div class="row">
                <div class="col-lg-8">
                    <x-card class="mb-4">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">Event Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Event Name:</strong>
                                    <p>{{ $event->event_name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>School:</strong>
                                    <p>{{ $event->school->name }}</p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Branch:</strong>
                                    <p>
                                        @if($event->branch)
                                        {{ $event->branch->name }}
                                        @else
                                        <span class="text-muted">All Branches</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Event Date:</strong>
                                    <p>{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Event Location:</strong>
                                    <p>{{ $event->event_location }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Scope:</strong>
                                    <p>
                                        @if($event->branch)
                                        <x-badge variant="info">Branch Specific</x-badge>
                                        @else
                                        <x-badge variant="primary">School Wide</x-badge>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12">
                                    <strong>Event Description:</strong>
                                    <p>{{ $event->event_description }}</p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Created At:</strong>
                                    <p>{{ $event->created_at->format('M d, Y') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Last Updated:</strong>
                                    <p>{{ $event->updated_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </x-card>
                </div>

                <div class="col-lg-4">
                    <x-card class="mb-4">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <x-button href="{{ route('events.edit', $event->id) }}" variant="primary">
                                    <i class="fas fa-edit me-2"></i> Edit Event
                                </x-button>
                                <form action="{{ route('events.destroy', $event->id) }}" method="POST" class="d-grid">
                                    @csrf
                                    @method('DELETE')
                                    <x-button type="submit" variant="danger"
                                        onclick="return confirm('Are you sure you want to delete this event?')">
                                        <i class="fas fa-trash me-2"></i> Delete Event
                                    </x-button>
                                </form>
                            </div>
                        </div>
                    </x-card>

                    <x-card>
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">
                                @if($event->branch)
                                Branch Information
                                @else
                                School Information
                                @endif
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($event->branch)
                            <!-- Branch Details -->
                            <div class="mb-3">
                                <strong>Branch Name:</strong>
                                <p>{{ $event->branch->name }}</p>
                            </div>
                            <div class="mb-3">
                                <strong>Address:</strong>
                                <p>{{ $event->branch->address }}, {{ $event->branch->city }}</p>
                            </div>
                            <div class="mb-3">
                                <strong>Contact Number:</strong>
                                <p>{{ $event->branch->contact_number ?? 'Not provided' }}</p>
                            </div>
                            @if($event->branch->branch_head_name)
                            <div class="mb-3">
                                <strong>Branch Head:</strong>
                                <p>{{ $event->branch->branch_head_name }}</p>
                            </div>
                            @endif
                            <x-button href="{{ route('schools.branches.show', [$event->branch->school_id, $event->branch->id]) }}"
                                variant="outline-primary" class="btn-sm w-100">
                                View Branch Details
                            </x-button>
                            @else
                            <!-- School Details (when no branch selected) -->
                            <div class="mb-3">
                                <strong>School Name:</strong>
                                <p>{{ $event->school->name }}</p>
                            </div>
                            <div class="mb-3">
                                <strong>Email:</strong>
                                <p>{{ $event->school->email ?? 'Not provided' }}</p>
                            </div>
                            <div class="mb-3">
                                <strong>Contact Number:</strong>
                                <p>{{ $event->school->contact_number ?? 'Not provided' }}</p>
                            </div>
                            <x-button href="{{ route('schools.show', $event->school->id) }}" variant="outline-primary" class="btn-sm w-100">
                                View School Details
                            </x-button>
                            @endif
                        </div>
                    </x-card>
                </div>
            </div>
        </section>
    </main>
</x-app-layout>