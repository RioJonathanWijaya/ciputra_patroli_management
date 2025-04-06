<?php

use App\Http\Controllers\Api\JadwalPatroliApiController;
use App\Http\Controllers\Api\KejadianApiController;
use App\Http\Controllers\Api\NotifikasiApiController;
use App\Http\Controllers\Api\SatpamApiController;
use App\Http\Controllers\Api\SimpleNotificationController;
use App\Http\Controllers\Api\SimpleNotifikasiController;
use App\Http\Controllers\satpam\SatpamController;
use Illuminate\Support\Facades\Route;

Route::get('/satpam/{uid}', [SatpamApiController::class, 'getSatpamByUID']);
Route::get('/penugasan_patroli/{uid}', [JadwalPatroliApiController::class, 'getPenugasanByUID']);
Route::get('/kejadian', [KejadianApiController::class, 'getAllKejadian']);
Route::post('/kejadian/save', [KejadianApiController::class, 'saveKejadian']);

Route::post('/satpam/update-fcm-token', [SatpamController::class, 'updateFcmToken']);
Route::get('/satpam/{id}/fcm-token', [SatpamController::class, 'getFcmToken']);


