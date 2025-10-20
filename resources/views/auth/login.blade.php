@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@endpush

@section('content')

<!-- ==================== LOGIN SECTION ==================== -->
<section class="login-section">
    <div class="container">
        <div class="login-container">
            <div class="login-left">
                <h2 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 1.5rem;">Welcome to SKOOLYST</h2>
                <p style="font-size: 1.2rem; margin-bottom: 2rem; opacity: 0.9;">
                    Find, compare, and connect with the perfect educational institutions for your needs.
                </p>
                <div style="margin-top: 2rem;">
                    <div style="display: flex; align-items: center; margin-bottom: 1.5rem;">
                        <i class="fas fa-search" style="font-size: 1.5rem; margin-right: 1rem;"></i>
                        <div>
                            <h4 style="margin-bottom: 0.3rem;">Search & Filter</h4>
                            <p style="opacity: 0.8;">Find schools by location, type, curriculum, and more</p>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; margin-bottom: 1.5rem;">
                        <i class="fas fa-list" style="font-size: 1.5rem; margin-right: 1rem;"></i>
                        <div>
                            <h4 style="margin-bottom: 0.3rem;">Browse Profiles</h4>
                            <p style="opacity: 0.8;">Explore detailed school profiles with ratings and reviews</p>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center;">
                        <i class="fas fa-handshake" style="font-size: 1.5rem; margin-right: 1rem;"></i>
                        <div>
                            <h4 style="margin-bottom: 0.3rem;">Connect Directly</h4>
                            <p style="opacity: 0.8;">Reach out to schools for inquiries and admission details</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="login-right">
                <h2 class="login-title">Login to Your Account</h2>
                <p class="login-subtitle">Welcome back! Please enter your details</p>

                <!-- Session Status -->
                @if (session('status'))
                <div class="auth-session-status">
                    {{ session('status') }}
                </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Enter your email">
                        @if ($errors->has('email'))
                        <div class="input-error">
                            {{ $errors->first('email') }}
                        </div>
                        @endif
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" placeholder="Enter your password">
                        @if ($errors->has('password'))
                        <div class="input-error">
                            {{ $errors->first('password') }}
                        </div>
                        @endif
                    </div>

                    <!-- Remember Me -->
                    <div class="form-check">
                        <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                        <label for="remember_me" class="form-check-label">Remember me</label>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                        @if (Route::has('password.request'))
                        <a class="forgot-password" href="{{ route('password.request') }}">
                            Forgot your password?
                        </a>
                        @endif

                        <button type="submit" class="login-btn">
                            {{ __('Log in') }}
                        </button>
                    </div>
                </form>

                <div class="register-link">
                    Don't have an account? <a href="{{ route('register') }}">Register now</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection