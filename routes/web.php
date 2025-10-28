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
use App\Http\Controllers\Pembimbing\BimbinganController;
use App\Http\Controllers\Pembimbing\PenilaianController;
use App\Http\Controllers\Admin\PelajarController as AdminPelajarController;
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;
use App\Http\Controllers\Admin\AssignPembimbingController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\Admin\LaporanAdminController;
use App\Http\Controllers\ProdukMagangController;
use App\Models\Presensi;

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
    Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

        // Pengajuan
        Route::get('/pengajuan', [PengajuanController::class, 'index'])->name('pengajuan.index');
        Route::put('/pengajuan/{id}/update-status', [PengajuanController::class, 'updateStatus'])->name('pengajuan.updateStatus');
        Route::put('/pengajuan/{id}', [PengajuanController::class, 'update'])->name('pengajuan.update');
        Route::delete('/pengajuan/{id}', [PengajuanController::class, 'destroy'])->name('pengajuan.destroy');

        // Kegiatan
        Route::get('/kegiatan', [KegiatanController::class, 'adminIndex'])->name('kegiatan.index');

        // Presensi
        Route::get('/presensi', [App\Http\Controllers\PresensiController::class, 'index'])->name('presensi.index');

        // Assign Pembimbing
        Route::get('/assign-pembimbing', [AssignPembimbingController::class, 'index'])
            ->name('assignpembimbing.view');

        Route::post('/assign-pembimbing/{id}', [AssignPembimbingController::class, 'assign'])
            ->name('assignpembimbing.assign');

        Route::get('/laporan', [LaporanAdminController::class, 'index'])->name('laporan.index');

        Route::get('/sertifikat', function () {
            return view('admin.sertifikat.index');
        })->name('sertifikat.index');

        // PRODUK MAGANG
        Route::resource('produk', App\Http\Controllers\ProdukMagangController::class)->names([
            'index' => 'produk.index',
            'create' => 'produk.create',
            'store' => 'produk.store',
            'edit' => 'produk.edit',
            'update' => 'produk.update',
            'destroy' => 'produk.destroy'
        ]);
    });

    // -------- PEMBIMBING --------
    Route::prefix('pembimbing')->middleware(['auth', 'role:pembimbing'])->name('pembimbing.')->group(function () {

        // Dashboard Pembimbing
        Route::get('/dashboard', [DashboardController::class, 'pembimbing'])->name('dashboard');

        // Daftar Bimbingan
        Route::get('/bimbingan', [BimbinganController::class, 'index'])->name('bimbingan');

        // Kegiatan
        Route::get('/kegiatan', [KegiatanController::class, 'index'])->name('kegiatan');

        // Presensi
        Route::get('/presensi', [PresensiController::class, 'index'])->name('presensi');

        // Penilaian
        Route::get('/penilaian', [PenilaianController::class, 'index'])->name('penilaian');

        Route::get('/assign-pembimbing', [AdminPelajarController::class, 'assignView'])->name('assignpembimbing.view');
        Route::post('/assign-pembimbing/{id}', [AdminPelajarController::class, 'assignPembimbing'])->name('assignpembimbing.assign');

        // PRODUK MAGANG
        Route::get('/produk', [ProdukMagangController::class, 'index'])->name('produk.index');
    });


    // -------- PELAJAR --------
    Route::prefix('pelajar')->middleware(['auth'])->name('pelajar.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'pelajar'])->name('dashboard');

        // Route profil pelajar
        Route::get('/profile', [ProfileController::class, 'show'])->name('pelajar.profile.show');

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

        // Presensi (khusus pelajar)
        Route::get('/presensi', [PresensiController::class, 'index'])->name('presensi.index');
        Route::post('/presensi', [PresensiController::class, 'store'])->name('presensi.store');
        Route::put('/presensi/{id}', [PresensiController::class, 'update'])->name('presensi.update');

        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::post('/laporan', [LaporanController::class, 'store'])->name('laporan.store');

        // PRODUK MAGANG
        Route::resource('produk', App\Http\Controllers\ProdukMagangController::class)->names([
            'index' => 'produk.index',
            'create' => 'produk.create',
            'store' => 'produk.store',
            'edit' => 'produk.edit',
            'update' => 'produk.update',
            'destroy' => 'produk.destroy'
        ]);
    });

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
