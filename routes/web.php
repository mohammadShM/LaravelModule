<?php

// use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/home', 'HomeController@index')->name('home');
