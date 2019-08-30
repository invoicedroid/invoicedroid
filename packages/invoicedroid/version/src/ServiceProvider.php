<?php
namespace InvoiceDroid\Version;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;


class ServiceProvider extends BaseServiceProvider
{
    public function boot(Router $router)
    {
        $this->setUpConfig();

        $this->app->singleton('version', function ($app) {
            return new Version($app);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'version');
    }

    /**
     * Set up the config
     */
    private function setUpConfig()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('version.php'),
            ], 'version');
        }

    }
}
