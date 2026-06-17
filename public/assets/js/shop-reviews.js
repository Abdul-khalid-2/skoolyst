/**
 * Shop public page: review modal submit (AJAX).
 */
(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        var reviewModal = document.getElementById('shopReviewModal');
        var writeReviewBtn = document.getElementById('writeShopReviewBtn');
        var closeModalBtn = reviewModal ? reviewModal.querySelector('.close-modal') : null;
        var cancelReviewBtn = document.getElementById('cancelShopReview');
        var closeAlreadyBtn = document.getElementById('closeShopReviewAlready');
        var starInputs = reviewModal ? reviewModal.querySelectorAll('.star-rating input') : [];
        var ratingText = document.getElementById('shopRatingText');

        var ratingDescriptions = {
            1: 'Poor - Very dissatisfied',
            2: 'Fair - Could be better',
            3: 'Good - Met expectations',
            4: 'Very Good - Exceeded expectations',
            5: 'Excellent - Far beyond expectations',
        };

        function closeReviewModal() {
            if (reviewModal) {
                reviewModal.style.display = 'none';
            }
            document.body.style.overflow = 'auto';
        }

        function openReviewModal() {
            if (reviewModal) {
                reviewModal.style.display = 'block';
                document.body.style.overflow = 'hidden';
            }
        }

        if (writeReviewBtn) {
            writeReviewBtn.addEventListener('click', openReviewModal);
        }
        if (closeModalBtn) {
            closeModalBtn.addEventListener('click', closeReviewModal);
        }
        if (cancelReviewBtn) {
            cancelReviewBtn.addEventListener('click', closeReviewModal);
        }
        if (closeAlreadyBtn) {
            closeAlreadyBtn.addEventListener('click', closeReviewModal);
        }
        window.addEventListener('click', function (event) {
            if (event.target === reviewModal) {
                closeReviewModal();
            }
        });

        starInputs.forEach(function (input) {
            input.addEventListener('change', function () {
                if (ratingText) {
                    ratingText.textContent = ratingDescriptions[this.value] || 'Select your rating';
                }
            });
        });

        var reviewForm = document.getElementById('shopReviewForm');
        if (reviewForm) {
            reviewForm.addEventListener('submit', function (e) {
                e.preventDefault();

                var rating = reviewForm.querySelector('input[name="rating"]:checked');
                var reviewField = document.getElementById('shopReviewText');
                var reviewText = reviewField ? reviewField.value.trim() : '';

                if (!rating) {
                    window.alert('Please select a rating');
                    return;
                }
                if (reviewText.length < 10) {
                    window.alert('Please write at least 10 characters in your review');
                    return;
                }

                var submitBtn = reviewForm.querySelector('.btn-submit-review');
                var originalText = submitBtn ? submitBtn.innerHTML : '';
                if (submitBtn) {
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
                    submitBtn.disabled = true;
                    submitBtn.classList.add('loading');
                }

                fetch(reviewForm.action, {
                    method: 'POST',
                    body: new FormData(reviewForm),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    },
                })
                    .then(function (response) {
                        return response.json().then(function (data) {
                            return { ok: response.ok, data: data };
                        });
                    })
                    .then(function (result) {
                        if (result.ok && result.data.success) {
                            window.alert('Review submitted successfully!');
                            closeReviewModal();
                            window.location.hash = 'reviews';
                            window.location.reload();
                            return;
                        }

                        window.alert('Error: ' + (result.data.message || 'Failed to submit review'));
                        if (submitBtn) {
                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;
                            submitBtn.classList.remove('loading');
                        }
                    })
                    .catch(function (err) {
                        console.error('Error:', err);
                        window.alert('An error occurred while submitting your review');
                        if (submitBtn) {
                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;
                            submitBtn.classList.remove('loading');
                        }
                    });
            });
        }
    });
})();
