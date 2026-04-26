<?php

namespace App\Providers;

use App\Services\ImageWebpService;
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
