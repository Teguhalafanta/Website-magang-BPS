<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\PelajarController;
use App\Http\Controllers\Admin\PengajuanController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PembimbingController;

// ================== GUEST ==================
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'index'])->name('login');
    Route::post('/log', [AuthController::class, 'login'])->name('login.store');
    Route::get('/login-sso', [AuthController::class, 'sso'])->name('login.sso');
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'store'])->name('register.store');
});

// ================== AUTH ==================
Route::middleware(['auth'])->group(function () {

    // Dashboard umum → redirect sesuai role
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // -------- PROFILE --------
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/magang/update', [ProfileController::class, 'updateMagang'])->name('magang.update');
    Route::put('/profile/foto', [ProfileController::class, 'updateFoto'])->name('profile.updateFoto');

    // -------- ADMIN --------
    Route::prefix('admin')->middleware('role:admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

        // Pengajuan (lihat semua pengajuan pelajar)
        Route::get('/pengajuan', [PengajuanController::class, 'index'])->name('pengajuan.index');
        Route::put('/pengajuan/{id}/update-status', [PengajuanController::class, 'updateStatus'])->name('pengajuan.updateStatus');

        // CRUD Pengajuan
        Route::put('/pengajuan/{id}', [PengajuanController::class, 'update'])->name('pengajuan.update');
        Route::delete('/pengajuan/{id}', [PengajuanController::class, 'destroy'])->name('pengajuan.destroy');

        // CRUD Kegiatan (admin bisa kelola semua kegiatan)
        Route::resource('kegiatan', KegiatanController::class)->names('kegiatan');

        // CRUD Presensi
        Route::resource('presensi', PresensiController::class)->names('presensi');
    });

    // -------- PEMBIMBING --------
    Route::prefix('pembimbing')->middleware('role:pembimbing')->name('pembimbing.')->group(function () {

        // Dashboard Pembimbing
        Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'pembimbing'])
            ->name('dashboard');

        // Kegiatan (sementara copy dari pelajar)
        Route::get('/kegiatan', [\App\Http\Controllers\KegiatanController::class, 'index'])->name('kegiatan.index');
        Route::get('/kegiatan/create', [\App\Http\Controllers\KegiatanController::class, 'create'])->name('kegiatan.create');
        Route::post('/kegiatan', [\App\Http\Controllers\KegiatanController::class, 'store'])->name('kegiatan.store');
        Route::get('/kegiatan/{kegiatan}/edit', [\App\Http\Controllers\KegiatanController::class, 'edit'])->name('kegiatan.edit');
        Route::put('/kegiatan/{kegiatan}', [\App\Http\Controllers\KegiatanController::class, 'update'])->name('kegiatan.update');
        Route::delete('/kegiatan/{kegiatan}', [\App\Http\Controllers\KegiatanController::class, 'destroy'])->name('kegiatan.destroy');

        // Kegiatan Harian & Bulanan
        Route::get('/kegiatan/harian', [\App\Http\Controllers\KegiatanController::class, 'harian'])->name('kegiatan.harian');
        Route::get('/kegiatan/bulanan', [\App\Http\Controllers\KegiatanController::class, 'kegiatanBulanan'])->name('kegiatan.bulanan');

        // Pengajuan (sementara copy dari pelajar)
        Route::get('/pengajuan', [\App\Http\Controllers\PelajarController::class, 'index'])->name('pengajuan.index');

        // Daftar Bimbingan (baru ditambahkan)
        Route::get('/bimbingan', [\App\Http\Controllers\PembimbingController::class, 'index'])
            ->name('bimbingan.index');
    });



    // -------- PELAJAR --------
    Route::prefix('pelajar')->middleware('role:pelajar')->name('pelajar.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'pelajar'])->name('dashboard');

        // Pengajuan Magang (khusus pelajar)
        Route::get('/pengajuan', [PelajarController::class, 'index'])->name('pengajuan.index');
        Route::get('/pengajuan/create', [PelajarController::class, 'create'])->name('pengajuan.create');
        Route::post('/pengajuan', [PelajarController::class, 'store'])->name('pengajuan.store');
        Route::put('/pengajuan/{id}', [PelajarController::class, 'update'])->name('pengajuan.update');
        Route::delete('/pengajuan/{id}', [PelajarController::class, 'destroy'])->name('pengajuan.destroy');

        // CRUD Kegiatan (tanpa show, khusus pelajar)
        Route::get('/kegiatan', [KegiatanController::class, 'index'])->name('kegiatan.index');
        Route::get('/kegiatan/create', [KegiatanController::class, 'create'])->name('kegiatan.create');
        Route::post('/kegiatan', [KegiatanController::class, 'store'])->name('kegiatan.store');
        Route::get('/kegiatan/{kegiatan}/edit', [KegiatanController::class, 'edit'])->name('kegiatan.edit');
        Route::put('/kegiatan/{kegiatan}', [KegiatanController::class, 'update'])->name('kegiatan.update');
        Route::delete('/kegiatan/{kegiatan}', [KegiatanController::class, 'destroy'])->name('kegiatan.destroy');

        // Upload Bukti Dukung
        Route::post('/kegiatan/{kegiatan}/bukti', [KegiatanController::class, 'uploadBukti'])->name('kegiatan.uploadBukti');
        Route::get('/kegiatan/{kegiatan}/bukti', [KegiatanController::class, 'lihatBukti'])->name('kegiatan.lihatBukti');

        // Filter kegiatan
        Route::get('/kegiatan/harian', [KegiatanController::class, 'harian'])->name('kegiatan.harian');
        Route::get('/kegiatan/bulanan', [KegiatanController::class, 'kegiatanBulanan'])->name('kegiatan.bulanan');
    });


    // -------- ABSENSI (pelajar) --------
    Route::resource('presensi', PresensiController::class)
        ->names('presensi')
        ->middleware('role:pelajar');

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
