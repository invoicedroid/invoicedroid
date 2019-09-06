<?php

namespace App\Http\Controllers\Install;

use App\Utilities\Installer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Database extends Controller
{
    //
    public function create() {
        return view('install.database.create');
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function store(Request $request) {
        // Check database connection
        if (!Installer::createDbTables($request->hostname, $request->port, $request->name, $request->username, $request->password)) {
            return response()->json([
                'message' => trans('install.error.connection')
            ], 500);
        }
        return response(null, 201);
    }
}
