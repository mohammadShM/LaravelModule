<?php

Route::group(['namespace' => 'Mshm\Course\Http\Controllers',
        'middleware' => ['web', 'auth', 'verified']]
    , function ($router) {
        $router->get('courses/{course}/lessons', 'LessonController@create')
            ->name('lessons.create');
        $router->post('courses/{course}/lessons', 'LessonController@store')
            ->name('lessons.store');
        $router->delete('courses/{course}/lessons/{lesson}', 'LessonController@destroy')
            ->name('lessons.destroy');
        $router->delete('courses/{course}/lessons/', 'LessonController@destroyMultiple')
            ->name('lessons.destroyMultiple');
        $router->patch('lessons/{lessons}/accept', 'LessonController@accept')
            ->name('lessons.accept');
        $router->patch('lessons/{lessons}/reject', 'LessonController@reject')
            ->name('lessons.reject');
        $router->patch('lessons/{lessons}/lock', 'LessonController@lock')
                   ->name('lessons.lock');
        $router->patch('lessons/{lessons}/unlock', 'LessonController@unlock')
                   ->name('lessons.unlock');
    });

