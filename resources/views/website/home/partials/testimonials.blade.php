<!-- ==================== TESTIMONIALS SECTION ==================== -->
<section class="testimonials-section" id="testimonials">
    <div class="container">
        <h2 class="section-title"> WHAT PARENTS SAY </h2>
        <p class="section-subtitle">Real Experiences From Families Who Found Their Perfect School</p>

        @if($testimonials->count() > 2)
            <div class="row mt-5">
                @foreach($testimonials as $testimonial)
                <div class="col-lg-4 col-md-6">
                    <div class="testimonial-card">
                        <div class="testimonial-rating mb-3">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $testimonial->rating)
                                    <i class="fas fa-star text-warning"></i>
                                @else
                                    <i class="far fa-star text-muted"></i>
                                @endif
                            @endfor
                        </div>
                        <p class="testimonial-text">
                            "{{ $testimonial->message }}"
                        </p>
                        <div class="testimonial-author">
                            @if($testimonial->avatar)
                                <img src="{{ asset($testimonial->avatar) }}" alt="{{ $testimonial->author_name }}" class="author-avatar">
                            @else
                                <div class="author-avatar">{{ $testimonial->initials ?? substr($testimonial->author_name, 0, 1) }}</div>
                            @endif
                            <div class="author-info">
                                <div class="author-name">{{ $testimonial->author_name }}</div>
                                <div class="author-role">{{ $testimonial->author_role ?? '' }}, {{ $testimonial->author_location ?? '' }}</div>
                                <div class="author-experience">
                                    <small class="text-muted">{{ $testimonial->experience_rating ?? '' }} {{ isset($testimonial->created_at) ? '• ' . $testimonial->created_at->format('M Y') : '' }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="row mt-5">
                <div class="col-lg-4 col-md-6">
                    <div class="testimonial-card">
                        <p class="testimonial-text">
                            "SKOOLYST made finding the right school for my daughter so easy! The detailed profiles and honest reviews helped us make the perfect choice."
                        </p>
                        <div class="testimonial-author">
                            <div class="author-avatar">P</div>
                            <div class="author-info">
                                <div class="author-name">Fatima Malik</div>
                                <div class="author-role">Parent, Karachi</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="testimonial-card">
                        <p class="testimonial-text">
                            "The comparison feature is brilliant! We could evaluate multiple schools based on our priorities and found an excellent match within days."
                        </p>
                        <div class="testimonial-author">
                            <div class="author-avatar">R</div>
                            <div class="author-info">
                                <div class="author-name">Umer Khan</div>
                                <div class="author-role">Parent, Hyderabad</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="testimonial-card">
                        <p class="testimonial-text">
                            "As a working parent, I appreciated how quickly I could research schools online. The platform is user-friendly and comprehensive."
                        </p>
                        <div class="testimonial-author">
                            <div class="author-avatar">A</div>
                            <div class="author-info">
                                <div class="author-name">Ayesha Ahmed</div>
                                <div class="author-role">Parent, Karachi</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="text-center mt-4">
            <a href="{{ route('testimonials.index') }}" class="btn-site btn-site-outline">
                View All Testimonials <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>
