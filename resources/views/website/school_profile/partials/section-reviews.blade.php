<section id="reviews" class="content-section">
    <h2 class="section-title">Parent &amp; Student Reviews</h2>
    <div class="section-content">
        @if($school->reviews && $school->reviews->count() > 0)
            <div class="reviews-list">
                @foreach($school->reviews as $review)
                    <div class="review-item">
                        <div class="review-header">
                            <div class="reviewer-info">
                                <span class="reviewer-name">
                                    {{ $review->user_id ? $review->user->name : $review->reviewer_name }}
                                    @if($review->user_id)
                                        <span class="verified-badge">✓ Verified</span>
                                    @endif
                                </span>
                                <div class="review-rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                            <span class="review-date">{{ $review->created_at->format('M d, Y') }}</span>
                        </div>
                        <p class="review-content">{{ $review->review }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <p class="no-content">No reviews yet. Be the first to review!</p>
        @endif
    </div>
</section>
