{{-- Share Your Experience — responsive footer form --}}
<div class="testimonial-form-section">
    <div class="testimonial-form-section__glow" aria-hidden="true"></div>

    <div class="testimonial-form-hero text-center text-lg-start mb-4 mb-lg-4">
        <div class="d-inline-flex align-items-center justify-content-center justify-content-lg-start gap-2 flex-wrap">
            <span class="testimonial-form-badge">
                <i class="fas fa-message" aria-hidden="true"></i>
                We read every message
            </span>
        </div>
        <h2 class="testimonial-form-hero__title h3 mb-2">Share your experience</h2>
        <p class="testimonial-form-hero__lede mb-0">
            Your feedback helps other families choose the right school and helps us improve SKOOLYST.
        </p>
    </div>

    <div class="row g-4 g-lg-5 align-items-stretch testimonial-form-inner">
        <div class="col-12 col-lg-5 d-flex">
            <div class="testimonial-form-aside w-100">
                <h3 class="h6 text-uppercase testimonial-form-kicker text-white-50 mb-3">Why it matters</h3>
                <ul class="testimonial-form-benefits list-unstyled mb-0">
                    <li class="testimonial-form-benefits__item">
                        <span class="testimonial-form-benefits__icon" aria-hidden="true">
                            <i class="fas fa-people-group"></i>
                        </span>
                        <div>
                            <strong class="d-block">Help parents decide</strong>
                            <span class="text-white-50 small">Real experiences beat brochures.</span>
                        </div>
                    </li>
                    <li class="testimonial-form-benefits__item">
                        <span class="testimonial-form-benefits__icon" aria-hidden="true">
                            <i class="fas fa-school"></i>
                        </span>
                        <div>
                            <strong class="d-block">Support better schools</strong>
                            <span class="text-white-50 small">Schools use feedback to understand families.</span>
                        </div>
                    </li>
                    <li class="testimonial-form-benefits__item">
                        <span class="testimonial-form-benefits__icon" aria-hidden="true">
                            <i class="fas fa-stopwatch"></i>
                        </span>
                        <div>
                            <strong class="d-block">Quick &amp; easy</strong>
                            <span class="text-white-50 small">A minute of your time makes a real difference.</span>
                        </div>
                    </li>
                </ul>
                <p class="testimonial-form-aside__footnote small text-white-50 mb-0 mt-4">
                    <i class="fas fa-circle-info me-1 opacity-75" aria-hidden="true"></i>
                    SKOOLYST helps you find and compare schools with profiles, reviews, and ratings in one place.
                </p>
            </div>
        </div>

        <div class="col-12 col-lg-7 d-flex">
            <div class="testimonial-form-card w-100">
                <form id="testimonialForm" class="testimonial-form" data-submit-url="{{ route('testimonials.store') }}">
                    @csrf

                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                            <div class="form-floating">
                                <input
                                    type="text"
                                    id="author_name"
                                    name="author_name"
                                    class="form-control"
                                    placeholder="Your name"
                                    minlength="2"
                                    maxlength="120"
                                    required
                                >
                                <label for="author_name">Your name <span class="form-required" aria-label="required">*</span></label>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-floating">
                                <input
                                    type="email"
                                    id="author_email"
                                    name="author_email"
                                    class="form-control"
                                    placeholder="you@email.com"
                                    inputmode="email"
                                    autocomplete="email"
                                >
                                <label for="author_email">Email <span class="text-muted small fw-normal">(optional)</span></label>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mt-0">
                        <div class="col-12 col-sm-6">
                            <div class="form-floating">
                                <input
                                    type="text"
                                    id="author_location"
                                    name="author_location"
                                    class="form-control"
                                    placeholder="City"
                                    maxlength="100"
                                >
                                <label for="author_location">Location</label>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label for="experience_rating" class="form-label testimonial-form-label">Overall experience <span class="form-required" aria-label="required">*</span></label>
                            <select id="experience_rating" name="experience_rating" class="form-select" required>
                                <option value="" selected disabled>Choose one…</option>
                                <option value="excellent">Excellent</option>
                                <option value="good">Good</option>
                                <option value="average">Average</option>
                                <option value="poor">Poor</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-3">
                        <p class="form-label testimonial-form-label mb-2">What did you like? <span class="text-muted small fw-normal">(select all that apply)</span></p>
                        <div class="row row-cols-1 row-cols-sm-2 g-2 platform-features" role="group" aria-label="Platform features you liked">
                            <div class="col">
                                <div class="form-check platform-feature-chip">
                                    <input class="form-check-input" type="checkbox" name="platform_features[]" value="School Search" id="feature1">
                                    <label class="form-check-label" for="feature1">Find school</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-check platform-feature-chip">
                                    <input class="form-check-input" type="checkbox" name="platform_features[]" value="School Comparison" id="feature2">
                                    <label class="form-check-label" for="feature2">Comparison</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-check platform-feature-chip">
                                    <input class="form-check-input" type="checkbox" name="platform_features[]" value="Detailed Profiles" id="feature3">
                                    <label class="form-check-label" for="feature3">School profile</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-check platform-feature-chip">
                                    <input class="form-check-input" type="checkbox" name="platform_features[]" value="Reviews &amp; Ratings" id="feature4">
                                    <label class="form-check-label" for="feature4">Reviews &amp; ratings</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-check platform-feature-chip">
                                    <input class="form-check-input" type="checkbox" name="platform_features[]" value="User Interface" id="feature5">
                                    <label class="form-check-label" for="feature5">Easy to use</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mt-1 align-items-start">
                        <div class="col-12 col-md-5 col-xl-4">
                            <fieldset class="border-0 p-0 m-0">
                                <legend class="form-label testimonial-form-label w-auto float-none">Your rating <span class="form-required" aria-label="required">*</span></legend>
                                <div class="star-rating" role="radiogroup" aria-label="Star rating from 1 to 5">
                                    <input type="radio" id="star5" name="rating" value="5" required>
                                    <label for="star5" title="5 stars">★</label>
                                    <input type="radio" id="star4" name="rating" value="4">
                                    <label for="star4" title="4 stars">★</label>
                                    <input type="radio" id="star3" name="rating" value="3">
                                    <label for="star3" title="3 stars">★</label>
                                    <input type="radio" id="star2" name="rating" value="2">
                                    <label for="star2" title="2 stars">★</label>
                                    <input type="radio" id="star1" name="rating" value="1">
                                    <label for="star1" title="1 star">★</label>
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-12 col-md-7 col-xl-8">
                            <div class="form-floating">
                                <textarea
                                    id="message"
                                    name="message"
                                    class="form-control testimonial-textarea"
                                    style="min-height: 7rem"
                                    placeholder="Your feedback"
                                    minlength="20"
                                    maxlength="1000"
                                    required
                                ></textarea>
                                <label for="message">Your story <span class="form-required" aria-label="required">*</span></label>
                            </div>
                            <p class="form-hint text-muted small mb-0 mt-1" id="messageHelp">20–1,000 characters. Be honest; we may feature highlights on the site (with your permission later).</p>
                        </div>
                    </div>

                    <div class="d-grid d-sm-flex gap-2 align-items-stretch align-items-sm-center mt-4">
                        <button type="submit" class="btn btn-primary btn-lg px-4 flex-grow-1 flex-sm-grow-0 btn-submit">
                            <span class="submit-text">Submit feedback</span>
                            <span class="spinner-border spinner-border-sm d-none ms-2" role="status" aria-hidden="true"></span>
                        </button>
                        <p class="small text-muted mb-0 flex-grow-1 text-center text-sm-start align-self-center">
                            By submitting, you agree we may use your comments for public testimonials (anonymized if you prefer in future).
                        </p>
                    </div>

                    <div class="alert mt-3 mb-0 d-none" id="formMessage" role="alert" aria-live="polite"></div>
                </form>
            </div>
        </div>
    </div>
</div>
