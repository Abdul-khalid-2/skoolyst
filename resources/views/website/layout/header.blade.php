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

                <!-- Auth Buttons -->
                <div class="auth-buttons d-flex gap-2">
                    @if (Route::has('login'))
                    @auth
                    @if (auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('school-admin') || auth()->user()->hasRole('shop-owner'))
                    <a href="{{ LaravelLocalization::localizeUrl('/dashboard') }}" class="btn-global-style">Dashboard</a>
                    @else
                    <form method="POST" action="{{ LaravelLocalization::localizeUrl(route('logout', [], false)) }}">
                        @csrf
                        <button type="submit" class="btn-global-style">Logout</button>
                    </form>
                    @endif
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