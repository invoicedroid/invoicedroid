<?php

Route::group(['middleware' => 'install', 'guest'], function() {
    Route::get('/', 'Install\Requirements@show')->name('install.index');
    Route::get('/requirements', 'Install\Requirements@show')->name('install.requirements');
    Route::get('/language', 'Install\Languages@create')->name('install.language');
    Route::post('/language', 'Install\Languages@store');
    Route::get('/database', 'Install\Database@create')->name('install.database');
    Route::post('/database', 'Install\Database@store');
    Route::get('/settings', 'Install\Settings@create')->name('install.settings');
    Route::post('/settings', 'Install\Settings@store');
});

