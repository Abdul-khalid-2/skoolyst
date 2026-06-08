{{-- Visible SEO content for users, crawlers, and AI answer engines (GEO). --}}
@if($schools->currentPage() === 1)
<section class="seo-content-section" aria-labelledby="seo-about-heading">
    <div class="container">
        <div class="seo-content-card">
            <h2 id="seo-about-heading" class="seo-content-title">Pakistan School Directory — Find Schools Near You</h2>
            <p class="seo-intro-text">{{ $seoIntro ?? 'Compare schools across Pakistan by city, curriculum, and parent reviews on SKOOLYST.' }}</p>

            @if(isset($cities) && $cities->isNotEmpty())
            <div class="seo-city-links">
                <h3 class="seo-subtitle">Popular cities</h3>
                <nav aria-label="Browse schools by city">
                    <ul class="seo-city-list">
                        @foreach($cities->take(12) as $city)
                            <li>
                                <a href="{{ route('browseSchools.index', ['location' => $city]) }}">
                                    Schools in {{ $city }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </nav>
            </div>
            @endif

            @if(isset($seoFaqItems) && count($seoFaqItems))
            <div class="seo-faq-block">
                <h3 class="seo-subtitle">Frequently asked questions</h3>
                <div class="accordion seo-faq-accordion" id="schoolsSeoFaq">
                    @foreach($seoFaqItems as $index => $faq)
                        <div class="accordion-item">
                            <h4 class="accordion-header" id="faq-heading-{{ $index }}">
                                <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#faq-collapse-{{ $index }}"
                                        aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                                        aria-controls="faq-collapse-{{ $index }}">
                                    {{ $faq['q'] }}
                                </button>
                            </h4>
                            <div id="faq-collapse-{{ $index }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                                 aria-labelledby="faq-heading-{{ $index }}" data-bs-parent="#schoolsSeoFaq">
                                <div class="accordion-body seo-faq-answer">
                                    {{ $faq['a'] }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
@endif
