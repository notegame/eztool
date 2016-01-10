<?php
namespace Exzcute\EzTool;

use Illuminate\Support\ServiceProvider;

//run php artisan vendor:publish --provider="Exzcute\EzTool\EzToolServiceProvider"

class EzToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/views', 'eztool');

        if (! $this->app->routesAreCached()) {
            require __DIR__.'/routes.php';
        }

        $this->publishes([
            __DIR__.'/migrations/2016_01_01_075416_create_permissions_table.php' => base_path('database/migrations/2016_01_01_075416_create_permissions_table.php'),
            __DIR__.'/config/eztool.permission.php' => base_path('config/eztool.permission.php'),
        ]);

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('eztool', function ($app)
        {
            return new EzTool;
        });

        $this->app->bind('permission_editor', function ($app)
        {
            return new PermissionEditor;
        });

        $this->app->bind('acl_manager', function ($app)
        {
            return new ACLManager;
        });

        $this->app->bind('menu_manager', function ($app)
        {
            return new MenuManager;
        });
    }
}
