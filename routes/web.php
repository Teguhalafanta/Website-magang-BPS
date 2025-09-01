<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginSSOController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PelajarController;

// login
// register
// dashboard (auth)
// notifikasi
// mahasiswa
// kegiatan
// pengajuan



// ================= Pengajuan =================
Route::get('/daftar-pengajuan', [PelajarController::class, 'index'])->name('pengajuan.index');

Route::middleware('auth')->group(function () {
    Route::get('/pengajuan-magang', [PelajarController::class, 'create'])->name('pelajars.create');
    Route::post('/pengajuan-magang', [PelajarController::class, 'store'])->name('pelajars.store');
});


Route::resource('mahasiswa', MahasiswaController::class);
Route::resource('kegiatan', KegiatanController::class);
Route::resource('absensi', AbsensiController::class);

// ================= Notifications =================
Route::get('/notifications', [NotificationController::class, 'index'])
    ->name('notifications.index');

Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])
    ->name('notifications.readAll');

Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])
    ->name('notifications.markAsRead');

// ================= Login  =================
Route::get('/login-sso', [LoginSSOController::class, 'index'])->name('login.sso');

// ================= Dashboard  =================
Route::get('/dashboard', [dashboardController::class, 'index'])
    ->middleware(['auth:web,dosen'])
    ->name('dashboard');

// ================= Logout Mahasiswa =================
Route::post('/logout', [loginController::class, 'logout'])->name('logout');

// ================= Login Mahasiswa =================
Route::get('/', [loginController::class, 'index'])
    ->name('login')
    ->middleware('guest');
Route::post('/log', [loginController::class, 'login'])->name('login.store');

// ================= Register Mahasiswa =================
Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// ================= Mahasiswa Index =================
Route::get('/mahasiswa', [MahasiswaController::class, 'index'])
    ->name('mahasiswa.index');
