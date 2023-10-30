<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FasilitasController;
use App\Http\Controllers\Api\KamarController;
use App\Http\Controllers\Api\SeasonController;
use App\Http\Controllers\Api\TarifController;

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
    Route::get('kamar', KamarController::class . '@index');
    Route::post('kamar', KamarController::class . '@store');
    Route::get('kamar/{id}', KamarController::class . '@show');
    Route::put('kamar/{id}', KamarController::class . '@update');
    Route::delete('kamar/{id}', KamarController::class . '@destroy');
});

Route::middleware(['auth:sanctum', 'role:sm'])->group(function () {
    Route::get('tarif', [TarifController::class, 'index']);
    Route::post('tarif', [TarifController::class, 'store']);
    Route::get('tarif/{id}', [TarifController::class, 'show']);
    Route::put('tarif/{id}', [TarifController::class, 'update']);
    Route::delete('tarif/{id}', [TarifController::class, 'destroy']);

    Route::get('season', SeasonController::class . '@index');
    Route::post('season', SeasonController::class . '@store');
    Route::get('season/{id}', SeasonController::class . '@show');
    Route::put('season/{id}', SeasonController::class . '@update');
    Route::delete('season/{id}', SeasonController::class . '@destroy');

    Route::get('fasilitas', FasilitasController::class . '@index');
    Route::post('fasilitas', FasilitasController::class . '@store');
    Route::get('fasilitas/{id}', FasilitasController::class . '@show'); 
    Route::put('fasilitas/{id}', FasilitasController::class . '@update');
    Route::delete('fasilitas/{id}', FasilitasController::class . '@destroy');
});

Route::post('register', 'App\Http\Controllers\Api\AuthController@register');
Route::post('registerPegawai', 'App\Http\Controllers\Api\AuthController@registerPegawai');
Route::post('login', 'App\Http\Controllers\Api\AuthController@login');
Route::post('loginPegawai', 'App\Http\Controllers\Api\AuthController@loginPegawai');
Route::post('logout', 'App\Http\Controllers\Api\AuthController@logout');
Route::post('changePassword/{id}', 'App\Http\Controllers\Api\AuthController@changePassword');
Route::get('customer/{id}', 'App\Http\Controllers\Api\AuthController@get');

Route::get('riwayatTransaksi/{id}', 'App\Http\Controllers\Api\ReservasiController@getRiwayatTransaksi');
Route::get('reservasi/{id}', 'App\Http\Controllers\Api\ReservasiController@getDetailTransaksi');


