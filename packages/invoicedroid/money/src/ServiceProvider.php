<?php
namespace InvoiceDroid\Money;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;


class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->setUpConfig();

        Money::setLocale($this->app->make('translator')->getLocale());
        Currency::setCurrencies($this->app->make('config')->get('money'));

        // Register blade directives
        $this->app->afterResolving('blade.compiler', function (BladeCompiler $bladeCompiler) {
            $bladeCompiler->directive('money', function ($expression) {
                return "<?php echo money($expression); ?>";
            });
        });
        $this->app->afterResolving('blade.compiler', function (BladeCompiler $bladeCompiler) {
            $bladeCompiler->directive('currency', function ($expression) {
                return "<?php echo currency($expression); ?>";
            });
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'money');
    }


    /**
     * Set up the config
     */
    private function setUpConfig()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('money.php'),
            ], 'money');
        }

    }
}
