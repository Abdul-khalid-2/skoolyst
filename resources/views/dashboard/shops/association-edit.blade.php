<x-app-layout>
    <main class="main-content">
        <section class="page-section">
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            <div class="d-flex align-items-center gap-3 mb-4">
                <a href="{{ route('shops.associations', $shopSchoolAssociation->shop_id) }}"
                   class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
                <h4 class="mb-0">Edit Association</h4>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card shadow-sm">
                <div class="card-body p-4">

                    <div class="mb-3 p-3 bg-light rounded">
                        <strong>Shop:</strong> {{ $shopSchoolAssociation->shop->name ?? '—' }}<br>
                        <strong>School:</strong> {{ $shopSchoolAssociation->school->name ?? '—' }}
                    </div>

                    <form action="{{ route('shop-school-associations.update', $shopSchoolAssociation) }}"
                          method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Association Type</label>
                            <select name="association_type" class="form-select">
                                @foreach(['preferred','official','affiliated','general'] as $type)
                                    <option value="{{ $type }}"
                                        {{ (($shopSchoolAssociation->association_type instanceof \BackedEnum ? $shopSchoolAssociation->association_type->value : $shopSchoolAssociation->association_type) === $type) ? 'selected' : '' }}>
                                        {{ ucfirst($type) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Discount Percentage</label>
                            <input type="number" name="discount_percentage" step="0.01" min="0" max="100"
                                   class="form-control @error('discount_percentage') is-invalid @enderror"
                                   value="{{ old('discount_percentage', $shopSchoolAssociation->discount_percentage) }}">
                            @error('discount_percentage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Permissions</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="can_add_products"
                                       value="1" id="can_add_products"
                                       {{ $shopSchoolAssociation->can_add_products ? 'checked' : '' }}>
                                <label class="form-check-label" for="can_add_products">Add Products</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="can_manage_products"
                                       value="1" id="can_manage_products"
                                       {{ $shopSchoolAssociation->can_manage_products ? 'checked' : '' }}>
                                <label class="form-check-label" for="can_manage_products">Manage Products</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="can_view_analytics"
                                       value="1" id="can_view_analytics"
                                       {{ $shopSchoolAssociation->can_view_analytics ? 'checked' : '' }}>
                                <label class="form-check-label" for="can_view_analytics">View Analytics</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Active</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active"
                                       value="1" id="is_active"
                                       {{ $shopSchoolAssociation->is_active ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Association is active</label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="3">{{ old('notes', $shopSchoolAssociation->notes) }}</textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Save Changes
                            </button>
                            <a href="{{ route('shops.associations', $shopSchoolAssociation->shop_id) }}"
                               class="btn btn-outline-secondary">Cancel</a>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
        </section>
    </main>
</x-app-layout>
