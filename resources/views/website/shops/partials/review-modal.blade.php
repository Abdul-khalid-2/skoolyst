<div id="shopReviewModal" class="shop-review-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Write a Review</h3>
            <button type="button" class="close-modal" aria-label="Close">&times;</button>
        </div>
        <div class="modal-body">
            @auth
                @if($userHasReviewed ?? false)
                    <div class="shop-review-already">
                        <i class="fas fa-check-circle"></i>
                        <h4>Thank you!</h4>
                        <p>You have already submitted a review for this shop.</p>
                        <button type="button" class="btn-cancel-review" id="closeShopReviewAlready">Close</button>
                    </div>
                @else
                    <form id="shopReviewForm" action="{{ route('website.shop.reviews.store', $shop->uuid) }}" method="POST">
                        @csrf
                        <div class="star-rating-container">
                            <label class="star-rating-label">Your Rating *</label>
                            <div class="star-rating">
                                <input type="radio" id="shopStar5" name="rating" value="5">
                                <label for="shopStar5" class="star-label"><i class="fas fa-star"></i></label>
                                <input type="radio" id="shopStar4" name="rating" value="4">
                                <label for="shopStar4" class="star-label"><i class="fas fa-star"></i></label>
                                <input type="radio" id="shopStar3" name="rating" value="3">
                                <label for="shopStar3" class="star-label"><i class="fas fa-star"></i></label>
                                <input type="radio" id="shopStar2" name="rating" value="2">
                                <label for="shopStar2" class="star-label"><i class="fas fa-star"></i></label>
                                <input type="radio" id="shopStar1" name="rating" value="1">
                                <label for="shopStar1" class="star-label"><i class="fas fa-star"></i></label>
                            </div>
                            <div class="rating-text">
                                <span id="shopRatingText">Select your rating</span>
                            </div>
                        </div>

                        <div class="review-form-group">
                            <label for="shopReviewText">Your Review *</label>
                            <textarea name="review" id="shopReviewText" rows="5" placeholder="Share your experience shopping at {{ $shop->name }}..." required minlength="10" maxlength="1000"></textarea>
                        </div>

                        <div class="modal-form-actions">
                            <button type="button" class="btn-cancel-review" id="cancelShopReview">Cancel</button>
                            <button type="submit" class="btn-submit-review">Submit Review</button>
                        </div>
                    </form>
                @endif
            @else
                <div class="login-required">
                    <div class="login-icon">
                        <i class="fas fa-user-lock"></i>
                    </div>
                    <h4>Login Required</h4>
                    <p>Please login to submit your review and help other customers choose the right shop.</p>
                    <div class="auth-buttons">
                        <a href="{{ route('login') }}" class="btn-login-modal">Login</a>
                        <a href="{{ route('register') }}" class="btn-register-modal">Register</a>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</div>
