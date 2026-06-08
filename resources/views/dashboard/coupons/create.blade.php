<x-app-layout>
    <main class="main-content">
        <section id="add-coupon" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0">Add New Coupon</h2>
                    <p class="mb-0 text-muted">Create a discount coupon</p>
                </div>
                <a href="{{ route('coupons.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Coupons
                </a>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('coupons.store') }}" method="POST">
                @csrf
                @include('dashboard.coupons._form')

                <div class="d-flex gap-2 mb-5">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Create Coupon</button>
                    <a href="{{ route('coupons.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </section>
    </main>
</x-app-layout>
