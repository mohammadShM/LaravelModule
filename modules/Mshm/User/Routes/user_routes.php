<?php

Route::group(['namespace' => 'Mshm\User\Http\Controllers', 'middleware' => 'web'], function (Route $router) {
    // Auth::routes(['verify' => true]);
    // override route ===============================================================================
    /** @var Route $router */
    Route::post('/email/verify', 'Auth\VerificationController@verify')
        ->name('verification.verify');
    $router::post('/email/resend', 'Auth\VerificationController@resend')
        ->name('verification.resend');
    $router::get('/email/verify', 'Auth\VerificationController@show')
        ->name('verification.notice');
    // login =========================================================================================
    $router::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
    $router::post('/login', 'Auth\LoginController@login')->name('login');
    // logout =========================================================================================
    $router::post('/logout', 'Auth\LoginController@logout')->name('logout');
    // reset password =================================================================================
    $router::get('/password/reset', 'Auth\ForgotPasswordController@showVerifyCodeRequestForm')
        ->name('password.request');
    $router::get('/password/reset/send', 'Auth\ForgotPasswordController@sendVerifyCodeEmail')
        ->name('password.sendVerifyCodeEmail');
    $router::post('/password/reset/check-verify-code', 'Auth\ForgotPasswordController@checkVerifyCode')
        ->name('password.checkVerifyCode')
        ->middleware('throttle:5,1');
    $router::get('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')
        ->name('password.email');
    $router::post('/password/reset', 'Auth\ResetPasswordController@reset')
        ->name('password.update');
    $router::post('/password/reset', 'Auth\ResetPasswordController@showResetForm')
        ->name('password.reset');
    // register ==========================================================================================
    $router::get('/register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    $router::post('/register', 'Auth\RegisterController@register')->name('register');
});
