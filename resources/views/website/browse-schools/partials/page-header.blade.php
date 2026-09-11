<!-- ==================== PAGE HEADER ==================== -->
<section class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="page-title">{{ $seoH1 ?? 'Find the Best Schools in Pakistan' }}</h1>
                <p class="page-subtitle">{{ $seoIntro ?? 'Discover and compare educational institutions that match your needs' }}</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <div class="results-count">
                    Showing {{ $schools->firstItem() ?? 0 }}-{{ $schools->lastItem() ?? 0 }} of {{ $schools->total() }} schools
                </div>
                <a href="{{ route('compare.index') }}" class="compare-schools-link">
                    <i class="fas fa-balance-scale" aria-hidden="true"></i> Compare Schools
                </a>
            </div>
        </div>
    </div>
</section>
