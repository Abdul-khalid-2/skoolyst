{{-- Topic MCQ practice hero (see assets/css/mcq/topic-hero-breadcrumb-description.css) --}}
<section class="topic-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1>{{ $topic->title }}</h1>
                <div class="topic-meta">
                    <span class="topic-meta-item">
                        <i class="far fa-clock"></i>
                        {{ $topic->estimated_time_minutes }} minutes
                    </span>
                    <span class="topic-difficulty-badge">
                        {{ $topic->formatted_difficulty }}
                    </span>
                    <span class="topic-stats-badge">
                        <i class="fas fa-question-circle"></i>{{ $mcqs->total() }} Questions
                    </span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="topic-header-actions">
                    <button type="button" class="btn btn-light" onclick="submitTest()" id="submitBtn">
                        <i class="fas fa-check-circle me-2"></i>Submit Test
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>
