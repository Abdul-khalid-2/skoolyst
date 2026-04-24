<!-- ==================== CALL TO ACTION SECTION ==================== -->
<section class="cta-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2>{{ __('mcqs.cta.title') }}</h2>
                <p class="mb-md-0">{{ __('mcqs.cta.body') }}</p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeUrl(route('website.mcqs.mock-tests', [], false)) }}" class="btn btn-light btn-lg">
                    {{ __('mcqs.cta.button') }} <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</section>
