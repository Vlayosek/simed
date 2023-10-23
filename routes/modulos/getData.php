<?php

use Illuminate\Support\Facades\Route;

Route::post('getAreas', 'Admin\DataController@getAreas');
Route::post('getCargos', 'Admin\DataController@getCargos');
