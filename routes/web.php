<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterDosenController;
use App\Http\Controllers\LoginDosenController;
use App\Http\Controllers\LoginSSOController;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

// Halaman login (default)
Route::get('/', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/log', [LoginController::class, 'login'])->name('login.store');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Halaman register
Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/regist', [RegisterController::class, 'store'])->name('register.store');

/*
|--------------------------------------------------------------------------
| Protected Routes (Require Authentication)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [DashboardController::class, 'updateProfile'])->name('profile.update');

    // Data Mahasiswa
    Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa.index');
    Route::post('/mahasiswa', [MahasiswaController::class, 'store'])->name('mahasiswa.store');

    // Absensi Routes
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
    Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');
    Route::resource('absensi', AbsensiController::class)->except(['index', 'store']);

    // AJAX Routes untuk Absensi
    Route::prefix('absensi')->name('absensi.')->group(function () {
        Route::get('/get-data', [AbsensiController::class, 'getAbsensiData'])->name('get-data');
        Route::get('/today-data', [AbsensiController::class, 'getTodayData'])->name('today-data');
        Route::get('/search-data', [AbsensiController::class, 'search'])->name('search');
        Route::get('/export-csv', [AbsensiController::class, 'export'])->name('export');
        Route::delete('/reset-all', [AbsensiController::class, 'reset'])->name('reset-all');
    });

    // Kegiatan Routes
    Route::get('/kegiatan', [KegiatanController::class, 'index'])->name('kegiatan.index');
    Route::get('/kegiatan/create', [KegiatanController::class, 'create'])->name('kegiatan.create');
    Route::post('/kegiatan', [KegiatanController::class, 'store'])->name('kegiatan.store');
    Route::resource('kegiatan', KegiatanController::class)->middleware('auth');
    Route::get('/kegiatan/{kegiatan}/edit', [KegiatanController::class, 'edit'])->name('kegiatan.edit');
    Route::put('/kegiatan/{kegiatan}', [KegiatanController::class, 'update'])->name('kegiatan.update');
    Route::delete('/kegiatan/{kegiatan}', [KegiatanController::class, 'destroy'])->name('kegiatan.destroy');

    // API Routes untuk Absensi
    Route::prefix('api/absensi')->name('api.absensi.')->group(function () {
        Route::get('/stats', [AbsensiController::class, 'getStatsApi'])->name('stats');
        Route::get('/today', [AbsensiController::class, 'getTodayApi'])->name('today');
        Route::get('/recap', [AbsensiController::class, 'getRecapApi'])->name('recap');
        Route::get('/weekly-stats', [AbsensiController::class, 'getWeeklyStatsApi'])->name('weekly-stats');
        Route::get('/monthly-stats', [AbsensiController::class, 'getMonthlyStatsApi'])->name('monthly-stats');
    });
});

Route::fallback(function () {
    if (app('auth')->check()) {
        return redirect()->route('dashboard')->with('error', 'Halaman tidak ditemukan.');
    }

    return redirect()->route('login')->with('error', 'Halaman tidak ditemukan.');
});




Route::get('/dashboard', [dashboardController::class, 'index'])->middleware('auth');
Route::get('/login-sso', [LoginSSOController::class, 'index'])->name('login.sso');

// ================= Dashboard  =================
Route::get('/dashboard', [dashboardController::class, 'index'])
    ->middleware(['auth:web,dosen']) // Bisa diakses mahasiswa & dosen
    ->name('dashboard');

// ================= Logout Mahasiswa =================
Route::post('/logout', [loginController::class, 'logout'])->name('logout');

// ================= Login Mahasiswa =================
Route::get('/', [loginController::class, 'index'])
    ->name('login')
    ->middleware('guest');
Route::post('/log', [loginController::class, 'login'])->name('login.store');
