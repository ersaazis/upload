<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return cb()->redirect(cb()->getAdminPath('login'),'Silakan Login Terlebih Dahulu !','sucess');
});
Route::group([
    'middleware' => ['web', \ersaazis\cb\middlewares\CBBackend::class],
    'prefix' => cb()->getAdminPath(),
], function () {
    Route::get('/pilih_tema/save/{photobook_id}/{tema_photobook_id}', 'PilihTemaController@saveTema');
    Route::get('/pilih_tema/{photobook_id}/{tema_photobook_id}', 'PilihTemaController@pilihTema');
    Route::get('/photobook/save/{id}', 'AdminPhotobookController@save');
});
