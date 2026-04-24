<section id="gallery" class="content-section">
    <h2 class="section-title">School Gallery</h2>
    <div class="section-content">
        @if($school->images && $school->images->count() > 0)
            <div class="gallery-grid">
                @foreach($school->images as $image)
                    <div class="gallery-item">
                        <img src="{{ asset('website/' . $image->image_path) }}" alt="{{ $image->title ?? 'School Image' }}">
                        @if($image->title)
                            <div class="image-caption">{{ $image->title }}</div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <p class="no-content">No gallery images available.</p>
        @endif
    </div>
</section>
