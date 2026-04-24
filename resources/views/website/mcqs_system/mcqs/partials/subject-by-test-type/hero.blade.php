<section class="practice-hero">
    <div class="container">
        <div class="practice-hero-content">
            <div class="practice-hero-icon" style="background: {{ $subject->color_code ?? '#667eea' }};">
                <i class="{{ $subject->icon ?? 'fas fa-book' }}"></i>
            </div>
            <div class="practice-hero-text">
                <h1>{{ $subject->name ?? 'Subject' }}</h1>
                <p>{{ $subject->description ?? 'Practice MCQs for ' . ($subject->name ?? 'this subject') }}</p>
            </div>
        </div>
    </div>
</section>
