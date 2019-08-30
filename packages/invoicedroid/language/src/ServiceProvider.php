<?php
namespace InvoiceDroid\Language;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;


class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param Router $router
     *
     * @return void
     */
    public function boot(Router $router)
    {

        if (!$this->app->routesAreCached()) {
            require __DIR__ . '/Routes/web.php';
        }

        $this->setPublishFiles();

        $router->aliasMiddleware('language', config('language.middleware'));

        $this->app->singleton('language', function ($app) {
            return new Language($app);
        });
    }


    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'language');
    }

    private function setPublishFiles()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php'  => config_path('language.php'),
            ], 'language');
        }
    }
}
