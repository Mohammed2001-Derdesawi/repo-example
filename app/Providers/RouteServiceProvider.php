<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
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
    public const HOME = '/home';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            # STAGING #
            if(env('APP_ENV')=='local'){
                Route::prefix('api/w')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api-w.php'));

            Route::prefix('api/m')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api-m.php'));
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/dashboard.php'));

            }
            else{
            // # PRODUCTION #
            Route::prefix('w')
                ->domain(env('PRODUCTION_API_SUBDOMAIN'))
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api-w.php'));

            Route::prefix('m')
                ->domain(env('PRODUCTION_API_SUBDOMAIN'))
                ->middleware('api')
                ->namespace($this->namespace)
                // ->prefix(env('CURRENT_PREFIX_MOBILE_API','v1'))
                ->group(base_path('routes/api-m.php'));

            Route::middleware('web')
                ->domain(env('PRODUCTION_DASHBOARD_SUBDOMAIN'))
                ->namespace($this->namespace)
                ->group(base_path('routes/dashboard.php'));
            }


        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
