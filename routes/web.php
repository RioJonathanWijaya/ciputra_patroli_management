<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dashboard\DashboardController;
use App\Http\Controllers\jadwal\JadwalController;
use App\Http\Controllers\kejadian\KejadianController;
use App\Http\Controllers\satpam\SatpamController;
use App\Http\Controllers\lokasi\LokasiController;
use App\Http\Controllers\manajemen\ManajemenController;
use App\Http\Controllers\PatrolRoutingController;
use App\Http\Controllers\satpam\KepalaSatpamController;
use App\Http\Controllers\patroli\PatroliController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\FirebaseAuthController;
use App\Http\Middleware\FirebaseAuthMiddleware;

// Authentication routes (no middleware)
Route::get('/login', [FirebaseAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [FirebaseAuthController::class, 'login']);
Route::match(['get', 'post'], '/logout', [FirebaseAuthController::class, 'logout'])->name('logout');

// Protected routes (with middleware)
Route::middleware([FirebaseAuthMiddleware::class])->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });

    Route::get('/admin/dashboard', [DashboardController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/api/get-routing', [PatrolRoutingController::class, 'getRoute']);

    Route::get('/admin/satpam/satpam', [SatpamController::class, 'satpam'])->name('admin.satpam.satpam');
    Route::get('/admin/lokasi/lokasi', [LokasiController::class, 'lokasi'])->name('admin.lokasi.lokasi');
    Route::get('/admin/jadwal_patroli/jadwal_patroli', [JadwalController::class, 'jadwal'])->name('admin.jadwal_patroli.jadwal_patroli');
    Route::get('/admin/kepala_satpam/kepala_satpam', [KepalaSatpamController::class, 'kepala_satpam'])->name('admin.kepala_satpam.kepala_satpam');
    Route::get('/admin/manajemen/manajemen', [ManajemenController::class, 'manajemen'])->name('admin.manajemen.manajemen');
    Route::get('/admin/kejadian/kejadian', [KejadianController::class, 'view'])->name('admin.kejadian.kejadian');

    // Profile Routes
    Route::get('/admin/profile', [FirebaseAuthController::class, 'profile'])->name('admin.profile');
    Route::post('/admin/profile/update', [FirebaseAuthController::class, 'updateProfile'])->name('admin.profile.update');

    // Patrol Routes
    Route::get('/admin/patroli/patroli', [PatroliController::class, 'patroli'])->name('admin.patroli.patroli');
    Route::delete('/admin/patroli/{id}', [PatroliController::class, 'destroy'])->name('admin.patroli.destroy');
    Route::get('/admin/patroli/{id}/detail', [PatroliController::class, 'show'])->name('admin.patroli.show');
    
    //Satpam Detail
    Route::get('admin/satpam/{id}/detail', [SatpamController::class, 'detail'])->name('admin.satpam.detail');

    //Kepala Satpam Detail
    Route::get('admin/kepala_satpam/{id}/detail', [KepalaSatpamController::class, 'detail'])->name('admin.kepala_satpam.detail');

    // Satpam Add
    Route::get('admin/satpam/create', [SatpamController::class, 'create'])->name('admin.satpam.create');
    Route::post('admin/satpam/store', [SatpamController::class, 'store'])->name('admin.satpam.store');

    // Manajemen Add
    Route::get('admin/manajemen/create', [ManajemenController::class, 'create'])->name('admin.manajemen.create');
    Route::post('admin/manajemen/store', [ManajemenController::class, 'store'])->name('admin.manajemen.store');
    Route::get('admin/manajemen/{id}/edit', [ManajemenController::class, 'edit'])->name('admin.manajemen.edit');
    Route::get('admin/manajemen/{id}/detail', [ManajemenController::class, 'detail'])->name('admin.manajemen.detail');

    //Location
    Route::get('admin/lokasi/create', [LokasiController::class, 'create'])->name('admin.lokasi.create');
    Route::post('admin/lokasi/store', [LokasiController::class, 'store'])->name('admin.lokasi.store');
    Route::delete('admin/lokasi/{id}', [LokasiController::class, 'destroy'])->name('admin.lokasi.destroy');

    //Jadwal Patroli
    Route::get('admin/jadwal_patroli/create', [JadwalController::class, 'create'])->name('admin.jadwal_patroli.create');
    Route::post('admin/jadwal_patroli/store', [JadwalController::class, 'store'])->name('admin.jadwal_patroli.store');
    Route::get('admin/jadwal_patroli/{id}/edit', [JadwalController::class, 'edit'])->name('admin.jadwal_patroli.edit');
    Route::delete('admin/jadwal_patroli/{id}', [JadwalController::class, 'destroy'])->name('admin.jadwal_patroli.destroy');

    // Kejadian
    Route::get('admin/kejadian/index', [KejadianController::class, 'index'])->name('admin.kejadian.index');
    Route::get('/admin/kejadian/{id}', [KejadianController::class, 'show'])->name('admin.kejadian.show');
    Route::post('/admin/kejadian/create', [KejadianController::class, 'create'])->name('admin.kejadian.create');
    Route::post('/admin/kejadian/tindakan/store', [KejadianController::class, 'storeTindakan'])->name('admin.kejadian.saveTindakan');
    Route::delete('/admin/kejadian/delete/{id}', [KejadianController::class, 'delete'])->name('admin.kejadian.delete');
    Route::get('/≠admin/kejadian/{id}/edit', [KejadianController::class, 'edit'])->name('admin.kejadian.edit');
    Route::put('/admin/kejadian/update/{id}', [KejadianController::class, 'update'])->name('admin.kejadian.update');

    // Notification Routes
    Route::get('/admin/notifikasi', [NotificationController::class, 'notifikasi'])->name('admin.notifikasi.notifikasi');
    Route::get('/api/notifications', [NotificationController::class, 'getNotifications'])->name('api.notifications');
    Route::post('/api/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('api.notifications.mark-as-read');
    Route::post('/api/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('api.notifications.mark-all-as-read');
});
