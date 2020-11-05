<?php

Route::group(['namespace' => 'Mshm\Course\Http\Controllers',
        'middleware' => ['web', 'auth', 'verified']]
    , function ($router) {
        $router->get('courses/{course}/lessons', 'LessonController@create')
            ->name('lessons.create');
    });

