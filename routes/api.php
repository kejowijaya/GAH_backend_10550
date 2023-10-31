<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FasilitasController;
use App\Http\Controllers\Api\KamarController;
use App\Http\Controllers\Api\SeasonController;
use App\Http\Controllers\Api\TarifController;
use App\Http\Controllers\Api\JenisKamarController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum', 'role:admin'])->group(function(){
    Route::post('kamar', KamarController::class . '@store');
    Route::put('kamar/{id}', KamarController::class . '@update');
    Route::delete('kamar/{id}', KamarController::class . '@destroy');
});

Route::middleware(['auth:sanctum', 'role:sm'])->group(function () {
    Route::post('tarif', [TarifController::class, 'store']);
    Route::put('tarif/{id}', [TarifController::class, 'update']);
    Route::delete('tarif/{id}', [TarifController::class, 'destroy']);

    Route::post('season', SeasonController::class . '@store');
    Route::put('season/{id}', SeasonController::class . '@update');
    Route::delete('season/{id}', SeasonController::class . '@destroy');

    Route::post('fasilitas', FasilitasController::class . '@store');
    Route::put('fasilitas/{id}', FasilitasController::class . '@update');
    Route::delete('fasilitas/{id}', FasilitasController::class . '@destroy');
});

Route::get('season', SeasonController::class . '@index');
Route::get('season/{id}', SeasonController::class . '@show');
Route::get('fasilitas', FasilitasController::class . '@index');
Route::get('kamar/{id}', KamarController::class . '@show');
Route::get('tarif', [TarifController::class, 'index']);
Route::get('tarif/{id}', [TarifController::class, 'show']);
Route::get('kamar', KamarController::class . '@index');
Route::get('fasilitas/{id}', FasilitasController::class . '@show'); 
Route::get('jenis_kamar', JenisKamarController::class . '@index');
Route::get('jenis_kamar/{id}', JenisKamarController::class . '@show');

Route::post('register', 'App\Http\Controllers\Api\AuthController@register');
Route::post('registerPegawai', 'App\Http\Controllers\Api\AuthController@registerPegawai');
Route::post('login', 'App\Http\Controllers\Api\AuthController@login');
Route::post('loginPegawai', 'App\Http\Controllers\Api\AuthController@loginPegawai');
Route::post('logout', 'App\Http\Controllers\Api\AuthController@logout');
Route::post('changePassword/{email}', 'App\Http\Controllers\Api\AuthController@changePassword');

Route::put('customer/{id}', 'App\Http\Controllers\Api\AuthController@update');

Route::get('customer/{id}', 'App\Http\Controllers\Api\AuthController@show');
Route::get('pegawai/{id}', 'App\Http\Controllers\Api\AuthController@showPegawai');

Route::get('riwayatTransaksi/{id}', 'App\Http\Controllers\Api\ReservasiController@getRiwayatTransaksi');
Route::get('reservasi/{id}', 'App\Http\Controllers\Api\ReservasiController@getDetailTransaksi');


