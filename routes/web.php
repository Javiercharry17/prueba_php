<?php

use Illuminate\Support\Facades\Route;

Route::get('/llamar-turno', 'TurnoController@index');
Route::post('/llamar-turno', 'TurnoController@llamarTurno');

Route::post('/llamar-turno/{id}', 'LlamadoTurnoController@llamarTurno')->name('llamar-turno');

Route::get('/', function () {
    return view('welcome');
});
