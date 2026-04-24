@php
    $loginUrl = \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeUrl(route('login', [], false));
    $loginLink = '<a href="'.e($loginUrl).'" class="text-decoration-none">'.e(__('mcqs.top_performers.login')).'</a>';
@endphp
<!-- ==================== TOP PERFORMERS SECTION ==================== -->
<section class="py-5">
    <div class="container">
        <div class="section-title-wrapper text-center mb-5">
            <h2 class="section-title">{{ __('mcqs.top_performers.title') }}</h2>
            <p class="section-subtitle">{!! __('mcqs.top_performers.subtitle', ['login' => $loginLink]) !!}</p>
        </div>

        <div class="row g-4 justify-content-center">
            @forelse($topUsers as $performer)
            <div class="col-6 col-md-2">
                <div class="performer-card text-center">
                    <div class="performer-avatar mb-3">
                        <img src="{{ asset('website/school/default/student_icon.png') }}"
                             alt="{{ $performer['user']->name }}"
                             class="rounded-circle"
                             style="width: 35px; height: 35px; object-fit: cover;">
                    </div>
                    <small class="performer-name mb-2 d-block">{{ $performer['user']->name }}</small>
                    <div class="performer-stars mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($performer['stars']))
                                <i class="fas fa-star text-warning"></i>
                            @elseif($i == ceil($performer['stars']) && ($performer['stars'] % 1) >= 0.5)
                                <i class="fas fa-star-half-alt text-warning"></i>
                            @else
                                <i class="far fa-star text-muted"></i>
                            @endif
                        @endfor
                    </div>
                    <div class="performer-stats">
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center">
                <p class="text-muted">{{ __('mcqs.top_performers.empty') }}</p>
            </div>
            @endforelse
        </div>
    </div>
</section>
