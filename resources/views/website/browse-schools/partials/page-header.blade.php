<!-- ==================== PAGE HEADER ==================== -->
<section class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="page-title">Browse All Schools</h1>
                <p class="page-subtitle">Discover and compare educational institutions that match your needs</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <div class="results-count">
                    Showing {{ $schools->firstItem() ?? 0 }}-{{ $schools->lastItem() ?? 0 }} of {{ $schools->total() }} schools
                </div>
            </div>
        </div>
    </div>
</section>
