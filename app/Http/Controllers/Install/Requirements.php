<?php

namespace App\Http\Controllers\Install;

use App\Utilities\Installer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class Requirements extends Controller
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show() {

        $requirements = Installer::checkEnvironmentRequirements()->getRequirements();

        if ( empty($requirements) ) {
            // Create the .env file
            if ( !File::exists(base_path('.env') ) ) {
                Installer::createEnvFile();
            }

            return redirect()->route('install.language');
        } else {
            dd( $requirements );
            return view('install.requirements.show', $requirements);
        }
    }
}
