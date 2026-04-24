<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ LaravelLocalization::localizeUrl('/') }}">
            <img src="{{ asset('assets/assets/skoolyst-header-logo1.png') }}" width="50" height="50" alt="Skoolyst logo">
            <span class="ms-2 d-none d-lg-inline">Skoolyst</span>
        </a>

        @php
            $cartCount = 0;
            if(session()->has('cart')) {
                $cart = session('cart');
                $cartCount = array_sum(array_column($cart, 'quantity'));
            }
        @endphp

        <div class="d-flex align-items-center ms-auto d-lg-none">
            <!-- Mobile Cart Icon (right side, before toggler) -->
            <a href="{{ LaravelLocalization::localizeUrl(route('website.cart', [], false)) }}" class="position-relative text-decoration-none me-2">
                <i class="fas fa-shopping-cart fa-lg cart-icon"></i>
                <span class="cart-badge position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                    {{ $cartCount > 99 ? '99+' : $cartCount }}
                </span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>

        <!-- Desktop toggler (kept for alignment, hidden on mobile) -->
        <button class="navbar-toggler d-lg-none d-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ LaravelLocalization::localizeUrl(route('website.home', [], false)) }}">
                        {{ __('messages.home') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('directory') ? 'active' : '' }}" href="{{ LaravelLocalization::localizeUrl(route('browseSchools.index', [], false)) }}">
                        {{ __('messages.schools') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('about') ? 'active' : '' }}" href="{{ LaravelLocalization::localizeUrl('/about') }}">
                        {{ __('messages.about') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('blog') ? 'active' : '' }}" href="{{ LaravelLocalization::localizeUrl('/blog') }}">
                        {{ __('messages.blog') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('shop') ? 'active' : '' }}" href="{{ LaravelLocalization::localizeUrl(route('website.shop.index', [], false)) }}">
                        {{ __('messages.shop') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('videos') ? 'active' : '' }}" href="{{ LaravelLocalization::localizeUrl(route('website.videos.index', [], false)) }}">
                        {{ __('messages.videos') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('mcqs') ? 'active' : '' }}" href="{{ LaravelLocalization::localizeUrl(route('website.mcqs.index', [], false)) }}">
                        {{ __('messages.mcqs') }}
                    </a>
                </li>
            </ul>

            <div class="navbar-actions d-flex align-items-center gap-3">
                <!-- Language Switcher -->
                <x-language-switcher />

                <!-- Cart Icon with Count (shown inside collapse / on desktop) -->
                <a href="{{ LaravelLocalization::localizeUrl(route('website.cart', [], false)) }}" class="cart-icon position-relative text-decoration-none d-none d-lg-inline-flex  me-2">
                    <i class="fas fa-shopping-cart fa-lg cart-icon"></i>
                    <span class="cart-badge position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                        {{ $cartCount > 99 ? '99+' : $cartCount }}
                    </span>
                </a>

                <!-- Auth: guest buttons / logged-in user menu -->
                <div class="auth-buttons d-flex align-items-center gap-2">
                    @if (Route::has('login'))
                    @auth
                    @php
                        $currentUser = auth()->user();
                        $navAvatarUrl = $currentUser->profile_picture_url
                            ?? 'https://ui-avatars.com/api/?name=' . urlencode($currentUser->name) . '&size=64&background=15a362&color=fff';
                    @endphp
                    <div class="dropdown">
                        <button class="btn btn-link text-decoration-none dropdown-toggle d-flex align-items-center gap-2 p-0 border-0"
                            type="button" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false"
                            id="userNavDropdown" aria-label="Account menu">
                            <img src="{{ $navAvatarUrl }}" alt="" width="36" height="36" class="rounded-circle" style="object-fit: cover;">
                            <span class="d-none d-sm-inline text-dark fw-medium">{{ \Illuminate\Support\Str::limit($currentUser->name, 22) }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="userNavDropdown">
                            @if($currentUser->hasDashboardAccess())
                            <li>
                                <a class="dropdown-item" href="{{ LaravelLocalization::localizeUrl('/dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-2 text-muted"></i>Dashboard
                                </a>
                            </li>
                            @else
                            <li>
                                <a class="dropdown-item" href="{{ LaravelLocalization::localizeUrl(route('user_profile.show', [], false)) }}">
                                    <i class="fas fa-user me-2 text-muted"></i>My profile
                                </a>
                            </li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ LaravelLocalization::localizeUrl(route('logout', [], false)) }}" class="px-0 py-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    @else
                    <a href="{{ LaravelLocalization::localizeUrl(route('login', [], false)) }}" class="btn-login">Login</a>
                    @if (Route::has('register'))
                    <a href="{{ LaravelLocalization::localizeUrl(route('register', [], false)) }}" class="btn-register">Register</a>
                    @endif
                    @endauth
                    @endif
                </div>
            </div>
        </div>
    </div>
</nav>