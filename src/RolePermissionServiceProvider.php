<?php
namespace Budhaspec\Rolepermission;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Configuration\Middleware;

class RolePermissionServiceProvider extends ServiceProvider
{

    public function configureMiddleware(Middleware $middleware): void
    {
        $middleware->alias([
            'check.permission' => CheckPermission::class,
        ]);
    }

    protected function registerHelpers()
    {
        $helpers = __DIR__ . '/Helper/helpers.php';
        if (file_exists($helpers)) {
            require_once $helpers;
        }
    }

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->registerHelpers();
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'access');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        
        $this->publishes([
            __DIR__ . '/Http/Middleware/CheckPermission.php' => app_path('Http/Middleware/CheckPermission.php'),
        ], 'role-permission');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/access'),
        ], 'views');

        $this->publishes([
            __DIR__.'/../resources/images' => public_path('budhaspec/rolepermission/images'),
        ], 'public');
    }
}
