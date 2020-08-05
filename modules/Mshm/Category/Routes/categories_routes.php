<?php

Route::group(['namespace' => 'Mshm\Category\Http\Controllers', 'middleware' => ['web', 'auth', 'verified']]
    , function ($router) {
        $router->resource('categories', 'CategoryController')
            ->middleware('permission:manage categories');
    });
