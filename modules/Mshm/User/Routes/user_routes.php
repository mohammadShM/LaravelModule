<?php

Route::group(['namespace' => 'Mshm\User\Http\Controllers', 'middleware' => 'web'], function ($router) {
    Auth::routes(['verify' => true]);
    // override route
    Route::post('/email/verify', 'Auth\VerificationController@verify')->name('verification.verify');
});

