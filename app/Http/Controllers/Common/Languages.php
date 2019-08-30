<?php

namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Languages extends Controller
{
    //
    public function show($locale)
    {
        $data = [
            'home' => 'Home'
        ];

        return response()->json(['data' => $data]);
    }
}
