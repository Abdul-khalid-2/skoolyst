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

    <!-- Add this hidden div to store branches data -->
    <div id="branches-data" style="display: none;">
        @foreach($schools as $school)
        <div class="school-branches" data-school-id="{{ $school->id }}">
            @foreach($school->branches as $branch)
            <div data-branch-id="{{ $branch->id }}"
                data-branch-name="{{ $branch->name }}"
                data-branch-city="{{ $branch->city }}"
                data-is-main="{{ $branch->is_main_branch }}">
            </div>
            @endforeach
        </div>
        @endforeach
    </div>

    @push('js')
    <script>
        function updateBranches() {
            const schoolId = document.getElementById('school_id').value;
            const branchSelect = document.getElementById('branch_id');

            // Clear existing options except the first one
            branchSelect.innerHTML = '<option value="">Select a branch (optional)</option>';

            if (!schoolId) return;

            // Find the branches for the selected school from the hidden data
            const schoolBranches = document.querySelector(`#branches-data .school-branches[data-school-id="${schoolId}"]`);

            if (schoolBranches) {
                const branches = schoolBranches.querySelectorAll('div[data-branch-id]');

                branches.forEach(branchDiv => {
                    const branchId = branchDiv.getAttribute('data-branch-id');
                    const branchName = branchDiv.getAttribute('data-branch-name');
                    const branchCity = branchDiv.getAttribute('data-branch-city');
                    const isMain = branchDiv.getAttribute('data-is-main') === '1';

                    const option = document.createElement('option');
                    option.value = branchId;
                    option.textContent = `${branchName} - ${branchCity}`;
                    if (isMain) {
                        option.textContent += ' (Main Branch)';
                    }
                    branchSelect.appendChild(option);
                });
            }
        }

        // Initialize branches on page load if a school is already selected
        document.addEventListener('DOMContentLoaded', function() {
            const schoolId = document.getElementById('school_id').value;
            if (schoolId) {
                updateBranches();

                // Select the previously selected branch if editing
                @if(old('branch_id'))
                setTimeout(() => {
                    document.getElementById('branch_id').value = "{{ old('branch_id') }}";
                }, 100);
                @endif
            }
        });
    </script>
    @endpush
</x-app-layout>