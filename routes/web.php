<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

});


Route::middleware([\App\Http\Middleware\FirebaseAuthMiddleware::class])->group(function () {

    Route::get('/admin', function () {
        return redirect()->route('admin.dashboard'); 
    });

    Route::get('/admin/dashboard', [DashboardController::class, 'dashboard'])->name('admin.dashboard');
});