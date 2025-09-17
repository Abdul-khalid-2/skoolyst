<x-app-layout>
    <!-- Main Content -->
    <main class="main-content">
        <!-- schoolyes Create -->
        <section id="schooles-create" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0">Create New School</h2>
                    <p class="mb-0 text-muted">Add a new school to the system</p>
                </div>
                <a href="{{ route('schooles') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Back to Schooles
                </a>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('schooles.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">School Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="address" required>
                        </div>
                        <div class="mb-3">
                            <label for="contact_person" class="form-label">Contact Person</label>
                            <input type="text" class="form-control" id="contact_person" name="contact_person" required>
                        </div>
                        <div class="mb-3">
                            <label for="contact_email" class="form-label">Contact Email</label>
                            <input type="email" class="form-control" id="contact_email" name="  contact_email" required>
                        </div>
                        <div class="mb-3">
                            <label for="contact_phone" class="form-label">Contact Phone</label>
                            <input type="text" class="form-control" id="contact_phone" name="contact_phone" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Create School</button>
                    </form>
                </div>
            </div>
        </section>


    </main>
</x-app-layout>