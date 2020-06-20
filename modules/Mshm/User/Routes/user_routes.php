<?php

Route::group(['namespace' => 'Mshm\User\Http\Controllers', 'middleware' => 'web'], function ($router) {
    Auth::routes(['verify' => true]);
});

