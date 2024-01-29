<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\BukuController;
use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\SirkulasiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('xss')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', [AuthController::class, 'user']);
        Route::get('/logout', [AuthController::class, 'logout']);

        Route::apiSingleton('buku', BukuController::class);
        Route::get('/buku/home', [BukuController::class, 'bukuHome']);

        Route::apiSingleton('banner', BannerController::class);
        Route::apiSingleton('kategori', KategoriController::class);
        Route::apiSingleton('sirkulasi', SirkulasiController::class);
        Route::get('/sirkulasi/detail/{id}', [SirkulasiController::class, 'detail']);

        Route::prefix('pengaturan')->group(function () {
            Route::apiSingleton('profile', ProfileController::class);
            Route::post('/profile/foto', [ProfileController::class, 'gantiFoto']);
            Route::post('/profile/password', [ProfileController::class, 'gantiPassword']);
        });
    });
});
