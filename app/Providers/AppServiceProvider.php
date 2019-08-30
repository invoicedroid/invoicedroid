<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use KgBot\LaravelLocalization\Facades\ExportLocalizations as ExportLocalization;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        View::composer( 'index', function ( $view ) {

            return $view->with( [
                'messages' => ExportLocalization::export()->toArray(),
            ] );
        } );
    }
}
