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
    public const HOME = '/dashboard';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            $this->mapAdminRoutes();
            $this->mapApiRoutes();
            $this->mapDashboardRoutes();
            $this->mapManagerRoutes();
            $this->mapWebRoutes();
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
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }

    /**
     * Map Admin routes
     *
     * @return void
     */
    private function mapAdminRoutes() {
        Route::prefix('admin')
            ->name('admin.')
            ->middleware('admin')
            ->namespace($this->namespace . '\\Admin')
            ->group(base_path('routes/admin.php'));
    }

    /**
     * Map API routes
     *
     * @return void
     */
    private function mapApiRoutes() {
        Route::prefix('api')
            ->name('api.')
            ->middleware('api')
            ->namespace($this->namespace . '\\Api')
            ->group(base_path('routes/api.php'));
    }

    /**
     * Map Dashboard routes
     *
     * @return void
     */
    private function mapDashboardRoutes() {
        Route::prefix('dashboard')
            ->name('dashboard.')
            ->middleware('dashboard')
            ->namespace($this->namespace . '\\Dashboard')
            ->group(base_path('routes/dashboard.php'));
    }

    /**
     * Map Manager routes
     *
     * @return void
     */
    private function mapManagerRoutes() {
        Route::prefix('manager')
            ->name('manager.')
            ->middleware('manager')
            ->namespace($this->namespace . '\\Manager')
            ->group(base_path('routes/manager.php'));
    }

    /**
     * Map Web routes
     *
     * @return void
     */
    private function mapWebRoutes() {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }
}
