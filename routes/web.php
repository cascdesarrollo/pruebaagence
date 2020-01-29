<?php

//Route::get('/', 'HomeController@index');
//Route::get('/home', 'HomeController@index')->name('home');

Route::get('/', 'DesempenoController@desempenoConsultor');
Route::get('/home', 'DesempenoController@desempenoConsultor')->name('home');

Route::post('/listadodesempeno', 'DesempenoController@consultarListadoDesempeno');
Route::post('/graficadesempeno', 'DesempenoController@consultarDatosGraficaDesempeno');
Route::post('/pizza', 'DesempenoController@consultarPizza');