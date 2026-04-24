<section
    class="school-header {{ $school->banner_image ? 'has-banner-image' : '' }}"
    @if($school->banner_image) style="--school-banner-image: url('{{ asset('website/' . $school->banner_image) }}');" @endif
>
    <div class="school-header-overlay"></div>

    @if($hasHero)
        <div class="container">
            <div class="school-hero-content">
                <div class="school-logo-wrapper me-3">
                    @if($school->profile->logo)
                        <img src="{{ asset('website/'. $school->profile->logo) }}" alt="{{ $locName }} Logo" class="school-logo-img school-logo-img--hero rounded">
                    @else
                        <div class="school-logo-placeholder school-logo-placeholder--hero rounded d-flex align-items-center justify-content-center">
                            <i class="fas fa-school text-white"></i>
                        </div>
                    @endif
                </div>

                <div class="school-text-content">
                    <h1 class="school-title">{{ $locBannerTitle !== '' ? $locBannerTitle : $locName }}</h1>
                    @if($locTagline !== '')
                        <p class="school-tagline">{{ $locTagline }}</p>
                    @endif
                    <p class="school-name-sub">{{ $locName }}</p>
                </div>
            </div>
        </div>
    @endif
</section>
