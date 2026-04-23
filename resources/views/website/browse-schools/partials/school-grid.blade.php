<!-- ==================== SCHOOLS GRID SECTION ==================== -->
<section class="schools-grid-section">
    <div class="container">
        <div class="row" id="schoolsContainer">
            @foreach($schools as $school)
            <div class="col-lg-4 col-md-6 school-card-col">
                <article class="school-card" itemscope itemtype="https://schema.org/School">
                    <div class="school-image">
                        @if(isset($school['banner_image']) && $school['banner_image'])
                        <img src="{{ $school['banner_image'] }}" alt="{{ $school['name'] }} school image" itemprop="image">
                        @else
                        <i class="fas fa-school" aria-hidden="true"></i>
                        @endif
                    </div>
                    <div class="school-content">
                        <div class="school-header">
                            <div>
                                <h2 class="school-name" itemprop="name">{{ $school['name'] }}</h2>
                                <div class="school-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span itemprop="addressLocality">{{ $school['location'] ?? 'Location not specified' }}</span>
                                </div>
                            </div>
                            <span class="school-type-badge">{{ $school['type'] }}</span>
                        </div>
                        <div class="school-rating" itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating">
                            @php
                            $averageRating = $school['rating'] ?? 0;
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

                            <span class="rating-value">{{ number_format($averageRating, 1) }}</span>
                            <small class="review-count">({{ $school['review_count'] ?? 0 }} reviews)</small>
                            <meta itemprop="ratingValue" content="{{ number_format($averageRating, 1) }}">
                            <meta itemprop="reviewCount" content="{{ $school['review_count'] ?? 0 }}">
                        </div>
                        <p class="school-description" itemprop="description">
                            {{ Str::limit($school['description'] ?? 'No description available.', 120) }}
                        </p>
                        <div class="school-features">
                            @if(isset($school['curriculum']) && $school['curriculum'])
                                <span class="feature-tag"><i class="fas fa-book"></i> {{ $school['curriculum'] }}</span>
                            @endif

                            @if(isset($school['features']) && is_array($school['features']))
                                @foreach(array_slice($school['features'], 0, 3) as $feature)
                                    <span class="feature-tag">{{ $feature }}</span>
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

        @if($schools->count() == 0)
        <div class="no-results">
            <i class="fas fa-search"></i>
            <h4>No schools found</h4>
            <p>Try adjusting your search criteria or <a href="{{ route('browseSchools.index') }}" class="browse-all-link">browse all schools</a>.</p>
        </div>
        @endif
    </div>
</section>
