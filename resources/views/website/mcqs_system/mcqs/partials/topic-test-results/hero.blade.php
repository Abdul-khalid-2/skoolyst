{{-- Same pattern as test-subject-results hero (mcqs-hero-section); topic is primary on this route --}}
<section class="mcqs-hero-section subject-results-hero--mcqs" id="topic-test-results-hero">
    <div class="container">
        <div class="mcqs-hero-content">
            <p class="subject-results-hero__eyebrow">Test results</p>
            <h1 class="mcqs-hero-title subject-results-hero__title">{{ $topic->title }}</h1>
            <p class="mcqs-hero-subheading subject-results-hero__sub mb-0">{{ $subject->name }}</p>
        </div>
    </div>
</section>
