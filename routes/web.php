<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LoginSSOController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\PelajarController;
use App\Http\Controllers\Admin\PengajuanController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;

// ================= PUBLIC (Guest) =================
Route::get('/', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/log', [LoginController::class, 'login'])->name('login.store');

Route::get('/login-sso', [LoginSSOController::class, 'index'])->name('login.sso');

Route::get('/register', [RegisterController::class, 'index'])->name('register')->middleware('guest');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store')->middleware('guest');

// ================= PROTECTED (Auth) =================
Route::middleware('auth')->group(function () {
    // Dashboard otomatis redirect sesuai role
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ================= ADMIN =================
    Route::prefix('admin')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
        Route::get('/pengajuan', [PengajuanController::class, 'index'])->name('admin.pengajuan.index');
        Route::resource('pelajar', PelajarController::class);
    });

    // ================= PELAJAR =================
    Route::prefix('pelajar')->middleware('role:pelajar')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'pelajar'])->name('pelajar.dashboard');

        // Pengajuan magang (pelajar hanya bisa kelola miliknya sendiri)
        Route::get('/pengajuan/create', [PelajarController::class, 'create'])->name('pelajar.pengajuan.create');
        Route::post('/pengajuan', [PelajarController::class, 'store'])->name('pelajar.pengajuan.store');
        Route::get('/pengajuan', [PelajarController::class, 'index'])->name('pelajar.pengajuan.index');

        // Kegiatan & Absensi
        Route::resource('kegiatan', KegiatanController::class);
        Route::get('/kegiatan/harian', [KegiatanController::class, 'harian'])->name('kegiatan.harian');
        Route::get('/kegiatan/bulanan', [KegiatanController::class, 'bulanan'])->name('kegiatan.bulanan');
        Route::resource('absensi', AbsensiController::class);
    });

    // Profil
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/magang/update', [ProfileController::class, 'updateMagang'])->name('magang.update');
    Route::put('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.updatePhoto');

    // Notifikasi
    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');
    Route::get('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])
        ->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])
        ->name('notifications.readAll');

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// ================= FALLBACK =================
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
