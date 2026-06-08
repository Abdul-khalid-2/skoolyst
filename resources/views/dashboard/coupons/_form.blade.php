@php
    $coupon = $coupon ?? null;
    $selected = $selected ?? ['shops' => [], 'schools' => [], 'products' => [], 'categories' => []];

    $val = function (string $key, $default = null) use ($coupon) {
        return old($key, $coupon->{$key} ?? $default);
    };

    $scopeVal = old('scope', $coupon->scope->value ?? ($coupon->scope ?? 'global'));
    $typeVal = old('discount_type', $coupon->discount_type->value ?? ($coupon->discount_type ?? 'percentage'));

    $selectedShops = old('shop_ids', $selected['shops']);
    $selectedSchools = old('school_ids', $selected['schools']);
    $selectedProducts = old('product_ids', $selected['products']);
    $selectedCategories = old('category_ids', $selected['categories']);
@endphp

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Coupon Details</h5></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="code" class="form-label">Coupon Code *</label>
                        <input type="text" class="form-control text-uppercase @error('code') is-invalid @enderror"
                               id="code" name="code" value="{{ $val('code') }}" placeholder="e.g. BACK2SCHOOL" required>
                        @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="name" class="form-label">Name *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ $val('name') }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group mt-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror"
                              id="description" name="description" rows="3">{{ $val('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row mt-3">
                    <div class="col-md-6 form-group">
                        <label for="discount_type" class="form-label">Discount Type *</label>
                        <select class="form-control" id="discount_type" name="discount_type" required>
                            <option value="percentage" {{ $typeVal === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                            <option value="fixed_amount" {{ $typeVal === 'fixed_amount' ? 'selected' : '' }}>Fixed Amount</option>
                            <option value="free_shipping" {{ $typeVal === 'free_shipping' ? 'selected' : '' }}>Free Shipping</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="discount_value" class="form-label">Discount Value *</label>
                        <input type="number" step="0.01" min="0" class="form-control @error('discount_value') is-invalid @enderror"
                               id="discount_value" name="discount_value" value="{{ $val('discount_value', 0) }}" required>
                        <small class="form-text text-muted" id="discountHint"></small>
                        @error('discount_value')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6 form-group">
                        <label for="minimum_order_amount" class="form-label">Minimum Order Amount</label>
                        <input type="number" step="0.01" min="0" class="form-control"
                               id="minimum_order_amount" name="minimum_order_amount" value="{{ $val('minimum_order_amount') }}">
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="maximum_discount_amount" class="form-label">Maximum Discount Amount</label>
                        <input type="number" step="0.01" min="0" class="form-control"
                               id="maximum_discount_amount" name="maximum_discount_amount" value="{{ $val('maximum_discount_amount') }}">
                        <small class="form-text text-muted">Caps a percentage discount.</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Scope-specific targets --}}
        <div class="card mb-4" id="targetsCard">
            <div class="card-header"><h5 class="mb-0">Coupon Targets</h5></div>
            <div class="card-body">
                <div class="scope-target" data-scope="shop_specific">
                    <label class="form-label">Applicable Shops</label>
                    <select name="shop_ids[]" class="form-control" multiple size="6">
                        @foreach($shops as $shop)
                            <option value="{{ $shop->id }}" {{ in_array($shop->id, $selectedShops) ? 'selected' : '' }}>{{ $shop->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="scope-target" data-scope="school_specific">
                    <label class="form-label">Applicable Schools</label>
                    <select name="school_ids[]" class="form-control" multiple size="6">
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}" {{ in_array($school->id, $selectedSchools) ? 'selected' : '' }}>{{ $school->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="scope-target" data-scope="product_specific">
                    <label class="form-label">Applicable Products</label>
                    <select name="product_ids[]" class="form-control mb-3" multiple size="6">
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ in_array($product->id, $selectedProducts) ? 'selected' : '' }}>{{ $product->name }}</option>
                        @endforeach
                    </select>
                    <label class="form-label">Applicable Categories</label>
                    <select name="category_ids[]" class="form-control" multiple size="6">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ in_array($category->id, $selectedCategories) ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <p class="text-muted mb-0" id="globalScopeNote">This coupon applies to the entire cart (global scope).</p>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Scope &amp; Limits</h5></div>
            <div class="card-body">
                <div class="form-group">
                    <label for="scope" class="form-label">Scope *</label>
                    <select class="form-control" id="scope" name="scope" required>
                        <option value="global" {{ $scopeVal === 'global' ? 'selected' : '' }}>Global (whole cart)</option>
                        <option value="shop_specific" {{ $scopeVal === 'shop_specific' ? 'selected' : '' }}>Shop specific</option>
                        <option value="school_specific" {{ $scopeVal === 'school_specific' ? 'selected' : '' }}>School specific</option>
                        <option value="product_specific" {{ $scopeVal === 'product_specific' ? 'selected' : '' }}>Product / Category specific</option>
                    </select>
                </div>

                <div class="form-group mt-3">
                    <label for="usage_limit" class="form-label">Total Usage Limit</label>
                    <input type="number" min="1" class="form-control" id="usage_limit" name="usage_limit" value="{{ $val('usage_limit') }}">
                    <small class="form-text text-muted">Leave empty for unlimited.</small>
                </div>

                <div class="form-group mt-3">
                    <label for="usage_per_customer" class="form-label">Usage Per Customer</label>
                    <input type="number" min="1" class="form-control" id="usage_per_customer" name="usage_per_customer" value="{{ $val('usage_per_customer') }}">
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Validity</h5></div>
            <div class="card-body">
                <div class="form-group">
                    <label for="valid_from" class="form-label">Valid From</label>
                    <input type="datetime-local" class="form-control" id="valid_from" name="valid_from"
                           value="{{ old('valid_from', $coupon?->valid_from?->timezone(config('shop.timezone'))->format('Y-m-d\TH:i')) }}">
                </div>
                <div class="form-group mt-3">
                    <label for="valid_until" class="form-label">Valid Until</label>
                    <input type="datetime-local" class="form-control @error('valid_until') is-invalid @enderror" id="valid_until" name="valid_until"
                           value="{{ old('valid_until', $coupon?->valid_until?->timezone(config('shop.timezone'))->format('Y-m-d\TH:i')) }}">
                    @error('valid_until')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Status</h5></div>
            <div class="card-body">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1"
                           {{ old('is_active', $coupon->is_active ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Active</label>
                </div>
                <div class="form-check mt-2">
                    <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1"
                           {{ old('is_featured', $coupon->is_featured ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_featured">Featured</label>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    (function () {
        const scopeSelect = document.getElementById('scope');
        const targets = document.querySelectorAll('.scope-target');
        const globalNote = document.getElementById('globalScopeNote');
        const typeSelect = document.getElementById('discount_type');
        const valueInput = document.getElementById('discount_value');
        const hint = document.getElementById('discountHint');

        function refreshTargets() {
            const scope = scopeSelect.value;
            let anyVisible = false;
            targets.forEach(t => {
                const show = t.dataset.scope === scope;
                t.style.display = show ? 'block' : 'none';
                if (show) anyVisible = true;
            });
            globalNote.style.display = anyVisible ? 'none' : 'block';
        }

        function refreshType() {
            const type = typeSelect.value;
            if (type === 'free_shipping') {
                valueInput.value = valueInput.value || 0;
                valueInput.setAttribute('readonly', 'readonly');
                hint.textContent = 'Free shipping ignores the discount value.';
            } else {
                valueInput.removeAttribute('readonly');
                hint.textContent = type === 'percentage' ? 'Enter a percentage, e.g. 10 for 10%.' : 'Enter a flat amount.';
            }
        }

        scopeSelect.addEventListener('change', refreshTargets);
        typeSelect.addEventListener('change', refreshType);
        refreshTargets();
        refreshType();
    })();
</script>
@endpush
