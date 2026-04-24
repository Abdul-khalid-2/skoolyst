/**
 * School public profile: tabbed sections, horizontal nav scroll, review modal.
 */
(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // --- Tab / section visibility (in-page nav) ---
        const navLinkEls = document.querySelectorAll('.nav-link[data-tab]');
        const contentSections = document.querySelectorAll('.content-section');

        navLinkEls.forEach(function (link) {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                navLinkEls.forEach(function (nav) {
                    nav.classList.remove('active');
                });
                this.classList.add('active');
                const targetTab = this.getAttribute('data-tab');
                contentSections.forEach(function (section) {
                    if (section.id === targetTab) {
                        section.classList.add('active');
                    } else {
                        section.classList.remove('active');
                    }
                });
            });
        });

        // --- School nav horizontal scroll (left/right buttons) ---
        const schoolNavList = document.getElementById('schoolNavLinks');
        const scrollLeftBtn = document.querySelector('.nav-scroll-left');
        const scrollRightBtn = document.querySelector('.nav-scroll-right');
        var scrollAmount = 150;

        if (schoolNavList && scrollLeftBtn && scrollRightBtn) {
            function updateScrollButtons() {
                var atStart = schoolNavList.scrollLeft <= 10;
                var atEnd = schoolNavList.scrollLeft + schoolNavList.clientWidth >= schoolNavList.scrollWidth - 10;
                scrollLeftBtn.disabled = atStart;
                scrollRightBtn.disabled = atEnd;
            }

            updateScrollButtons();

            scrollLeftBtn.addEventListener('click', function () {
                schoolNavList.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
            });
            scrollRightBtn.addEventListener('click', function () {
                schoolNavList.scrollBy({ left: scrollAmount, behavior: 'smooth' });
            });
            schoolNavList.addEventListener('scroll', updateScrollButtons);
        }

        // --- Review modal (Write Review) ---
        var reviewModal = document.getElementById('reviewModal');
        var writeReviewBtn = document.getElementById('writeReviewBtn');
        var closeModalBtn = document.querySelector('.review-modal .close-modal');
        var cancelReviewBtn = document.getElementById('cancelReview');
        var starInputs = document.querySelectorAll('.star-rating input');
        var ratingText = document.getElementById('ratingText');

        var ratingDescriptions = {
            1: 'Poor - Very dissatisfied',
            2: 'Fair - Could be better',
            3: 'Good - Met expectations',
            4: 'Very Good - Exceeded expectations',
            5: 'Excellent - Far beyond expectations'
        };

        function closeReviewModal() {
            if (reviewModal) {
                reviewModal.style.display = 'none';
            }
            document.body.style.overflow = 'auto';
        }

        if (writeReviewBtn && reviewModal) {
            writeReviewBtn.addEventListener('click', function () {
                reviewModal.style.display = 'block';
                document.body.style.overflow = 'hidden';
            });
        }
        if (closeModalBtn) {
            closeModalBtn.addEventListener('click', closeReviewModal);
        }
        if (cancelReviewBtn) {
            cancelReviewBtn.addEventListener('click', closeReviewModal);
        }
        window.addEventListener('click', function (event) {
            if (event.target === reviewModal) {
                closeReviewModal();
            }
        });

        starInputs.forEach(function (input) {
            input.addEventListener('change', function () {
                var r = this.value;
                if (ratingText) {
                    ratingText.textContent = ratingDescriptions[r] || 'Select your rating';
                }
            });
        });

        var reviewForm = document.getElementById('reviewForm');
        if (reviewForm) {
            reviewForm.addEventListener('submit', function (e) {
                e.preventDefault();
                var rating = document.querySelector('input[name="rating"]:checked');
                var reviewField = document.getElementById('review');
                var reviewText = reviewField ? reviewField.value.trim() : '';

                if (!rating) {
                    window.alert('Please select a rating');
                    return;
                }
                if (!reviewText) {
                    window.alert('Please write your review');
                    return;
                }

                var submitBtn = this.querySelector('.btn-submit-review');
                var originalText = submitBtn ? submitBtn.innerHTML : '';
                if (submitBtn) {
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
                    submitBtn.disabled = true;
                    submitBtn.classList.add('loading');
                }

                fetch(this.action, {
                    method: 'POST',
                    body: new FormData(this),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        Accept: 'application/json'
                    }
                })
                    .then(function (response) {
                        return response.json();
                    })
                    .then(function (data) {
                        if (data.success) {
                            window.alert('Review submitted successfully!');
                            closeReviewModal();
                            window.location.reload();
                        } else {
                            window.alert('Error: ' + (data.message || 'Failed to submit review'));
                            if (submitBtn) {
                                submitBtn.innerHTML = originalText;
                                submitBtn.disabled = false;
                                submitBtn.classList.remove('loading');
                            }
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
