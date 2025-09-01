<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PelajarController;

/*
Public Routes (tanpa login)
*/

// Login Mahasiswa
Route::get('/', [LoginController::class, 'index'])
    ->name('login')
    ->middleware('guest');
Route::post('/log', [LoginController::class, 'login'])
    ->name('login.store');

// Login SSO
Route::get('/login-sso', [LoginSSOController::class, 'index'])
    ->name('login.sso');

// Register Mahasiswa
Route::get('/register', [RegisterController::class, 'index'])
    ->name('register')
    ->middleware('guest');
Route::post('/register', [RegisterController::class, 'store'])
    ->name('register.store')
    ->middleware('guest');

// Logout
Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');

/*
| Protected Routes (wajib login)
*/
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Pengajuan Magang
    Route::get('/pengajuan-magang', [PelajarController::class, 'create'])
        ->name('pelajars.create');
    Route::post('/pengajuan-magang', [PelajarController::class, 'store'])
        ->name('pelajars.store');

    // Daftar Pengajuan (butuh login juga)
    Route::get('/daftar-pengajuan', [PelajarController::class, 'index'])
        ->name('pengajuan.index');

    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('readAll');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('markAsRead');
    });

    // Resource Controllers
    Route::resource('mahasiswa', MahasiswaController::class);
    Route::resource('kegiatan', KegiatanController::class);
    Route::resource('absensi', AbsensiController::class);
});
