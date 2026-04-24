<!-- ==================== SCHOOL DIRECTORY SECTION ==================== -->
<section class="directory-section" id="directory">
    <div class="container">
        <h2 class="section-title"> SCHOOLS </h2>
        <p class="section-subtitle">Explore Educational Institutions Around The Globe That Match Your Needs</p>

        <div class="row" id="schoolsContainer">
            <!-- Schools will be dynamically loaded here -->
            @foreach($schools as $school)
                <div class="col-lg-4 col-md-6 school-card-col">
                    <article class="school-card" itemscope itemtype="https://schema.org/School">
                        <div class="school-image">
                            @if(isset($school['banner_image']) && $school['banner_image'])
                                <img src="{{ $school['banner_image'] }}" alt="{{ $school['name'] }} school campus image" itemprop="image" style="width: 100%; height: 200px; object-fit: cover;">
                            @elseif(isset($school->banner_image) && $school->banner_image)
                                <img src="{{ asset('website/' . $school->banner_image) }}" alt="{{ is_array($school) ? $school['name'] : $school->localized('name') }} school campus image" itemprop="image" style="width: 100%; height: 200px; object-fit: cover;">
                            @else
                                <i class="fas fa-school" aria-hidden="true"></i>
                            @endif
                            @if(isset($school->hasNewAnnouncements) && $school->hasNewAnnouncements())
                                <div class="new-announcement-badge">
                                    <span class="badge-pulse"></span>
                                    <a href="{{ route('browseSchools.show', $school->uuid ?? $school['id']) }}" class="announcement-link">
                                        <i class="fas fa-bullhorn"></i>
                                        New Updates
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div class="school-content">
                            <div class="school-header">
                                <div>
                                    <h3 class="school-name" itemprop="name">{{ is_array($school) ? ($school['name'] ?? '') : $school->localized('name') }}</h3>
                                    <div class="school-location">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span itemprop="addressLocality">{{ $school['location'] ?? ($school->city ?? 'Location not specified') }}</span>
                                    </div>
                                </div>
                                <span class="school-type-badge">{{ $school['type'] ?? $school->school_type }}</span>
                            </div>
                            <div class="school-rating" itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating">
                                @php
                                    $averageRating = $school['rating'] ?? ($school->reviews->avg('rating') ?? 0);
                                    $fullStars = floor($averageRating);
                                    $hasHalfStar = $averageRating - $fullStars >= 0.5;
                                    $emptyStars = 5 - ceil($averageRating);
                                @endphp

                                @for($i = 0; $i < $fullStars; $i++)
                                    <i class="fas fa-star"></i>
                                @endfor

                                @if($hasHalfStar)
                                    <i class="fas fa-star-half-alt"></i>
                                @endif

                                @for($i = 0; $i < $emptyStars; $i++)
                                    <i class="far fa-star"></i>
                                @endfor

                                <span>{{ number_format($averageRating, 1) }}</span>
                                <small>({{ $school['review_count'] ?? ($school->reviews->count() ?? 0) }} reviews)</small>
                                <meta itemprop="ratingValue" content="{{ number_format($averageRating, 1) }}">
                                <meta itemprop="reviewCount" content="{{ $school['review_count'] ?? ($school->reviews->count() ?? 0) }}">
                            </div>
                            <p class="school-description" itemprop="description">
                                {{ Str::limit($school['description'] ?? ($school->description ?? 'No description available'), 160) }}
                            </p>
                            <div class="school-features">
                                @if(isset($school['curriculum']) && $school['curriculum'])
                                    <span class="feature-tag"><i class="fas fa-book"></i> {{ $school['curriculum'] }}</span>
                                @elseif(isset($school->curriculums) && $school->curriculums->count() > 0)
                                    <span class="feature-tag"><i class="fas fa-book"></i> {{ $school->curriculums->first()->name }}</span>
                                @endif

                                @if(isset($school['features']) && is_array($school['features']))
                                    @foreach(array_slice($school['features'], 0, 3) as $feature)
                                        <span class="feature-tag">{{ $feature }}</span>
                                    @endforeach
                                @elseif(isset($school->features))
                                    @foreach($school->features->take(3) as $feature)
                                        <span class="feature-tag">{{ $feature->name }}</span>
                                    @endforeach
                                @endif
                            </div>
                            <a href="{{ route('browseSchools.show', $school['uuid']) }}" class="view-profile-btn" itemprop="url">
                                <i class="fas fa-eye"></i> View Full Profile
                            </a>
                            <p class="visitor-count">
                                @if(isset($school['visitor_count']) && $school['visitor_count'] > 0)
                                    <i class="fas fa-eye"></i> {{ $school['visitor_count'] }}
                                @endif
                            </p>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('browseSchools.index') }}" class="btn btn-primary">View All Schools <i class="fas fa-arrow-right"></i></a>
        </div>

        <div id="noResults" class="no-results" style="display: none;">
            <i class="fas fa-search fa-3x site-muted mb-3 d-block"></i>
            <p>No schools found matching your criteria. Try adjusting your filters.</p>
        </div>

        @if($schools->count() == 0)
        <div class="no-results">
            <i class="fas fa-school fa-3x site-muted mb-3 d-block"></i>
            <p>No schools available at the moment. Please check back later.</p>
        </div>
        @endif
    </div>
</section>
