<?php

use Illuminate\Support\Facades\Route;

Route::post('dropzoneProyecto', 'proyectos\ProyectoController@uploadFiles');

Route::post('/general/getCiudades', 'Ajax\SelectController@getCiudades');
Route::post('/general/getProvincias', 'Ajax\SelectController@getProvincias');
Route::post('/general/getEmpresas', 'Ajax\SelectController@getEmpresas');
