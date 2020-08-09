<?php


Route::group(['namespace' => 'Mshm\Course\Http\Controllers', 'middleware' => ['web', 'auth', 'verified']]
    , function ($router) {
        $router->resource('courses', 'CourseController');
    });

