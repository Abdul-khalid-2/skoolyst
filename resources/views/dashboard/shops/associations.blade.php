<x-app-layout>
    <main class="main-content">
        <section id="shop-associations" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0">School Associations</h2>
                    <p class="mb-0 text-muted">Manage school associations for {{ $shop->name }}</p>
                </div>
                <div class="btn-group">
                    <a href="{{ route('shops.show', $shop) }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i> Back to Shop
                    </a>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#associateSchoolModal">
                        <i class="fas fa-plus me-2"></i> Associate School
                    </button>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Associations Statistics -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $associations->count() }}</h4>
                                    <p class="mb-0">Total Associations</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-school fa-2x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $associations->where('status', 'approved')->count() }}</h4>
                                    <p class="mb-0">Approved</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-check-circle fa-2x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $associations->where('status', 'pending')->count() }}</h4>
                                    <p class="mb-0">Pending</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-clock fa-2x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $associations->where('status', 'rejected')->count() }}</h4>
                                    <p class="mb-0">Rejected</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-times-circle fa-2x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-handshake me-2"></i>School Associations
                    </h5>
                </div>
                <div class="card-body">
                    @if($associations->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>School</th>
                                    <th>Association Type</th>
                                    <th>Discount</th>
                                    <th>Status</th>
                                    <th>Permissions</th>
                                    <th>Created By</th>
                                    <th>Approved By</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($associations as $association)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($association->school->logo_url)
                                            <img src="{{ Storage::disk('website')->url($association->school->logo_url) }}" 
                                                 alt="{{ $association->school->name }}" class="rounded me-3" width="40" height="40">
                                            @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-school text-muted"></i>
                                            </div>
                                            @endif
                                            <div>
                                                <strong>{{ $association->school->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $association->school->city }}, {{ $association->school->state }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info text-capitalize">
                                            {{ str_replace('_', ' ', $association->association_type) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($association->discount_percentage > 0)
                                        <span class="badge bg-success">
                                            {{ $association->discount_percentage }}% OFF
                                        </span>
                                        @else
                                        <span class="text-muted">No discount</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $association->status == 'approved' ? 'success' : 
                                            ($association->status == 'pending' ? 'warning' : 'danger') 
                                        }}">
                                            {{ ucfirst($association->status) }}
                                        </span>
                                        <br>
                                        <small class="text-muted">
                                            @if($association->is_active)
                                            <i class="fas fa-circle text-success me-1"></i>Active
                                            @else
                                            <i class="fas fa-circle text-secondary me-1"></i>Inactive
                                            @endif
                                        </small>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column small">
                                            @if($association->can_add_products)
                                            <span class="text-success mb-1">
                                                <i class="fas fa-check-circle me-1"></i>Add Products
                                            </span>
                                            @endif
                                            @if($association->can_manage_products)
                                            <span class="text-success mb-1">
                                                <i class="fas fa-check-circle me-1"></i>Manage Products
                                            </span>
                                            @endif
                                            @if($association->can_view_analytics)
                                            <span class="text-success">
                                                <i class="fas fa-check-circle me-1"></i>View Analytics
                                            </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <small>
                                            {{ $association->createdBy->name ?? 'System' }}
                                            <br>
                                            <span class="text-muted">{{ $association->created_at->format('M j, Y') }}</span>
                                        </small>
                                    </td>
                                    <td>
                                        @if($association->approvedBy)
                                        <small>
                                            {{ $association->approvedBy->name }}
                                            <br>
                                            <span class="text-muted">{{ $association->approved_at?->format('M j, Y') }}</span>
                                        </small>
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $association->created_at->format('M j, Y') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            @if($association->status == 'pending' && auth()->user()->hasRole('super_admin'))
                                            <form action="{{ route('shop-school-associations.approve', $association) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success" title="Approve">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('shop-school-associations.reject', $association) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger" title="Reject">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                            @endif
                                            
                                            <a href="#" class="btn btn-sm btn-outline-primary" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            @if(auth()->user()->can('update', $association))
                                            <a href="{{ route('shop-school-associations.edit', $association) }}" 
                                               class="btn btn-sm btn-outline-secondary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @endif
                                            
                                            @if(auth()->user()->can('delete', $association))
                                            <form action="{{ route('shop-school-associations.destroy', $association) }}" method="POST" 
                                                  class="d-inline" onsubmit="return confirm('Are you sure you want to delete this association?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <div class="text-muted">
                            <i class="fas fa-handshake fa-3x mb-3"></i>
                            <h5>No School Associations</h5>
                            <p class="mb-4">This shop doesn't have any school associations yet.</p>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#associateSchoolModal">
                                <i class="fas fa-plus me-2"></i>Associate First School
                            </button>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </section>
    </main>

    <!-- Associate School Modal -->
    <div class="modal fade" id="associateSchoolModal" tabindex="-1" aria-labelledby="associateSchoolModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('shops.associate-school', $shop) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="associateSchoolModalLabel">Associate School</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="school_id" class="form-label">Select School *</label>
                            <select class="form-select @error('school_id') is-invalid @enderror" 
                                    id="school_id" name="school_id" required>
                                <option value="">Select School</option>
                                <!-- You'll need to populate this with actual schools from your database -->
                                @foreach(\App\Models\School::where('status', true)->get() as $school)
                                <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
                                    {{ $school->name }} - {{ $school->city }}, {{ $school->state }}
                                </option>
                                @endforeach
                            </select>
                            @error('school_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="association_type" class="form-label">Association Type *</label>
                            <select class="form-select @error('association_type') is-invalid @enderror" 
                                    id="association_type" name="association_type" required>
                                <option value="">Select Type</option>
                                <option value="preferred" {{ old('association_type') == 'preferred' ? 'selected' : '' }}>Preferred</option>
                                <option value="official" {{ old('association_type') == 'official' ? 'selected' : '' }}>Official</option>
                                <option value="affiliated" {{ old('association_type') == 'affiliated' ? 'selected' : '' }}>Affiliated</option>
                                <option value="general" {{ old('association_type') == 'general' ? 'selected' : '' }}>General</option>
                            </select>
                            @error('association_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="discount_percentage" class="form-label">Discount Percentage</label>
                            <input type="number" step="0.01" min="0" max="100" class="form-control @error('discount_percentage') is-invalid @enderror" 
                                   id="discount_percentage" name="discount_percentage" value="{{ old('discount_percentage', 0) }}">
                            <div class="form-text">Special discount percentage for this school (0-100%)</div>
                            @error('discount_percentage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Permissions</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="can_add_products" name="can_add_products" 
                                       value="1" {{ old('can_add_products', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="can_add_products">
                                    Can Add Products
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="can_manage_products" name="can_manage_products" 
                                       value="1" {{ old('can_manage_products', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="can_manage_products">
                                    Can Manage Products
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="can_view_analytics" name="can_view_analytics" 
                                       value="1" {{ old('can_view_analytics', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="can_view_analytics">
                                    Can View Analytics
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-handshake me-2"></i>Create Association
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>