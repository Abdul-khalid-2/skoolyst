@php
    $heroTitle = $locBannerTitle !== '' && $locBannerTitle !== null ? $locBannerTitle : $locName;
    $showOfficialName = ($locBannerTitle !== '' && $locBannerTitle !== null) && trim((string) $locBannerTitle) !== trim((string) $locName);
    $schoolTypeLabel = $school->school_type instanceof \BackedEnum
        ? $school->school_type->value
        : ($school->school_type !== null ? (string) $school->school_type : '');
    $hasLocationMeta = filled(trim((string) ($school->city ?? '')));
@endphp
<section
    class="school-hero school-header {{ $school->banner_image ? 'has-banner-image' : '' }}"
    @if($school->banner_image) style="--school-banner-image: url('{{ asset('website/' . $school->banner_image) }}');" @endif
    aria-label="School profile header"
>
    <div class="school-header-overlay" aria-hidden="true"></div>
    <div class="school-hero-pattern" aria-hidden="true"></div>

    @if($hasHero)
        <div class="container school-hero-container">
            <div class="school-hero-content">
                <div class="school-hero-brand">
                    <div class="school-logo-wrapper">
                        @if($school->profile?->logo)
                            <img
                                src="{{ asset('website/'. $school->profile->logo) }}"
                                alt="{{ $locName }} — logo"
                                class="school-logo-img school-logo-img--hero"
                                width="160"
                                height="160"
                            >
                        @else
                            <div class="school-logo-placeholder school-logo-placeholder--hero d-flex align-items-center justify-content-center" role="img" aria-label="{{ $locName }}">
                                <i class="fas fa-school" aria-hidden="true"></i>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="school-hero-copy">
                    @if($hasLocationMeta || filled($schoolTypeLabel))
                        <div class="school-hero-meta">
                            @if($hasLocationMeta)
                                <span class="school-hero-meta-item">
                                    <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                                    {{ $school->city }}
                                </span>
                            @endif
                            @if(filled($schoolTypeLabel))
                                <span class="school-hero-badge">{{ $schoolTypeLabel }}</span>
                            @endif
                        </div>
                    @endif

                    <div class="school-text-content">
                        <h1 class="school-title">{{ $heroTitle }}</h1>
                        @if($locTagline !== '')
                            <p class="school-tagline">{{ $locTagline }}</p>
                        @endif
                        @if($showOfficialName)
                            <p class="school-name-sub">{{ $locName }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</section>
