<div id="reviewModal" class="review-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Write a Review</h3>
            <button type="button" class="close-modal" aria-label="Close">&times;</button>
        </div>
        <div class="modal-body">
            @auth
                <form id="reviewForm" action="{{ route('reviews.store', $school->id) }}" method="POST">
                    @csrf
                    <div class="star-rating-container">
                        <label class="star-rating-label">Your Rating *</label>
                        <div class="star-rating">
                            <input type="radio" id="star5" name="rating" value="5">
                            <label for="star5" class="star-label"><i class="fas fa-star"></i></label>
                            <input type="radio" id="star4" name="rating" value="4">
                            <label for="star4" class="star-label"><i class="fas fa-star"></i></label>
                            <input type="radio" id="star3" name="rating" value="3">
                            <label for="star3" class="star-label"><i class="fas fa-star"></i></label>
                            <input type="radio" id="star2" name="rating" value="2">
                            <label for="star2" class="star-label"><i class="fas fa-star"></i></label>
                            <input type="radio" id="star1" name="rating" value="1">
                            <label for="star1" class="star-label"><i class="fas fa-star"></i></label>
                        </div>
                        <div class="rating-text">
                            <span id="ratingText">Select your rating</span>
                        </div>
                    </div>

                    <div class="review-form-group">
                        <label for="review">Your Review *</label>
                        <textarea name="review" id="review" rows="5" placeholder="Share your experience with this school..." required></textarea>
                    </div>

                    <div class="modal-form-actions">
                        <button type="button" class="btn-cancel-review" id="cancelReview">Cancel</button>
                        <button type="submit" class="btn-submit-review">Submit Review</button>
                    </div>
                </form>
            @else
                <div class="login-required">
                    <div class="login-icon">
                        <i class="fas fa-user-lock"></i>
                    </div>
                    <h4>Login Required</h4>
                    <p>Please login to submit your review and help other parents make informed decisions.</p>
                    <div class="auth-buttons">
                        <a href="{{ route('login') }}" class="btn-login-modal">Login</a>
                        <a href="{{ route('register') }}" class="btn-register-modal">Register</a>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</div>
