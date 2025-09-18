<x-app-layout>
    <main class="main-content">
        <section id="events-create" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0">Create New Event</h2>
                    <p class="mb-0 text-muted">Add a new event to the system</p>
                </div>
                <a href="{{ route('events.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Back to Events
                </a>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('events.store') }}">
                                @csrf

                                <div class="mb-3">
                                    <label for="school_id" class="form-label">School</label>
                                    <select class="form-control" id="school_id" name="school_id" required onchange="updateBranches()">
                                        <option value="">Select a school</option>
                                        @foreach($schools as $school)
                                        <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
                                            {{ $school->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('school_id')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="branch_id" class="form-label">Branch (Optional)</label>
                                    <select class="form-control" id="branch_id" name="branch_id">
                                        <option value="">Select a branch (optional)</option>
                                        <!-- Branches will be populated via JavaScript -->
                                    </select>
                                    @error('branch_id')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>



                                <div class="mb-3">
                                    <label for="event_name" class="form-label">Event Name</label>
                                    <input type="text" class="form-control" id="event_name" name="event_name"
                                        value="{{ old('event_name') }}" placeholder="Enter event name" required>
                                    @error('event_name')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="event_description" class="form-label">Event Description</label>
                                    <textarea class="form-control" id="event_description" name="event_description"
                                        rows="4" placeholder="Enter event description" required>{{ old('event_description') }}</textarea>
                                    @error('event_description')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="event_date" class="form-label">Event Date</label>
                                        <input type="date" class="form-control" id="event_date" name="event_date"
                                            value="{{ old('event_date') }}" required>
                                        @error('event_date')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="event_location" class="form-label">Event Location</label>
                                        <input type="text" class="form-control" id="event_location" name="event_location"
                                            value="{{ old('event_location') }}" placeholder="Enter event location" required>
                                        @error('event_location')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="d-flex gap-2 pt-3 border-top">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Create Event
                                    </button>
                                    <a href="{{ route('events.index') }}" class="btn btn-outline-secondary">
                                        Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>



    @push('js')
    <!-- Add this JavaScript to handle dynamic branch loading -->
    <script>
        function updateBranches() {
            const schoolId = document.getElementById('school_id').value;
            const branchSelect = document.getElementById('branch_id');

            // Clear existing options
            branchSelect.innerHTML = '<option value="">Select a branch (optional)</option>';

            if (!schoolId) return;

            // Fetch branches for the selected school
            fetch(`/api/schools/${schoolId}/branches`)
                .then(response => response.json())
                .then(branches => {
                    branches.forEach(branch => {
                        const option = document.createElement('option');
                        option.value = branch.id;
                        option.textContent = `${branch.name} - ${branch.city}`;
                        if (branch.is_main_branch) {
                            option.textContent += ' (Main Branch)';
                        }
                        branchSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching branches:', error));
        }

        // If editing, populate branches on page load
        document.addEventListener('DOMContentLoaded', function() {
            const schoolId = document.getElementById('school_id').value;
            if (schoolId) {
                updateBranches();

                // After loading branches, select the previously selected branch
                setTimeout(() => {
                    const branchId = "{{ old('branch_id', isset($event) ? $event->branch_id : '') }}";
                    if (branchId) {
                        document.getElementById('branch_id').value = branchId;
                    }
                }, 500);
            }
        });
    </script>
    @endpush
</x-app-layout>