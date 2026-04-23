<div class="description-card" id="descriptionPreview">
    <div class="description-header">
        <h5><i class="fas fa-info-circle me-2"></i>Topic Overview</h5>
        <button class="description-toggle" onclick="toggleDescription()" id="toggleDescriptionBtn" type="button">
            Read More <i class="fas fa-chevron-down ms-1"></i>
        </button>
    </div>
    <p class="description-preview">{{ Str::limit(strip_tags($topic->description), 200) }}</p>
</div>

<div class="description-full" id="descriptionFull">
    <div class="description-header">
        <h5><i class="fas fa-info-circle me-2"></i>Complete Topic Overview</h5>
        <button class="description-toggle" onclick="toggleDescription()" type="button">
            Show Less <i class="fas fa-chevron-up ms-1"></i>
        </button>
    </div>
    {!! $topic->content ?? $topic->description !!}
</div>
