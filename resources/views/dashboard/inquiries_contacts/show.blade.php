<x-app-layout>
    <main class="main-content">
        <section id="inquiry-detail" class="page-section">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.inquiries.index') }}">Contact Inquiries</a></li>
                            <li class="breadcrumb-item active">Inquiry Details</li>
                        </ol>
                    </nav>
                    <h2 class="mb-0">Inquiry Details</h2>
                    <p class="text-muted">Reference: {{ $inquiry->uuid }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.inquiries.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to List
                    </a>
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-cog me-2"></i>Actions
                        </button>
                        <ul class="dropdown-menu">
                            @if($inquiry->status === 'new')
                                <li>
                                    <form action="{{ route('admin.inquiries.updateStatus', $inquiry->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="in_progress">
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-play me-2"></i>Mark as In Progress
                                        </button>
                                    </form>
                                </li>
                            @endif
                            @if($inquiry->status === 'in_progress')
                                <li>
                                    <form action="{{ route('admin.inquiries.updateStatus', $inquiry->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="resolved">
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-check me-2"></i>Mark as Resolved
                                        </button>
                                    </form>
                                </li>
                            @endif
                            <li>
                                <form action="{{ route('admin.inquiries.updateStatus', $inquiry->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="closed">
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-times me-2"></i>Close Inquiry
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Left Column - Inquiry Details -->
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Inquiry Information</h5>
                            <span class="badge bg-{{ $inquiry->status === 'new' ? 'danger' : ($inquiry->status === 'in_progress' ? 'warning' : ($inquiry->status === 'resolved' ? 'success' : 'secondary')) }}">
                                {{ ucfirst(str_replace('_', ' ', $inquiry->status)) }}
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Full Name:</strong>
                                    <p class="mb-2">{{ $inquiry->name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Email:</strong>
                                    <p class="mb-2">
                                        <a href="mailto:{{ $inquiry->email }}">{{ $inquiry->email }}</a>
                                        @if($inquiry->user_id)
                                            <span class="badge bg-success ms-2">Registered User</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Phone:</strong>
                                    <p class="mb-2">
                                        @if($inquiry->phone)
                                            <a href="tel:{{ $inquiry->phone }}">{{ $inquiry->phone }}</a>
                                        @else
                                            <span class="text-muted">Not provided</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Subject:</strong>
                                    <p class="mb-2">
                                        <span class="badge bg-light text-dark">{{ $inquiry->full_subject }}</span>
                                    </p>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <strong>Message:</strong>
                                <div class="border rounded p-3 bg-light">
                                    {{ $inquiry->message }}
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>School:</strong>
                                    <p class="mb-2">{{ $inquiry->school->name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Branch:</strong>
                                    <p class="mb-2">
                                        @if($inquiry->branch)
                                            {{ $inquiry->branch->name }}
                                        @else
                                            <span class="text-muted">Main Branch</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Admin Notes Section -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Admin Notes & Response</h5>
                        </div>
                        <div class="card-body">
                            @if($inquiry->admin_notes)
                                <div class="mb-3">
                                    <strong>Current Notes:</strong>
                                    <div class="border rounded p-3 bg-light mt-2">
                                        {{ $inquiry->admin_notes }}
                                    </div>
                                </div>
                            @endif
                            
                            <form action="{{ route('admin.inquiries.updateStatus', $inquiry->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="mb-3">
                                    <label for="admin_notes" class="form-label">Update Notes</label>
                                    <textarea class="form-control" id="admin_notes" name="admin_notes" rows="4" placeholder="Add internal notes or response details...">{{ old('admin_notes', $inquiry->admin_notes) }}</textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="status" class="form-label">Update Status</label>
                                        <select class="form-select" id="status" name="status">
                                            <option value="new" {{ $inquiry->status === 'new' ? 'selected' : '' }}>New</option>
                                            <option value="in_progress" {{ $inquiry->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                            <option value="resolved" {{ $inquiry->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                            <option value="closed" {{ $inquiry->status === 'closed' ? 'selected' : '' }}>Closed</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-save me-2"></i>Update Inquiry
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Metadata & Actions -->
                <div class="col-lg-4">
                    <!-- Inquiry Metadata -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Inquiry Metadata</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>Submitted:</strong>
                                <p class="mb-1">{{ $inquiry->created_at->format('F j, Y \a\t g:i A') }}</p>
                                <small class="text-muted">{{ $inquiry->created_at->diffForHumans() }}</small>
                            </div>
                            
                            @if($inquiry->responded_at)
                            <div class="mb-3">
                                <strong>Responded:</strong>
                                <p class="mb-1">{{ $inquiry->responded_at->format('F j, Y \a\t g:i A') }}</p>
                                <small class="text-muted">{{ $inquiry->responded_at->diffForHumans() }}</small>
                            </div>
                            @endif
                            
                            <div class="mb-3">
                                <strong>IP Address:</strong>
                                <p class="mb-0">{{ $inquiry->ip_address ?? 'Unknown' }}</p>
                            </div>
                            
                            @if($inquiry->assignedAdmin)
                            <div class="mb-3">
                                <strong>Assigned To:</strong>
                                <p class="mb-0">{{ $inquiry->assignedAdmin->name }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="mailto:{{ $inquiry->email }}?subject=Re: {{ $inquiry->full_subject }}&body=Dear {{ $inquiry->name }},"
                                   class="btn btn-outline-primary" target="_blank">
                                    <i class="fas fa-reply me-2"></i>Reply via Email
                                </a>
                                
                                @if($inquiry->phone)
                                <a href="tel:{{ $inquiry->phone }}" class="btn btn-outline-success">
                                    <i class="fas fa-phone me-2"></i>Call {{ $inquiry->name }}
                                </a>
                                @endif
                                
                                <button class="btn btn-outline-info" onclick="copyToClipboard('{{ $inquiry->uuid }}')">
                                    <i class="fas fa-copy me-2"></i>Copy Reference ID
                                </button>
                                
                                @if($inquiry->user)
                                <a href="#" class="btn btn-outline-secondary">
                                    <i class="fas fa-user me-2"></i>View User Profile
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @push('scripts')
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                // Show success message
                const btn = event.target;
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check me-2"></i>Copied!';
                btn.classList.remove('btn-outline-info');
                btn.classList.add('btn-success');
                
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-outline-info');
                }, 2000);
            });
        }
    </script>
    @endpush

    @push('styles')
    <style>
        .breadcrumb {
            background: transparent;
            padding: 0;
            margin-bottom: 0.5rem;
        }
        
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }
    </style>
    @endpush
</x-app-layout>