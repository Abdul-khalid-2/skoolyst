<div id="mcqsPracticeSection" class="col-lg-8">
    <div id="mcqsContent">
        @include('website.mcqs_system.mcqs.partials.test-mcqs-content', ['mcqs' => $mcqs])
    </div>

    <div id="loadingSpinner" class="text-center py-4" style="display: none;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-2">Loading questions...</p>
    </div>
</div>
