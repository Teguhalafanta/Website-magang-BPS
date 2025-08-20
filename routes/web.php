<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\loginController;

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RegisterDosenController;
use App\Http\Controllers\LoginDosenController;


// ================= Dashboard (sementara satu halaman untuk semua role) =================
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


// ================= Halaman Opsi Sign Up =================
Route::get('/signup', function () {
    return view('auth.signup_opsi');
})->name('signup');

// ================= Register Mahasiswa =================
Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// ================= Register Dosen =================
Route::get('/register/dosen', [RegisterDosenController::class, 'index'])->name('register.dosen');
Route::post('/register/dosen', [RegisterDosenController::class, 'store'])->name('register.dosen.store');

// ================= Login Dosen =================
Route::get('/login/dosen', [LoginDosenController::class, 'index'])
    ->name('login.dosen')
    ->middleware('guest');
Route::post('/login/dosen', [LoginDosenController::class, 'store'])->name('login.dosen.store');

// ================= Logout Dosen =================
Route::post('/logout/dosen', [LoginDosenController::class, 'logout'])->name('logout.dosen');

// ================= Mahasiswa Index =================
Route::get('/mahasiswa', [App\Http\Controllers\MahasiswaController::class, 'index'])
    ->name('mahasiswa.index');
