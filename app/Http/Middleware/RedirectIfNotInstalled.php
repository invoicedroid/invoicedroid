<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\File;

class RedirectIfNotInstalled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Check if .env file exists
        if ( File::exists(base_path('.env')) ) {
            return $next($request);
        }

        // Not installed, redirect to installation wizard
        return redirect()->route('install.requirements');
    }
}
