<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LoginSSOController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\PelajarController;

/*
Public Routes (tanpa login)
*/

// Login Pelajar
Route::get('/', [LoginController::class, 'index'])
    ->name('login')
    ->middleware('guest');
Route::post('/log', [LoginController::class, 'login'])
    ->name('login.store');

// Login SSO
Route::get('/login-sso', [LoginSSOController::class, 'index'])
    ->name('login.sso');

// Register Pelajar
Route::get('/register', [RegisterController::class, 'index'])
    ->name('register')
    ->middleware('guest');
Route::post('/register', [RegisterController::class, 'store'])
    ->name('register.store')
    ->middleware('guest');

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

    // Daftar Pengajuan
    Route::get('/daftar-pengajuan', [PelajarController::class, 'index'])
        ->name('pengajuan.index');

    // Resource Utama Kegiatan
    Route::get('/kegiatan/harian', [KegiatanController::class, 'harian'])->name('kegiatan.harian');
    Route::get('/kegiatan/bulanan', [KegiatanController::class, 'bulanan'])->name('kegiatan.bulanan');

    // Resource Controllers
    Route::resource('pelajar', PelajarController::class);
    Route::resource('kegiatan', KegiatanController::class);
    Route::resource('absensi', AbsensiController::class);

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');
});
