<?php

Route::group(['namespace' => 'Mshm\Course\Http\Controllers',
        'middleware' => ['web', 'auth', 'verified']]
    , function ($router) {
        $router->get('courses/{course}/lessons', 'LessonController@create')
            ->name('lessons.create');
        $router->post('courses/{course}/lessons', 'LessonController@store')
            ->name('lessons.store');
        $router->get('courses/{course}/lessons/{lesson}/edit', 'LessonController@edit')
            ->name('lessons.edit');
        $router->patch('courses/{course}/lessons/{lesson}/edit', 'LessonController@update')
            ->name('lessons.update');
        $router->delete('courses/{course}/lessons/{lesson}', 'LessonController@destroy')
            ->name('lessons.destroy');
        $router->delete('courses/{course}/lessons/', 'LessonController@destroyMultiple')
            ->name('lessons.destroyMultiple');
        $router->patch('lessons/{lessons}/accept', 'LessonController@accept')
            ->name('lessons.accept');
        $router->patch('courses/{courses}/lessons/accept-all', 'LessonController@acceptAll')
                   ->name('lessons.acceptAll');
        $router->patch('courses/{courses}/lessons/accept-multiple', 'LessonController@acceptMultiple')
            ->name('lessons.acceptMultiple');
        $router->patch('courses/{courses}/lessons/reject-multiple', 'LessonController@rejectMultiple')
            ->name('lessons.rejectMultiple');
        $router->patch('lessons/{lessons}/reject', 'LessonController@reject')
            ->name('lessons.reject');
        $router->patch('lessons/{lessons}/lock', 'LessonController@lock')
                   ->name('lessons.lock');
        $router->patch('lessons/{lessons}/unlock', 'LessonController@unlock')
                   ->name('lessons.unlock');
    });

