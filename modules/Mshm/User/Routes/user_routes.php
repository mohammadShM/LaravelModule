<?php

// for routers with auth middleware ==================================================================================
Route::group(['namespace' => 'Mshm\User\Http\Controllers', 'middleware' => ['web', 'auth']], function ($router) {
    // Auth::routes(['verify' => true]);
    // for user roles ==============================================================================
    Route::post('users/{user}/add/role', 'UserController@addRole')
        ->name('users.addRole');
    Route::delete('users/{user}/remove/{role}/role', 'UserController@removeRole')
        ->name('users.removeRole');
    Route::patch('users/{user}/manualVerify', 'UserController@manualVerify')
        ->name('users.manualVerify');
    Route::post('users/photo', 'UserController@updatePhoto')
        ->name('users.photo');
    Route::get('edit-profile', 'UserController@profile')
        ->name('users.profile');
    Route::post('edit-profile', 'UserController@updateProfile')
        ->name('users.profile');
    Route::resource('users', 'UserController');
});
// for routers without auth middleware ===============================================================================
Route::group(['namespace' => 'Mshm\User\Http\Controllers', 'middleware' => 'web'], function ($router) {
    // override route ==============================================================================
    /** @var Route $router */
    $router->post('/email/verify', 'Auth\VerificationController@verify')
        ->name('verification.verify');
    Route::post('/email/resend', 'Auth\VerificationController@resend')
        ->name('verification.resend');
    Route::get('/email/verify', 'Auth\VerificationController@show')
        ->name('verification.notice');
    // login =========================================================================================
    Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('/login', 'Auth\LoginController@login')->name('login');
    // logout =========================================================================================
    Route::any('/logout', 'Auth\LoginController@logout')->name('logout');
    // reset password =================================================================================
    Route::get('/password/reset', 'Auth\ForgotPasswordController@showVerifyCodeRequestForm')
        ->name('password.request');
    Route::get('/password/reset/send', 'Auth\ForgotPasswordController@sendVerifyCodeEmail')
        ->name('password.sendVerifyCodeEmail');
    Route::post('/password/reset/check-verify-code', 'Auth\ForgotPasswordController@checkVerifyCode')
        ->name('password.checkVerifyCode')
        ->middleware('throttle:5,1');
    Route::get('/password/change', 'Auth\ResetPasswordController@showResetForm')
        ->name('password.showResetForm')->middleware('auth');
    Route::post('/password/change', 'Auth\ResetPasswordController@reset')
        ->name('password.update');
    // register ==========================================================================================
    Route::get('/register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    Route::post('/register', 'Auth\RegisterController@register')->name('register');
});
