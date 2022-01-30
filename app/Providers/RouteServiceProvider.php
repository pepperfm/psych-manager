<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected ?string $webNamespace = 'App\\Http\\Controllers';

    /**
     * This namespace is applied to the controller routes in your api routes file.
     *
     * @var string
     */
    // protected string $apiNamespace = 'App\\Http\\Controllers\\Api';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->mapWebRoutes();
        $this->mapApiRoutes();
    }

    /**
     * Configure a web routes for the application.
     *
     * @return void
     */
    protected function mapWebRoutes(): void
    {
        Route::middleware('web')
            ->group(base_path('routes/web/web.php'));
    }

    /**
     * Configure an api routes for the application.
     *
     * @return void
     */
    protected function mapApiRoutes(): void
    {
        $fileSystem = new Filesystem();
        $files = $fileSystem->files(base_path('routes/api'));

        if (! empty($files) && is_array($files)) {
            foreach ($files as $file) {
                Route::prefix('api')
                    ->middleware('api')
                    ->group($file->getRealPath());
            }
        }
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', static fn() => Limit::perMinute(60));
        RateLimiter::for('v1', static function (Request $request) {
            return Limit::perMinute(45)->response(function () {
                return response('Too many requests', 429);
            })->by($request->user()?->id ?: $request->ip());
        });
    }
}
