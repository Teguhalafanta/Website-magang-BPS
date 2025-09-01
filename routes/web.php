<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LoginSSOController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

// Login Mahasiswa (default)
Route::get('/', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/log', [LoginController::class, 'login'])->name('login.store');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Login SSO
Route::get('/login-sso', [LoginSSOController::class, 'index'])->name('login.sso');

// Register Mahasiswa
Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// Opsi Signup
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
        ->middleware(['auth:web'])
        ->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    /*
    |--------------------------------------------------------------------------
    | Mahasiswa
    |--------------------------------------------------------------------------
    */
    Route::resource('mahasiswa', MahasiswaController::class);

    /*
    |--------------------------------------------------------------------------
    | Absensi
    |--------------------------------------------------------------------------
    */
    Route::resource('absensi', AbsensiController::class);

    // API & Utility Routes untuk Absensi
    Route::prefix('absensi')->name('absensi.')->group(function () {
        Route::get('/hari-ini', [AbsensiController::class, 'hariIni'])->name('hariIni');
        Route::get('/get-data', [AbsensiController::class, 'getAbsensiData'])->name('get-data');
        Route::get('/today-data', [AbsensiController::class, 'getTodayData'])->name('today-data');
        Route::get('/search-data', [AbsensiController::class, 'search'])->name('search');
        Route::get('/export-csv', [AbsensiController::class, 'export'])->name('export');
        Route::delete('/reset-all', [AbsensiController::class, 'reset'])->name('reset-all');
    });

    // API khusus (untuk kebutuhan grafik/dashboard)
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
    Route::resource('kegiatan', KegiatanController::class);

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
});


/*
|--------------------------------------------------------------------------
| Fallback Route
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    $user = Auth::guard('mahasiswa')->user()
        ?? Auth::guard('web')->user();

    if ($user) {
        return redirect()->route('dashboard')->with('error', 'Halaman tidak ditemukan.');
    }

    return redirect()->route('login')->with('error', 'Halaman tidak ditemukan.');
});
