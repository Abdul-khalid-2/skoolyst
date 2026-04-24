<section id="mission-vision" class="content-section">
    <h2 class="section-title">Mission &amp; Vision</h2>
    <div class="section-content">
        <div class="mission-vision-grid">
            @if($school->localized('mission') !== '')
            <div class="mission-vision-item">
                <div class="mv-icon">
                    <i class="fas fa-bullseye"></i>
                </div>
                <h3>Our Mission</h3>
                <p>{!! nl2br(e($school->localized('mission'))) !!}</p>
            </div>
            @endif

            @if($school->localized('vision') !== '')
            <div class="mission-vision-item">
                <div class="mv-icon">
                    <i class="fas fa-eye"></i>
                </div>
                <h3>Our Vision</h3>
                <p>{!! nl2br(e($school->localized('vision'))) !!}</p>
            </div>
            @endif

            @if($school->localized('school_motto') !== '')
            <div class="mission-vision-item">
                <div class="mv-icon">
                    <i class="fas fa-quote-left"></i>
                </div>
                <h3>Our Motto</h3>
                <p>"{{ $school->localized('school_motto') }}"</p>
            </div>
            @endif
        </div>

        @if($school->localized('mission') === '' && $school->localized('vision') === '' && $school->localized('school_motto') === '')
            <p class="no-content">Mission and vision information not available.</p>
        @endif
    </div>
</section>
