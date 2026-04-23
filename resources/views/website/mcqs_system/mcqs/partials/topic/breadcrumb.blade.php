<div class="breadcrumb-wrapper">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('website.mcqs.index') }}">MCQs</a></li>
            <li class="breadcrumb-item"><a href="{{ route('website.mcqs.subject', $subject->slug) }}">{{ $subject->name }}</a></li>
            <li class="breadcrumb-item active">{{ $topic->title }}</li>
        </ol>
    </nav>
</div>
