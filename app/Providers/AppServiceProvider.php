<?php

namespace App\Providers;

use App\Services\ImageWebpService;
use Illuminate\Support\Facades\Blade;
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
    }
}
