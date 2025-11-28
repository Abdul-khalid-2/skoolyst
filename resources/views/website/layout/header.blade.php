<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">SKOOLYST</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ route('website.home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('how-it-works') ? 'active' : '' }}" href="{{ route('website.how_it_works') }}">How It Works</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('directory') ? 'active' : '' }}" href="{{ route('browseSchools.index') }}">Schools</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('about') ? 'active' : '' }}" href="{{ url('/about') }}">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('blog') ? 'active' : '' }}" href="{{ url('/blog') }}">Blog</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('shop') ? 'active' : '' }}" href="{{ route('website.shop.index') }}">Shop</a>
                </li>
            </ul>

            <div class="navbar-actions d-flex align-items-center gap-3">
                <!-- Cart Icon with Count -->
                <a href="{{ route('website.cart') }}" class="cart-icon position-relative text-decoration-none">
                    <i class="fas fa-shopping-cart fa-lg" style="color: #4361ee;"></i>
                    @php
                        $cartCount = 0;
                        if(session()->has('cart')) {
                            $cart = session('cart');
                            $cartCount = array_sum(array_column($cart, 'quantity'));
                        }
                    @endphp
                    @if($cartCount > 0)
                        <span class="cart-badge position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ $cartCount > 99 ? '99+' : $cartCount }}
                        </span>
                    @endif
                </a>

                <!-- Auth Buttons -->
                <div class="auth-buttons d-flex gap-2">
                    @if (Route::has('login'))
                        @auth
                            @if (auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('school-admin') || auth()->user()->hasRole('shop-owner'))
                                <a href="{{ url('/dashboard') }}" class="btn-global-style">Dashboard</a>
                            @else
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-global-style">Logout</button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-login">Login</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-primary btn-register">Register</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </div>
</nav>