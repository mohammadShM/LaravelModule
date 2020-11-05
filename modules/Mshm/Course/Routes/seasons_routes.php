<?php


Route::group(['namespace' => 'Mshm\Course\Http\Controllers',
        'middleware' => ['web', 'auth', 'verified']]
    , function ($router) {
        $router->patch('seasons/{season}/accept', 'SeasonController@accept')
            ->name('seasons.accept');
        $router->patch('seasons/{season}/reject', 'SeasonController@reject')
            ->name('seasons.reject');
        $router->post('season/{course}', 'SeasonController@store')->name('seasons.store');
        $router->get('season/{season}', 'SeasonController@edit')->name('seasons.edit');
        $router->patch('season/{season}', 'SeasonController@update')->name('seasons.update');
        $router->delete('season/{season}', 'SeasonController@destroy')->name('seasons.destroy');
        $router->patch('season/{season}/accept',
            'SeasonController@accept')->name('seasons.accept');
        $router->patch('season/{season}/reject',
            'SeasonController@reject')->name('seasons.reject');
        $router->patch('season/{season}/lock',
                   'SeasonController@lock')->name('seasons.lock');
        $router->patch('season/{season}/unlock',
                   'SeasonController@unlock')->name('seasons.unlock');
    });
