<?php
namespace InvoiceDroid\Setting;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use InvoiceDroid\Setting\Middleware\AutoSaveSettings;

class ServiceProvider extends BaseServiceProvider
{

    /**
     * Boot the app
     */
    public function boot()
    {
        $this->setPublishFiles();

        $this->app->singleton('setting.manager', function ($app) {
            return new Manager($app);
        });

        $this->app->singleton('setting', function ($app) {
            return $app['setting.manager']->driver();
        });

        // Auto save setting
        if (config('setting.auto_save')) {
            $kernel = $this->app['Illuminate\Contracts\Http\Kernel'];
            $kernel->pushMiddleware(AutoSaveSettings::class);
        }

        // Override config
        if (config('setting.override')) {
            foreach (config('setting.override') as $config_key => $setting_key) {
                // handle non associative override declaration
                $config_key = $config_key ?: $setting_key;
                try {
                    $value = setting($setting_key);
                    if (is_null($value)) {
                        continue;
                    }
                } catch (\Exception $e) {
                    continue;
                }
                config([$config_key => $value]);
            }
            unset($value);
        }

        // Register blade directive
        Blade::directive('setting', function ($expression) {
            return "<?php echo setting($expression); ?>";
        });

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'setting');
    }

    /**
     * Set the pubhlishable files
     */
    private function setPublishFiles()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php'  => config_path('setting.php'),
            ], 'setting');
        }
    }
}
