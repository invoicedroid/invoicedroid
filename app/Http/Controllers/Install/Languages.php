<?php

namespace App\Http\Controllers\Install;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class Languages extends Controller
{
    //
    public function create()
    {
        return view('install.language.create');
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        Session::put('locale', $request->language);
        return redirect()->route('install.database');
    }
}
