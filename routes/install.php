<?php

Route::group(['middleware' => 'install'], function() {
    Route::get('/', 'Install\Requirements@show')->name('install.index');
    Route::get('/requirements', 'Install\Requirements@show')->name('install.requirements');
    Route::get('/language', 'Install\Languages@create')->name('install.language');
    Route::post('/language', 'Install\Languages@store');
});

