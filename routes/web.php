<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\PelajarController;
use App\Http\Controllers\Admin\PengajuanController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;

// ================== GUEST ==================
Route::get('/', [AuthController::class, 'index'])->name('login')->middleware('guest');
Route::post('/log', [AuthController::class, 'login'])->name('login.store')->middleware('guest');
Route::get('/login-sso', [AuthController::class, 'sso'])->name('login.sso')->middleware('guest');
Route::get('/register', [AuthController::class, 'register'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'store'])->name('register.store')->middleware('guest');

Route::middleware(['auth', 'role:pelajar'])->group(function () {
    Route::get('/pelajar/create', [PelajarController::class, 'create'])->name('pelajar.create');
    Route::post('/pelajar/store', [PelajarController::class, 'store'])->name('pelajar.store');
});

// ================== AUTH ==================
Route::middleware(['auth'])->group(function () {

    // Dashboard umum → redirect sesuai role
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // -------- ADMIN --------
    Route::prefix('admin')->middleware('role:admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

        // Pengajuan (lihat semua pengajuan pelajar)
        Route::get('/pengajuan', [PengajuanController::class, 'index'])->name('pengajuan.index');
        Route::put('/pengajuan/{id}/update-status', [PengajuanController::class, 'updateStatus'])->name('pengajuan.updateStatus');

        // CRUD Pelajar
        Route::get('/pengajuan/{id}/edit', [PengajuanController::class, 'edit'])->name('pengajuan.edit');
        Route::put('/pengajuan/{id}', [PengajuanController::class, 'update'])->name('pengajuan.update');
        Route::delete('/pengajuan/{id}', [PengajuanController::class, 'destroy'])->name('pengajuan.destroy');

        // CRUD Kegiatan
        Route::resource('kegiatan', KegiatanController::class)->names('kegiatan');

        // CRUD Absensi
        Route::resource('absensi', AbsensiController::class)->names('absensi');
    });


    // -------- PELAJAR --------
    Route::prefix('pelajar')->middleware('role:pelajar')->name('pelajar.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'pelajar'])->name('dashboard');

        // Pengajuan Magang
        Route::get('/pengajuan', [PelajarController::class, 'index'])->name('pengajuan.index');
        Route::get('/pengajuan/create', [PelajarController::class, 'create'])->name('pengajuan.create');
        Route::post('/pengajuan', [PelajarController::class, 'store'])->name('pengajuan.store');

        // CRUD Kegiatan (tanpa show)
        Route::resource('kegiatan', KegiatanController::class)->except(['show'])->names('kegiatan');
        Route::get('/kegiatan/harian', [KegiatanController::class, 'harian'])->name('kegiatan.harian');
        Route::get('/kegiatan/bulanan', [KegiatanController::class, 'kegiatanBulanan'])->name('kegiatan.bulanan');
        Route::get('/kegiatan/{kegiatan}/edit', [KegiatanController::class, 'edit'])->name('kegiatan.edit');
        Route::delete('/kegiatan/{kegiatan}', [KegiatanController::class, 'destroy'])->name('kegiatan.destroy');
    });

    // -------- ABSENSI (pelajar) --------
    Route::resource('absensi', AbsensiController::class)->names('absensi')->middleware('role:pelajar');

    // -------- PROFILE --------
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/magang/update', [ProfileController::class, 'updateMagang'])->name('magang.update');
    Route::put('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.updatePhoto');

    // -------- NOTIFIKASI --------
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');

    // -------- LOGOUT --------
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// ================== FALLBACK ==================
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
