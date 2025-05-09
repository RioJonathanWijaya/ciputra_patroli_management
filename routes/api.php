<?php

use App\Http\Controllers\Api\JadwalPatroliApiController;
use App\Http\Controllers\Api\KejadianApiController;
use App\Http\Controllers\Api\SatpamApiController;
use App\Http\Controllers\satpam\SatpamController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\PatroliApiController;

Route::get('/satpam/{uid}', [SatpamApiController::class, 'getSatpamByUID']);
Route::get('/penugasan_patroli/{uid}', [JadwalPatroliApiController::class, 'getPenugasanByUID']);
Route::get('/kejadian', [KejadianApiController::class, 'getAllKejadian']);
Route::post('/kejadian/save', [KejadianApiController::class, 'saveKejadian']);
Route::get('/patroli/stats/{uid}', [JadwalPatroliApiController::class, 'getStatsPatroliSatpam']);

Route::post('/satpam/update-fcm-token', [SatpamController::class, 'updateFcmToken']);
Route::get('/satpam/{id}/fcm-token', [SatpamController::class, 'getFcmToken']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/recent-patroli', [PatroliApiController::class, 'getRecentPatroli']);


