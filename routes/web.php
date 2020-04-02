<?php

Route::get('/', function () {
    return view('welcome');
});

Route::get('/items', 'ItemsController@index');

Route::post('/items', 'ItemsController@store');

Route::delete('/items/{id}', 'ItemsController@destroy');