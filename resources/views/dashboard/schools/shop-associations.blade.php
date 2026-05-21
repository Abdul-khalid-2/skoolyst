<x-app-layout>
    <main class="main-content">
        <section class="page-section">
            <div class="container-fluid py-4">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="mb-0">Shop Association Requests</h4>
                        <small class="text-muted">Shops requesting affiliation with {{ $school->name }}</small>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                {{-- Stats --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="card text-center border-warning">
                            <div class="card-body py-3">
                                <h3 class="text-warning mb-0">{{ $pendingCount }}</h3>
                                <small class="text-muted">Pending</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center border-success">
                            <div class="card-body py-3">
                                <h3 class="text-success mb-0">{{ $approvedCount }}</h3>
                                <small class="text-muted">Approved</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center border-danger">
                            <div class="card-body py-3">
                                <h3 class="text-danger mb-0">{{ $rejectedCount }}</h3>
                                <small class="text-muted">Rejected</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Filter --}}
                <div class="mb-3 d-flex gap-2">
                    <a href="{{ route('school.shop-associations.index') }}"
                       class="btn btn-sm {{ !request('status') ? 'btn-dark' : 'btn-outline-secondary' }}">All</a>
                    <a href="{{ route('school.shop-associations.index', ['status' => 'pending']) }}"
                       class="btn btn-sm {{ request('status') === 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">Pending</a>
                    <a href="{{ route('school.shop-associations.index', ['status' => 'approved']) }}"
                       class="btn btn-sm {{ request('status') === 'approved' ? 'btn-success' : 'btn-outline-success' }}">Approved</a>
                    <a href="{{ route('school.shop-associations.index', ['status' => 'rejected']) }}"
                       class="btn btn-sm {{ request('status') === 'rejected' ? 'btn-danger' : 'btn-outline-danger' }}">Rejected</a>
                </div>

                {{-- Table --}}
                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        @if($associations->count())
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Shop</th>
                                        <th>Type</th>
                                        <th>Discount</th>
                                        <th>Permissions</th>
                                        <th>Requested By</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($associations as $association)
                                    @php
                                        $statusVal = $association->status instanceof \BackedEnum
                                            ? $association->status->value
                                            : $association->status;
                                        $typeVal = $association->association_type instanceof \BackedEnum
                                            ? $association->association_type->value
                                            : $association->association_type;
                                    @endphp
                                    <tr>
                                        <td>
                                            <strong>{{ $association->shop->name ?? '—' }}</strong><br>
                                            <small class="text-muted">{{ $association->shop->city ?? '' }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info text-dark">{{ ucfirst($typeVal) }}</span>
                                        </td>
                                        <td>
                                            @if($association->discount_percentage)
                                                <span class="badge bg-success">{{ $association->discount_percentage }}% OFF</span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small>
                                                @if($association->can_add_products) <span class="text-success">✔ Add Products</span><br> @endif
                                                @if($association->can_manage_products) <span class="text-success">✔ Manage Products</span><br> @endif
                                                @if($association->can_view_analytics) <span class="text-success">✔ Analytics</span> @endif
                                            </small>
                                        </td>
                                        <td>
                                            <small>{{ $association->createdBy->name ?? '—' }}<br>
                                            {{ $association->created_at->format('M j, Y') }}</small>
                                        </td>
                                        <td>
                                            <small>{{ $association->created_at->format('M j, Y') }}</small>
                                        </td>
                                        <td>
                                            @if($statusVal === 'pending')
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @elseif($statusVal === 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @else
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($statusVal === 'pending')
                                            <div class="d-flex gap-1">
                                                <form action="{{ route('school.shop-associations.approve', $association) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" title="Approve">
                                                        <i class="fas fa-check"></i> Approve
                                                    </button>
                                                </form>
                                                <form action="{{ route('school.shop-associations.reject', $association) }}" method="POST"
                                                      onsubmit="return confirm('Reject this association request?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Reject">
                                                        <i class="fas fa-times"></i> Reject
                                                    </button>
                                                </form>
                                            </div>
                                            @else
                                                <span class="text-muted small">No actions</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="p-3">{{ $associations->links() }}</div>
                        @else
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-handshake fa-3x mb-3 opacity-25"></i>
                            <p>No association requests yet.</p>
                        </div>
                        @endif
                    </div>
                </div>

            </div>
        </section>
    </main>
</x-app-layout>
