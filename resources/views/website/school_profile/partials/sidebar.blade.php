<div class="sidebar-column">
    <section id="contact-sidebar" class="sidebar-section">
        <h3 class="sidebar-title">Contact Information</h3>
        <div class="contact-info">
            @if($school->contact_number)
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <span>{{ $school->contact_number }}</span>
                </div>
            @endif
            @if($school->email)
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <span>{{ $school->email }}</span>
                </div>
            @endif
            @if($school->website)
                <div class="contact-item">
                    <i class="fas fa-globe"></i>
                    <a href="{{ $school->website }}" target="_blank" rel="noopener noreferrer">Visit Website</a>
                </div>
            @endif
            <div class="contact-item">
                <i class="fas fa-map-marker-alt"></i>
                <span>{{ $school->address }}, {{ $school->city }}</span>
            </div>
        </div>
    </section>

    <section class="sidebar-section">
        <h3 class="sidebar-title">Fee Structure</h3>
        <div class="fee-info">
            @if($school->fee_structure_type === 'fixed')

                @if($school->regular_fees)
                    <div class="fee-item">
                        <span class="fee-label">Regular Fees:</span>
                        <span class="fee-amount">Rs {{ number_format($school->regular_fees) }}</span>
                    </div>
                @endif

                @if($school->discounted_fees)
                    <div class="fee-item">
                        <span class="fee-label">Discounted Fees:</span>
                        <span class="fee-amount">Rs {{ number_format($school->discounted_fees) }}</span>
                    </div>
                @endif

                @if($school->admission_fees)
                    <div class="fee-item">
                        <span class="fee-label">Admission Fees:</span>
                        <span class="fee-amount">Rs {{ number_format($school->admission_fees) }}</span>
                    </div>
                @endif

                @if(!$school->regular_fees && !$school->discounted_fees && !$school->admission_fees)
                    <p class="no-content">Fee information not available.</p>
                @endif

            @elseif($school->fee_structure_type === 'class_wise')

                @php
                    $classFees = is_array($school->class_wise_fees)
                        ? $school->class_wise_fees
                        : json_decode($school->class_wise_fees, true);
                @endphp

                @if(!empty($classFees) && is_array($classFees))

                    @foreach($classFees as $range => $amount)
                        <div class="fee-item">
                            <span class="fee-label">{{ $range }}</span>
                            <span class="fee-amount">Rs {{ $amount }}</span>
                        </div>
                    @endforeach

                @else
                    <p class="no-content">Class-wise fee information not available.</p>
                @endif

                @if($school->admission_fees)
                    <div class="fee-item">
                        <span class="fee-label">Admission Fees:</span>
                        <span class="fee-amount">Rs {{ number_format($school->admission_fees) }}</span>
                    </div>
                @endif
            @else
                <p class="no-content">Fee structure not defined.</p>
            @endif
        </div>
    </section>

    <section class="sidebar-section">
        <h3 class="sidebar-title">Quick Actions</h3>
        <div class="action-buttons">
            <button type="button" class="action-btn tertiary" id="writeReviewBtn">
                <i class="fas fa-edit"></i>
                Write Review
            </button>
        </div>
    </section>

    <section class="sidebar-section">
        <h3 class="sidebar-title">Follow Us</h3>
        <div class="social-links">
            @if($school->profile && $school->profile->social_media)
                @php
                    $socialMedia = json_decode($school->profile->social_media, true);
                @endphp

                @foreach($socialMedia as $platform => $url)
                    @if($url)
                        <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="social-link" title="{{ ucfirst($platform) }}">
                            <i class="fab fa-{{ $platform }}"></i>
                        </a>
                    @endif
                @endforeach
            @else
                <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
            @endif
        </div>
    </section>
</div>
