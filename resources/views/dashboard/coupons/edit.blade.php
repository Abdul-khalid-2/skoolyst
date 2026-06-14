<x-app-layout>
    <main class="main-content">
        <section id="edit-coupon" class="page-section">
            <x-page-header class="mb-4">
                <x-slot name="heading">
                    <h2 class="h4 mb-0">Edit Coupon</h2>
                    <p class="mb-0 text-muted">{{ $coupon->code }}</p>
                </x-slot>
                <x-slot name="actions">
                    <x-button href="{{ route('coupons.index') }}" variant="secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Coupons
                    </x-button>
                </x-slot>
            </x-page-header>

            @if($errors->any())
                <x-alert variant="danger" class="mb-4">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </x-alert>
            @endif

            <form action="{{ route('coupons.update', $coupon) }}" method="POST">
                @csrf
                @method('PUT')
                @include('dashboard.coupons._form')

                <div class="d-flex gap-2 mb-5">
                    <x-button type="submit" variant="primary"><i class="fas fa-save me-2"></i>Update Coupon</x-button>
                    <x-button href="{{ route('coupons.index') }}" variant="secondary">Cancel</x-button>
                </div>
            </form>
        </section>
    </main>
</x-app-layout>
