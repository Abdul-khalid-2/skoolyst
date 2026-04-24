<section id="curriculum" class="content-section">
    <h2 class="section-title">Curriculum &amp; Programs</h2>
    <div class="section-content">
        @if($school->curriculums && $school->curriculums->count() > 0)
            <div class="curriculum-list">
                @foreach($school->curriculums as $curriculum)
                    <div class="curriculum-item">
                        <h4>{{ $curriculum->name }}</h4>
                        <p>{{ $curriculum->description ?? 'No description available.' }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <p class="no-content">Curriculum information not available.</p>
        @endif
    </div>
</section>
