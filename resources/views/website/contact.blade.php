@extends('website.layout.app')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">


@endpush
@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="mb-4">Contact Us</h1>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            <div class="row">
                <!-- Contact Form -->
                <div class="col-md-7">
                    <div class="card shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4">Send us a Message</h5>
                            <form method="POST" action="{{ route('contact.inquiry.store') }}">
                                @csrf
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Full Name *</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email Address *</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                               id="email" name="email" value="{{ old('email') }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone') }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="subject" class="form-label">Subject *</label>
                                    <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                                           id="subject" name="subject" value="{{ old('subject') }}" required>
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="message" class="form-label">Message *</label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" 
                                              id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Send Message</button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Contact Info -->
                <div class="col-md-5">
                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4">Contact Information</h5>
                            
                            <div class="mb-4">
                                <h6><i class="fas fa-envelope text-primary me-2"></i> Email</h6>
                                <p class="mb-0">contact@skoolyst.com</p>
                                <p>support@skoolyst.com</p>
                            </div>
                            
                            <div class="mb-4">
                                <h6><i class="fas fa-phone text-primary me-2"></i> Phone</h6>
                                <p class="mb-0">+92 334 0673401</p>
                                <p>Mon-Fri, 9:00 AM - 6:00 PM</p>
                            </div>
                            
                            <div class="mb-4">
                                <h6><i class="fas fa-map-marker-alt text-primary me-2"></i> Address</h6>
                                <p class="mb-0">SKOOLYST Pakistan</p>
                                <p class="mb-0">Gulzar-e-Hijri</p>
                                <p>Karachi, Pakistan</p>
                            </div>
                            
                            <div class="mb-4">
                                <h6><i class="fas fa-clock text-primary me-2"></i> Business Hours</h6>
                                <p class="mb-0">Monday - Friday: 9:00 AM - 6:00 PM</p>
                                <p class="mb-0">Saturday: 10:00 AM - 4:00 PM</p>
                                <p>Sunday: Closed</p>
                            </div>
                            
                            <div>
                                <h6><i class="fas fa-share-alt text-primary me-2"></i> Follow Us</h6>
                                <div class="d-flex mt-2">
                                    <a href="https://facebook.com/skoolystpk" class="me-3 text-dark">
                                        <i class="fab fa-facebook fa-lg"></i>
                                    </a>
                                    <a href="https://twitter.com/skoolystpk" class="me-3 text-dark">
                                        <i class="fab fa-twitter fa-lg"></i>
                                    </a>
                                    <a href="https://instagram.com/skoolystpk" class="me-3 text-dark">
                                        <i class="fab fa-instagram fa-lg"></i>
                                    </a>
                                    <a href="https://linkedin.com/company/skoolyst" class="text-dark">
                                        <i class="fab fa-linkedin fa-lg"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- FAQ Section -->
            <div class="mt-5">
                <h4 class="mb-4">Frequently Asked Questions</h4>
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                How can I list my school on SKOOLYST?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Schools can register through our school registration portal. Visit the "For Schools" section or contact our partnership team for assistance.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                How do I update school information?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                School administrators can log in to their dashboard to update information. For urgent updates, contact our support team.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                Are the school reviews verified?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Yes, we verify reviews through multiple methods to ensure authenticity and prevent spam.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Simple form validation enhancement
    document.addEventListener('DOMContentLoaded', function() {
        const contactForm = document.querySelector('form');
        if (contactForm) {
            contactForm.addEventListener('submit', function(e) {
                const phone = document.getElementById('phone').value;
                const email = document.getElementById('email').value;
                
                // Basic email validation
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    e.preventDefault();
                    alert('Please enter a valid email address.');
                    return false;
                }
                
                // Optional phone validation
                if (phone && !/^[\d\s\-\+\(\)]{10,}$/.test(phone)) {
                    e.preventDefault();
                    alert('Please enter a valid phone number (at least 10 digits).');
                    return false;
                }
                
                return true;
            });
        }
    });
</script>
@endsection