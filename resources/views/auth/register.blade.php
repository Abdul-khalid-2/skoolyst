@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/registration.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@endpush

@section('content')

<!-- ==================== REGISTER SECTION ==================== -->
<section class="register-section">
    <div class="container">
        <div class="register-container">
            <div class="register-left">
                <h2 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 1.5rem;">Join SKOOLYST Today</h2>
                <p style="font-size: 1.2rem; margin-bottom: 2rem; opacity: 0.9;">
                    Discover the perfect educational institutions for your needs.
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
            <div class="register-right">
                <h2 class="register-title">Create Your Account</h2>
                <p class="register-subtitle">Choose your account type and join SKOOLYST</p>

                <!-- Registration Type Selector -->
                <div class="registration-type">
                    <div class="type-btn active" data-type="user">
                        <div class="type-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <h4>Parent/Student</h4>
                        <p style="font-size: 0.9rem; opacity: 0.8;">Find and compare schools</p>
                    </div>
                    <div class="type-btn" data-type="school">
                        <div class="type-icon">
                            <i class="fas fa-school"></i>
                        </div>
                        <h4>School</h4>
                        <p style="font-size: 0.9rem; opacity: 0.8;">Register your institution</p>
                    </div>
                </div>

                <!-- Session Status -->
                @if (session('status'))
                <div class="auth-session-status">
                    {{ session('status') }}
                </div>
                @endif

                <!-- User Registration Form -->
                <div id="userRegistration">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group">
                            <label for="name" class="form-label">Full Name</label>
                            <input id="name" class="form-control" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Enter your full name">
                            @if ($errors->has('name'))
                            <div class="input-error">
                                {{ $errors->first('name') }}
                            </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">Email Address</label>
                            <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Enter your email">
                            @if ($errors->has('email'))
                            <div class="input-error">
                                {{ $errors->first('email') }}
                            </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="password" class="form-label">Password</label>
                            <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password" placeholder="Create a password">
                            @if ($errors->has('password'))
                            <div class="input-error">
                                {{ $errors->first('password') }}
                            </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm your password">
                        </div>

                        <div class="form-check">
                            <input id="agree_terms" type="checkbox" class="form-check-input" name="agree_terms" required>
                            <label for="agree_terms" class="form-check-label">
                                I agree to the <a href="{{ route('website.terms') }}" style="color: #4361ee;">Terms of Service</a> and <a href="{{ route('website.privacy') }}" style="color: #4361ee;">Privacy Policy</a>
                            </label>
                        </div>

                        <button type="submit" class="register-btn">
                            Create Account
                        </button>
                    </form>

                    <div class="login-link">
                        Already have an account? <a href="{{ route('login') }}">Login now</a>
                    </div>
                </div>

                <!-- School Registration Form -->
                <div id="schoolRegistration" class="school-registration">
                    <form method="POST" action="{{ route('school.register') }}">
                        @csrf

                        <!-- Step 1: School Basic Information -->
                        <div class="form-step active" data-step="1">
                            <h3 style="margin-bottom: 1.5rem; color: #1a1a1a;">School Information</h3>

                            <div class="form-group">
                                <label for="school_name" class="form-label">School Name *</label>
                                <input id="school_name" class="form-control" type="text" name="school_name" value="{{ old('school_name') }}" required placeholder="Enter school name">
                                @if ($errors->has('school_name'))
                                <div class="input-error">
                                    {{ $errors->first('school_name') }}
                                </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="school_email" class="form-label">School Email *</label>
                                <input id="school_email" class="form-control" type="email" name="school_email" value="{{ old('school_email') }}" required placeholder="Enter school email">
                                @if ($errors->has('school_email'))
                                <div class="input-error">
                                    {{ $errors->first('school_email') }}
                                </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="school_address" class="form-label">Address *</label>
                                <input id="school_address" class="form-control" type="text" name="school_address" value="{{ old('school_address') }}" required placeholder="Enter school address">
                                @if ($errors->has('school_address'))
                                <div class="input-error">
                                    {{ $errors->first('school_address') }}
                                </div>
                                @endif
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="school_city" class="form-label">City *</label>
                                        <!-- <input class="form-control" type="text" name="school_city" value="{{ old('school_city') }}" required placeholder="Enter city"> -->
                                        <select id="school_city" class="form-control" name="school_city" required>
                                            <option value="">Select City</option>
                                            <option value="Karachi">Karachi</option>
                                            <option value="Hyderabad">Hyderabad</option>
                                        </select>
                                        @if ($errors->has('school_city'))
                                        <div class="input-error">
                                            {{ $errors->first('school_city') }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="school_contact" class="form-label">Contact Number</label>
                                        <input id="school_contact" class="form-control" type="tel" name="school_contact" value="{{ old('school_contact') }}" placeholder="Enter contact number">
                                        @if ($errors->has('school_contact'))
                                        <div class="input-error">
                                            {{ $errors->first('school_contact') }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="form-navigation">
                                <button type="button" class="btn-prev" disabled>Previous</button>
                                <button type="button" class="btn-next" data-next="2">Next</button>
                            </div>
                        </div>

                        <!-- Step 2: School Details -->
                        <div class="form-step" data-step="2">
                            <h3 style="margin-bottom: 1.5rem; color: #1a1a1a;">School Details</h3>

                            <div class="form-group">
                                <label for="school_description" class="form-label">School Description</label>
                                <textarea id="school_description" class="form-control" name="school_description" rows="4" placeholder="Describe your school">{{ old('school_description') }}</textarea>
                                @if ($errors->has('school_description'))
                                <div class="input-error">
                                    {{ $errors->first('school_description') }}
                                </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="school_facilities" class="form-label">Facilities</label>
                                <textarea id="school_facilities" class="form-control" name="school_facilities" rows="3" placeholder="e.g., Library, Sports Ground, Science Lab, Computer Lab">{{ old('school_facilities') }}</textarea>
                                @if ($errors->has('school_facilities'))
                                <div class="input-error">
                                    {{ $errors->first('school_facilities') }}
                                </div>
                                @endif
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="school_type" class="form-label">School Type *</label>
                                        <select id="school_type" class="form-control" name="school_type" required>
                                            <option value="">Select Type</option>
                                            <option value="Co-Ed" {{ old('school_type') == 'Co-Ed' ? 'selected' : '' }}>Co-Educational</option>
                                            <option value="Boys" {{ old('school_type') == 'Boys' ? 'selected' : '' }}>Boys Only</option>
                                            <option value="Girls" {{ old('school_type') == 'Girls' ? 'selected' : '' }}>Girls Only</option>
                                        </select>
                                        @if ($errors->has('school_type'))
                                        <div class="input-error">
                                            {{ $errors->first('school_type') }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="school_website" class="form-label">Website</label>
                                        <input id="school_website" class="form-control" type="url" name="school_website" value="{{ old('school_website') }}" placeholder="https://">
                                        @if ($errors->has('school_website'))
                                        <div class="input-error">
                                            {{ $errors->first('school_website') }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="form-navigation">
                                <button type="button" class="btn-prev" data-prev="1">Previous</button>
                                <button type="button" class="btn-next" data-next="3">Next</button>
                            </div>
                        </div>

                        <!-- Step 3: Admin Account & Fees -->
                        <div class="form-step" data-step="3">
                            <h3 style="margin-bottom: 1.5rem; color: #1a1a1a;">Admin Account & Fees</h3>

                            <div class="form-group">
                                <label for="admin_name" class="form-label">Admin Name *</label>
                                <input id="admin_name" class="form-control" type="text" name="admin_name" value="{{ old('admin_name') }}" required placeholder="Enter admin full name">
                                @if ($errors->has('admin_name'))
                                <div class="input-error">
                                    {{ $errors->first('admin_name') }}
                                </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="admin_email" class="form-label">Admin Email *</label>
                                <input id="admin_email" class="form-control" type="email" name="admin_email" value="{{ old('admin_email') }}" required placeholder="Enter admin email">
                                @if ($errors->has('admin_email'))
                                <div class="input-error">
                                    {{ $errors->first('admin_email') }}
                                </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="admin_password" class="form-label">Admin Password *</label>
                                <input id="admin_password" class="form-control" type="password" name="admin_password" required placeholder="Create admin password">
                                @if ($errors->has('admin_password'))
                                <div class="input-error">
                                    {{ $errors->first('admin_password') }}
                                </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="admin_password_confirmation" class="form-label">Confirm Admin Password *</label>
                                <input id="admin_password_confirmation" class="form-control" type="password" name="admin_password_confirmation" required placeholder="Confirm admin password">
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="regular_fees" class="form-label">Regular Fees</label>
                                        <input id="regular_fees" class="form-control" type="number" name="regular_fees" value="{{ old('regular_fees') }}" step="0.01" placeholder="0.00">
                                        @if ($errors->has('regular_fees'))
                                        <div class="input-error">
                                            {{ $errors->first('regular_fees') }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="discounted_fees" class="form-label">Discounted Fees</label>
                                        <input id="discounted_fees" class="form-control" type="number" name="discounted_fees" value="{{ old('discounted_fees') }}" step="0.01" placeholder="0.00">
                                        @if ($errors->has('discounted_fees'))
                                        <div class="input-error">
                                            {{ $errors->first('discounted_fees') }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="admission_fees" class="form-label">Admission Fees</label>
                                        <input id="admission_fees" class="form-control" type="number" name="admission_fees" value="{{ old('admission_fees') }}" step="0.01" placeholder="0.00">
                                        @if ($errors->has('admission_fees'))
                                        <div class="input-error">
                                            {{ $errors->first('admission_fees') }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="form-check">
                                <input id="school_terms" type="checkbox" class="form-check-input" name="school_terms" required>
                                <label for="school_terms" class="form-check-label">
                                    I confirm that all information provided is accurate and I have the authority to register this school
                                </label>
                                @if ($errors->has('school_terms'))
                                <div class="input-error">
                                    {{ $errors->first('school_terms') }}
                                </div>
                                @endif
                            </div>

                            <div class="form-navigation">
                                <button type="button" class="btn-prev" data-prev="2">Previous</button>
                                <button type="submit" class="btn-next">Complete Registration</button>
                            </div>
                        </div>
                    </form>

                    <div class="login-link" style="margin-top: 2rem;">
                        Already have an account? <a href="{{ route('login') }}">Login now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script src="{{ asset('assets/js/register.js') }}"></script>
@endpush
@endsection