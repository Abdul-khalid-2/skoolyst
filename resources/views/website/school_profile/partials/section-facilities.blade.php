<section id="facilities" class="content-section">
    <h2 class="section-title">Facilities &amp; Features</h2>
    <div class="section-content">
        @if($school->features && $school->features->count() > 0)
            <div class="facilities-grid">
                @foreach($school->features as $feature)
                    <div class="facility-item">
                        <i class="fas fa-{{ $feature->icon ?? 'check' }}"></i>
                        <div>
                            <span>{{ $feature->name }}</span>
                            @if($feature->pivot->description)
                                <p class="facility-description">{{ $feature->pivot->description }}</p>
                            @elseif($feature->description)
                                <p class="facility-description">{{ $feature->description }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="no-content">Facilities information not available.</p>
        @endif
    </div>
</section>
