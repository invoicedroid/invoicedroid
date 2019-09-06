<?php

namespace App\Http\Controllers\Install;

use App\Utilities\Installer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Settings extends Controller
{
    //
    public function create() {
        return view('install.settings.create');
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(Request $request) {
        // Create company
        $company = Installer::createCompany($request->company_name, $request->company_email, session('locale'));
        // Create user
        $user = Installer::createUser($request->admin_email, $request->admin_password, $request->admin_name, session('locale'), $company);
        // Make the final touches
        Installer::finalTouches();

        return response(null, 201);
    }
}
