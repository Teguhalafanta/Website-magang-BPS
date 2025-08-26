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

// Login Mahasiswa (default)
Route::get('/', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/log', [LoginController::class, 'login'])->name('login.store');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Login Dosen
Route::get('/login/dosen', [LoginDosenController::class, 'index'])->name('login.dosen')->middleware('guest');
Route::post('/login/dosen', [LoginDosenController::class, 'store'])->name('login.dosen.store');
Route::post('/logout/dosen', [LoginDosenController::class, 'logout'])->name('logout.dosen');

// Login SSO
Route::get('/login-sso', [LoginSSOController::class, 'index'])->name('login.sso');

// Halaman register mahasiswa
Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/regist', [RegisterController::class, 'store'])->name('register.store');

// Halaman register dosen
Route::get('/register/dosen', [RegisterDosenController::class, 'index'])->name('register.dosen');
Route::post('/register/dosen', [RegisterDosenController::class, 'store'])->name('register.dosen.store');

// Halaman opsi signup
Route::get('/signup', function () {
    return view('auth.signup_opsi');
})->name('signup');

/*
|--------------------------------------------------------------------------
| Protected Routes (Require Authentication)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware(['auth:web,dosen']) // Bisa diakses mahasiswa & dosen
        ->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    // Route untuk daftar mahasiswa
    Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa.index');

    // Route untuk daftar kegiatan
    Route::get('/kegiatan', [KegiatanController::class, 'index'])->name('kegiatan.index');

    // Route untuk absensi hari ini
    Route::get('/absensi/hari-ini', [AbsensiController::class, 'hariIni'])->name('absensi.hariIni');

    /*
    |--------------------------------------------------------------------------
    | Mahasiswa
    |--------------------------------------------------------------------------
    */
    Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa.index');
    Route::post('/mahasiswa', [MahasiswaController::class, 'store'])->name('mahasiswa.store');
    Route::resource('mahasiswa', MahasiswaController::class);
    Route::get('/mahasiswa/{mahasiswa}/edit', [MahasiswaController::class, 'edit'])->name('mahasiswa.edit');
    Route::put('/mahasiswa/{mahasiswa}', [MahasiswaController::class, 'update'])->name('mahasiswa.update');
    Route::delete('/mahasiswa/{mahasiswa}', [MahasiswaController::class, 'destroy'])->name('mahasiswa.destroy');

    /*
    |--------------------------------------------------------------------------
    | Absensi
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth'])->group(function () {
        Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
        Route::get('/absensi/{id}/edit', [AbsensiController::class, 'edit'])->name('absensi.edit');
        Route::put('/absensi/{id}', [AbsensiController::class, 'update'])->name('absensi.update');
        Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');
        Route::delete('/absensi/{id}', [AbsensiController::class, 'destroy'])->name('absensi.destroy'); // opsional
    });

    // API & Utility Routes untuk Absensi
    Route::prefix('absensi')->name('absensi.')->group(function () {
        Route::get('/get-data', [AbsensiController::class, 'getAbsensiData'])->name('get-data');
        Route::get('/today-data', [AbsensiController::class, 'getTodayData'])->name('today-data');
        Route::get('/search-data', [AbsensiController::class, 'search'])->name('search');
        Route::get('/export-csv', [AbsensiController::class, 'export'])->name('export');
        Route::delete('/reset-all', [AbsensiController::class, 'reset'])->name('reset-all');
    });

    // API khusus (jangan bentrok dengan route utama Absensi)
    Route::prefix('api/absensi')->name('api.absensi.')->group(function () {
        Route::get('/stats', [AbsensiController::class, 'getStatsApi'])->name('stats');
        Route::get('/today', [AbsensiController::class, 'getTodayApi'])->name('today');
        Route::get('/recap', [AbsensiController::class, 'getRecapApi'])->name('recap');
        Route::get('/weekly-stats', [AbsensiController::class, 'getWeeklyStatsApi'])->name('weekly-stats');
        Route::get('/monthly-stats', [AbsensiController::class, 'getMonthlyStatsApi'])->name('monthly-stats');
    });

    /*
    |--------------------------------------------------------------------------
    | Kegiatan
    |--------------------------------------------------------------------------
    */
    Route::get('/kegiatan', [KegiatanController::class, 'index'])->name('kegiatan.index');
    Route::post('/kegiatan', [KegiatanController::class, 'store'])->name('kegiatan.store');
    Route::resource('kegiatan', KegiatanController::class)->middleware('auth');
    Route::get('/kegiatan/{kegiatan}/edit', [KegiatanController::class, 'edit'])->name('kegiatan.edit');
    Route::put('/kegiatan/{kegiatan}', [KegiatanController::class, 'update'])->name('kegiatan.update');
    Route::delete('/kegiatan/{kegiatan}', [KegiatanController::class, 'destroy'])->name('kegiatan.destroy');
});

/*
|--------------------------------------------------------------------------
| Fallback Route
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    if (app('auth')->check()) {
        return redirect()->route('dashboard')->with('error', 'Halaman tidak ditemukan.');
    }
    return redirect()->route('login')->with('error', 'Halaman tidak ditemukan.');
});
