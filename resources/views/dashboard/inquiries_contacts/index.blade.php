<x-app-layout>
    <main class="main-content">
        <section id="inquiries" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <div>
                    <h2 class="mb-0">Contact Inquiries</h2>
                    <p class="text-muted">Manage contact inquiries from your school profile</p>
                </div>
                <div class="d-flex gap-2">
                    <!-- Status Filter -->
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-filter me-2"></i>
                            Status: {{ ucfirst(request('status', 'all')) }}
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => 'all']) }}">All Inquiries</a></li>
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => 'new']) }}">New</a></li>
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => 'in_progress']) }}">In Progress</a></li>
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => 'resolved']) }}">Resolved</a></li>
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => 'closed']) }}">Closed</a></li>
                        </ul>
                    </div>
                    
                    <!-- Export Button -->
                    <button class="btn btn-outline-primary">
                        <i class="fas fa-download me-2"></i>Export
                    </button>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-primary">
                                    <i class="fas fa-inbox"></i>
                                </div>
                                <div class="ms-3">
                                    <h4 class="mb-0">{{ $totalInquiries }}</h4>
                                    <span class="text-muted">Total Inquiries</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-danger">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="ms-3">
                                    <h4 class="mb-0">{{ $newInquiries }}</h4>
                                    <span class="text-muted">New</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-warning">
                                    <i class="fas fa-spinner"></i>
                                </div>
                                <div class="ms-3">
                                    <h4 class="mb-0">{{ $inProgressInquiries }}</h4>
                                    <span class="text-muted">In Progress</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-success">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="ms-3">
                                    <h4 class="mb-0">{{ $resolvedInquiries }}</h4>
                                    <span class="text-muted">Resolved</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="table-responsive" style="overflow-x:auto; -webkit-overflow-scrolling: touch;">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>From</th>
                                <th>Contact</th>
                                <th>Subject</th>
                                <th>Message Preview</th>
                                <th>Branch</th>
                                <th>Status</th>
                                <th>Received</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inquiries as $inquiry)
                            <tr class="{{ $inquiry->status === 'new' ? 'table-warning' : '' }}">
                                <td>{{ ($inquiries->currentPage() - 1) * $inquiries->perPage() + $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center me-2">
                                            <i class="fas fa-user text-muted"></i>
                                        </div>
                                        <div>
                                            <strong>{{ $inquiry->name }}</strong>
                                            @if($inquiry->user_id)
                                                <br><small class="text-success">✓ Registered User</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <small class="text-muted">Email:</small>
                                        <div>{{ $inquiry->email }}</div>
                                        @if($inquiry->phone)
                                            <small class="text-muted">Phone:</small>
                                            <div>{{ $inquiry->phone }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $inquiry->full_subject }}</span>
                                </td>
                                <td class="text-truncate" style="max-width: 200px;">
                                    {{ Str::limit($inquiry->message, 80) }}
                                </td>
                                <td>
                                    @if($inquiry->branch)
                                        <span class="badge bg-info">{{ $inquiry->branch->name }}</span>
                                    @else
                                        <span class="text-muted">Main</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $inquiry->status === 'new' ? 'danger' : ($inquiry->status === 'in_progress' ? 'warning' : ($inquiry->status === 'resolved' ? 'success' : 'secondary')) }}">
                                        {{ ucfirst(str_replace('_', ' ', $inquiry->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $inquiry->created_at->format('M d, Y') }}</small>
                                    <br>
                                    <small>{{ $inquiry->created_at->format('h:i A') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.inquiries.show', $inquiry->id) }}" class="btn btn-sm btn-outline-primary" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="fas fa-cog"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                @if($inquiry->status === 'new')
                                                    <li>
                                                        <form action="{{ route('admin.inquiries.updateStatus', $inquiry->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="in_progress">
                                                            <button type="submit" class="dropdown-item">Mark as In Progress</button>
                                                        </form>
                                                    </li>
                                                @endif
                                                @if($inquiry->status === 'in_progress')
                                                    <li>
                                                        <form action="{{ route('admin.inquiries.updateStatus', $inquiry->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="resolved">
                                                            <button type="submit" class="dropdown-item">Mark as Resolved</button>
                                                        </form>
                                                    </li>
                                                @endif
                                                <li>
                                                    <form action="{{ route('admin.inquiries.updateStatus', $inquiry->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="closed">
                                                        <button type="submit" class="dropdown-item text-danger">Close Inquiry</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <h5>No inquiries found</h5>
                                        <p>Contact inquiries from your school profile will appear here.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Links -->
                @if($inquiries->hasPages())
                <div class="card-footer">
                    {{ $inquiries->links() }}
                </div>
                @endif
            </div>
        </section>
    </main>

    @push('styles')
    <style>
        .stat-card {
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.2s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }
        
        .avatar-sm {
            width: 32px;
            height: 32px;
        }
        
        .table-warning {
            background-color: #fffbf0;
        }
    </style>
    @endpush
</x-app-layout>