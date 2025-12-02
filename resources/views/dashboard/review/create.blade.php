<x-app-layout>
    <main class="main-content">
        <section id="reviews" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0">Add New Review</h2>
                    <p class="mb-0 text-muted">Create a new school review</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('reviews.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back to Reviews
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('reviews.store') }}" method="POST">
                                @csrf

                                <div class="row g-3">
                                    <!-- School Selection -->
                                    <div class="col-md-6">
                                        <label class="form-label">School *</label>
                                        <select name="school_id" class="form-select" required id="schoolSelect">
                                            <option value="">Select School</option>
                                            @foreach($schools as $school)
                                            <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
                                                {{ $school->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('school_id')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Branch Selection -->
                                    <div class="col-md-6">
                                        <label class="form-label">Branch</label>
                                        <select name="branch_id" class="form-select" id="branchSelect">
                                            <option value="">Select Branch</option>
                                            <!-- Branches will be loaded via AJAX -->
                                        </select>
                                        @error('branch_id')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Rating -->
                                    <div class="col-12">
                                        <label class="form-label">Rating *</label>
                                        <div class="rating-input mb-3">
                                            <div class="d-flex gap-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <label class="star-label" style="cursor: pointer;">
                                                    <input type="radio" name="rating" value="{{ $i }}"
                                                        class="d-none" {{ old('rating') == $i ? 'checked' : '' }}>
                                                    <i class="far fa-star fa-2x text-warning"
                                                        data-star="{{ $i }}"
                                                        style="transition: all 0.2s;"></i>
                                                    </label>
                                                    @endfor
                                            </div>
                                            <div class="text-muted small mt-1">Click on stars to rate</div>
                                        </div>
                                        @error('rating')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Reviewer Information -->
                                    <div class="col-md-6">
                                        <label class="form-label">Reviewer Name *</label>
                                        <input type="text" name="reviewer_name" class="form-control"
                                            value="{{ old('reviewer_name') }}" required>
                                        @error('reviewer_name')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Reviewer Email</label>
                                        <input type="email" name="reviewer_email" class="form-control"
                                            value="{{ old('reviewer_email') }}">
                                        <small class="text-muted">If email matches a user account, it will be linked</small>
                                        @error('reviewer_email')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Review Content -->
                                    <div class="col-12">
                                        <label class="form-label">Review *</label>
                                        <textarea name="review" class="form-control" rows="5" required>{{ old('review') }}</textarea>
                                        <small class="text-muted">Minimum 10 characters, maximum 1000 characters</small>
                                        @error('review')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Admin Notes -->
                                    <div class="col-12">
                                        <label class="form-label">Admin Notes</label>
                                        <textarea name="admin_notes" class="form-control" rows="3">{{ old('admin_notes') }}</textarea>
                                        <small class="text-muted">These notes are only visible to admins</small>
                                        @error('admin_notes')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save me-2"></i> Create Review
                                            </button>
                                            <a href="{{ route('reviews.index') }}" class="btn btn-outline-secondary">
                                                Cancel
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Guidelines</h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <i class="fas fa-info-circle text-primary me-2"></i>
                                    Reviews must be genuine and constructive
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-star text-warning me-2"></i>
                                    Provide accurate ratings based on real experience
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                    Avoid offensive language or personal attacks
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-eye text-info me-2"></i>
                                    Reviews will be publicly visible after approval
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-user-shield text-success me-2"></i>
                                    Admin created reviews are auto-approved
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="mb-0">Quick Stats</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="h4 mb-0 text-primary">0</div>
                                        <small class="text-muted">Today</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="h4 mb-0 text-success">0</div>
                                        <small class="text-muted">This Week</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <style>
        .star-label:hover i,
        .star-label input:checked~i {
            font-weight: 900 !important;
        }

        .star-label i:hover {
            transform: scale(1.2);
        }

        .star-label input:checked~i {
            transform: scale(1.1);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Star rating interaction
            const starLabels = document.querySelectorAll('.star-label');
            starLabels.forEach(label => {
                const star = label.querySelector('i');
                const input = label.querySelector('input');

                star.addEventListener('click', function() {
                    const value = this.getAttribute('data-star');

                    // Update all stars
                    starLabels.forEach(l => {
                        const s = l.querySelector('i');
                        const i = l.querySelector('input');
                        const starValue = s.getAttribute('data-star');

                        if (starValue <= value) {
                            s.classList.remove('far');
                            s.classList.add('fas');
                        } else {
                            s.classList.remove('fas');
                            s.classList.add('far');
                        }

                        // Update radio button
                        if (starValue == value) {
                            i.checked = true;
                        }
                    });
                });
            });

            // Load branches when school changes
            const schoolSelect = document.getElementById('schoolSelect');
            const branchSelect = document.getElementById('branchSelect');

            if (schoolSelect) {
                schoolSelect.addEventListener('change', function() {
                    const schoolId = this.value;

                    if (schoolId) {
                        fetch(`dashboard/reviews/get-branches?school_id=${schoolId}`)
                            .then(response => response.json())
                            .then(data => {
                                branchSelect.innerHTML = '<option value="">Select Branch</option>';
                                data.forEach(branch => {
                                    const option = document.createElement('option');
                                    option.value = branch.id;
                                    option.textContent = branch.name;
                                    branchSelect.appendChild(option);
                                });
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                branchSelect.innerHTML = '<option value="">Error loading branches</option>';
                            });
                    } else {
                        branchSelect.innerHTML = '<option value="">Select Branch</option>';
                    }
                });
            }

            // Set initial branches if school is pre-selected
            @if(old('school_id'))
            const initialSchoolId = {
                {
                    old('school_id')
                }
            };
            if (schoolSelect) {
                fetch(`dashboard/reviews/get-branches?school_id=${initialSchoolId}`)
                    .then(response => response.json())
                    .then(data => {
                        branchSelect.innerHTML = '<option value="">Select Branch</option>';
                        data.forEach(branch => {
                            const option = document.createElement('option');
                            option.value = branch.id;
                            option.textContent = branch.name;
                            if (branch.id == {
                                    {
                                        old('branch_id') ?? 'null'
                                    }
                                }) {
                                option.selected = true;
                            }
                            branchSelect.appendChild(option);
                        });
                    });
            }
            @endif

            // Set initial star rating
            @if(old('rating'))
            const initialRating = {
                {
                    old('rating')
                }
            };
            starLabels.forEach(label => {
                const star = label.querySelector('i');
                const input = label.querySelector('input');
                const starValue = star.getAttribute('data-star');

                if (starValue <= initialRating) {
                    star.classList.remove('far');
                    star.classList.add('fas');
                }

                if (starValue == initialRating) {
                    input.checked = true;
                }
            });
            @endif
        });
    </script>
</x-app-layout>