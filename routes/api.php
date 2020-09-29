<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// PEGAWAI
Route::post('/', 'Pegawai@login');
Route::get('/tampil', 'Pegawai@getAuthenticatedUser');
Route::get('/tampilAll', 'Pegawai@getAll');

// MESSAGE
Route::post('/pesan', 'Pesan@buat');
Route::put('/edit/{id}', 'Pesan@edit');
Route::delete('hapus/{id}', 'Pesan@hapus');

// WORK ORDER
Route::post('/workorder', 'WorkOrder@buat');
Route::get('/tampil_terima', 'WorkOrder@tampil_terima');
Route::get('/tampil_kirim', 'WorkOrder@tampil_kirim');
Route::post('/work_selesai/{id}', 'WorkOrder@selesai');