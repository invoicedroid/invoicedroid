<?php

/*
|--------------------------------------------------------------------------
| Web Routes used only for the admin app - nothing more
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['language']], function() {
    Route::get('{path}', 'FrontendController@show')->where('path', '(.*)')->name('admin.dashboard');
});
