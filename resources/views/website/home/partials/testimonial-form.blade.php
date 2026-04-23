<!-- Testimonial Form Section - Compact two-column layout -->
<div class="testimonial-form-section">
    <div class="testimonial-form-inner row g-4 align-items-start">
        <div class="testimonial-form-info col-lg-6 col-md-12">
            <h3 class="form-title">Share Your Experience</h3>
            <p class="form-subtitle">Help other parents by sharing your feedback about our platform.</p>
            <div class="form-info-content">
                <p><strong>About SKOOLYST</strong></p>
                <p>SKOOLYST helps parents find and compare schools with detailed profiles, reviews, and ratings-all in one place.</p>
                <p><strong>Why share your experience?</strong></p>
                <ul>
                    <li>Your feedback helps other families make informed decisions</li>
                    <li>Schools value real parent experiences to improve</li>
                    <li>It only takes a minute and makes a difference</li>
                </ul>
                <p class="form-cta-text">Please take a moment to fill the form and tell us how SKOOLYST worked for you.</p>
            </div>
        </div>
        <div class="testimonial-form-fields col-lg-6 col-md-12">
            <form id="testimonialForm" class="testimonial-form" data-submit-url="{{ route('testimonials.store') }}">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="author_name">Your Name <span style="color: red">*</span></label>
                            <input
                                type="text"
                                id="author_name"
                                name="author_name"
                                class="form-control"
                                placeholder="Enter your name"
                                required
                            >
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="author_email">Email</label>
                            <input
                                type="email"
                                id="author_email"
                                name="author_email"
                                class="form-control"
                                placeholder="Enter your email (optional)"
                            >
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="author_location">Your Location</label>
                            <input
                                type="text"
                                id="author_location"
                                name="author_location"
                                class="form-control"
                                placeholder="e.g., Karachi, Lahore"
                            >
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="experience_rating">Overall Experience <span style="color: red">*</span></label>
                            <select
                                id="experience_rating"
                                name="experience_rating"
                                class="form-control"
                                required
                            >
                                <option value="">Select your experience</option>
                                <option value="excellent">Excellent</option>
                                <option value="good">Good</option>
                                <option value="average">Average</option>
                                <option value="poor">Poor</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>What did you like most? (Select all that apply)</label>
                    <div class="platform-features">
                        <div class="form-check">
                            <input type="checkbox" name="platform_features[]" value="School Search" id="feature1">
                            <label for="feature1">Find School</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="platform_features[]" value="School Comparison" id="feature2">
                            <label for="feature2">Comparison</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="platform_features[]" value="Detailed Profiles" id="feature3">
                            <label for="feature3">School Profile</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="platform_features[]" value="Reviews & Ratings" id="feature4">
                            <label for="feature4">Reviews</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="platform_features[]" value="User Interface" id="feature5">
                            <label for="feature5">App easy to use</label>
                        </div>
                    </div>
                </div>

                <div class="row g-3 align-items-start">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Rate Your Experience <span style="color: red">*</span></label>
                            <div class="star-rating">
                                <input type="radio" id="star5" name="rating" value="5" required>
                                <label for="star5" title="Excellent">☆</label>
                                <input type="radio" id="star4" name="rating" value="4">
                                <label for="star4" title="Good">☆</label>
                                <input type="radio" id="star3" name="rating" value="3">
                                <label for="star3" title="Average">☆</label>
                                <input type="radio" id="star2" name="rating" value="2">
                                <label for="star2" title="Poor">☆</label>
                                <input type="radio" id="star1" name="rating" value="1">
                                <label for="star1" title="Very Poor">☆</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="form-group">
                            <label for="message">Description <span style="color: red">*</span></label>
                            <textarea
                                id="message"
                                name="message"
                                class="form-control"
                                rows="3"
                                placeholder="Share your experience with SKOOLYST platform..."
                                minlength="20"
                                maxlength="1000"
                                required
                            ></textarea>
                            <small class="text-muted">Minimum 20 characters</small>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-submit">
                    <span class="submit-text">Submit Feedback</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                </button>

                <div class="alert mt-3 d-none" id="formMessage"></div>
            </form>
        </div>
    </div>
</div>
