<?php

namespace App\Providers;

use App\Enums\ModerationStatus;
use App\Models\Testimonial;
use App\Services\ImageWebpService;
use App\Support\CacheKeys;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ImageWebpService::class, function () {
            return new ImageWebpService;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Testimonial::saved(function (Testimonial $t): void {
            if ($t->wasRecentlyCreated) {
                if ($t->status === ModerationStatus::Approved) {
                    CacheKeys::forgetTestimonialListCaches();
                }

                return;
            }
            $changes = $t->getChanges();
            if (array_key_exists('status', $changes)) {
                CacheKeys::forgetTestimonialListCaches();

                return;
            }
            if ($t->status === ModerationStatus::Approved) {
                $substantive = collect($changes)->except('updated_at');
                if ($substantive->isNotEmpty()) {
                    CacheKeys::forgetTestimonialListCaches();
                }
            }
        });

        Testimonial::deleted(function (Testimonial $t): void {
            if ($t->status === ModerationStatus::Approved) {
                CacheKeys::forgetTestimonialListCaches();
            }
        });

        Blade::directive('cleanContent', function ($expression) {
            return "<?php echo App\Http\Controllers\PageController::cleanContent($expression); ?>";
        });

        // Set LOG_DB_QUERIES=true in .env to log every query (N+1 hunting). Remove when done.
        if (app()->environment('local') && filter_var(env('LOG_DB_QUERIES', false), FILTER_VALIDATE_BOOL)) {
            DB::listen(function ($query): void {
                Log::debug('sql', [
                    'sql' => $query->sql,
                    'bindings' => $query->bindings,
                    'ms' => $query->time,
                ]);
            });
        }
    }
}
