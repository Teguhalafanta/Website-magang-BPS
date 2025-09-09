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
Route::middleware(['auth'])->group(function () {

    // ================= ADMIN =================
    Route::prefix('admin')
        ->middleware('role:admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

            // Pengajuan
            Route::get('/pengajuan', [PengajuanController::class, 'index'])->name('pengajuan.index');

            // CRUD Pelajar
            Route::resource('pelajar', PelajarController::class)->names('pelajar');

            // CRUD Kegiatan (Admin bisa kelola semua kegiatan)
            Route::resource('kegiatan', KegiatanController::class)->names('kegiatan');

            // CRUD Absensi
            Route::resource('absensi', AbsensiController::class)->names('absensi');
        });

    // ================= PELAJAR =================
    Route::prefix('pelajar')
        ->middleware('role:pelajar')
        ->name('pelajar.')
        ->group(function () {

            // Dashboard pelajar
            Route::get('/dashboard', [DashboardController::class, 'pelajar'])->name('dashboard');

            // Pengajuan magang
            Route::get('/pengajuan', [PelajarController::class, 'index'])->name('pengajuan.index');
            Route::get('/pengajuan/create', [PelajarController::class, 'create'])->name('pengajuan.create');
            Route::post('/pengajuan', [PelajarController::class, 'store'])->name('pengajuan.store');

            // Resource Kegiatan untuk Pelajar (edit, update, delete, index, create, store)
            Route::resource('kegiatan', KegiatanController::class)
                ->except(['show']) // biasanya show tidak dipakai
                ->names('kegiatan');

            // Tambahan route khusus edit dan delete jika ingin eksplisit (tidak wajib karena sudah ada resource di atas)
            Route::get('/kegiatan/{kegiatan}/edit', [KegiatanController::class, 'edit'])->name('pelajar.kegiatan.edit');
            Route::delete('/kegiatan/{kegiatan}', [KegiatanController::class, 'destroy'])->name('pelajar.kegiatan.destroy');

            // Route khusus untuk fitur harian dan bulanan
            Route::get('kegiatan/harian', [KegiatanController::class, 'harian'])->name('kegiatan.harian');
            Route::get('kegiatan/bulanan', [KegiatanController::class, 'kegiatanBulanan'])->name('kegiatan.bulanan');
        });

    // Route resource absensi (jika pelajar boleh akses)
    Route::resource('absensi', AbsensiController::class)
        ->names('absensi')
        ->middleware('role:pelajar');

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
