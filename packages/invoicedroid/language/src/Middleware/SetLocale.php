<?php
namespace InvoiceDroid\Language\Middleware;


use Carbon\Carbon;
use Jenssegers\Date\Date;
use Closure;
use Illuminate\Support\Facades\App;

class SetLocale
{
    /**
     * This function checks if language to set is an allowed lang of config.
     *
     * @param string $locale
     **/
    private function setLocale($locale)
    {
        // Check if is allowed and set default locale if not
        if (!language()->allowed($locale)) {
            $locale = config('app.locale');
        }
        // Set app language
        App::setLocale($locale);
        // Set carbon language
        if (config('language.carbon')) {
            // Carbon uses only language code
            if (config('language.mode.code') == 'long') {
                $locale = explode('-', $locale)[0];
            }
            Carbon::setLocale($locale);
        }
        // Set date language
        if (config('language.date')) {
            // Date uses only language code
            if (config('language.mode.code') == 'long') {
                $locale = explode('-', $locale)[0];
            }
            Date::setLocale($locale);
        }
    }
    public function setDefaultLocale()
    {
        $this->setLocale(config('app.locale'));
    }

    /**
     * Set the user locale
     */
    public function setUserLocale()
    {
        $user = auth()->user();
        if ($user->locale) {
            $this->setLocale($user->locale);
        } else {
            $this->setDefaultLocale();
        }
    }

    /**
     * Set the system locale
     * @param $request
     */
    public function setSystemLocale($request)
    {
        if ($request->session()->has('locale')) {
            $this->setLocale(session('locale'));
        } else {
            $this->setDefaultLocale();
        }
    }

    /**
     * @param  \Illuminate\Http\Request $request
     * @return string|null
     */
    protected function parseLocale($request)
    {
        $locale = $request->server('HTTP_ACCEPT_LANGUAGE');
        $locale = substr($locale, 0, strpos($locale, ',') ?: strlen($locale));
        $this->setLocale($locale);
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->has('lang')) {
            $this->setLocale($request->get('lang'));
        } elseif (auth()->check()) {
            $this->setUserLocale();
        } else {
            $this->setSystemLocale($request);
        }
        // set from the HTTP_ACCEPT_LANGUAGE
        $this->parseLocale($request);

        return $next($request);
    }
}
