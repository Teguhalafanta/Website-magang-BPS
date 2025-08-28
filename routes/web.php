<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginSSOController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\MahasiswaController;

Route::resource('mahasiswa', MahasiswaController::class);

Route::resource('kegiatan', KegiatanController::class);

Route::resource('absensi', AbsensiController::class);


Route::get('/login-sso', [LoginSSOController::class, 'index'])->name('login.sso');

// ================= Dashboard  =================
Route::get('/dashboard', [dashboardController::class, 'index'])
    ->middleware(['auth:web,dosen'])
    ->name('dashboard');

// ================= Logout Mahasiswa =================
Route::post('/logout', [loginController::class, 'logout'])->name('logout');

// ================= Login Mahasiswa =================
Route::get('/', [loginController::class, 'index'])
    ->name('login')
    ->middleware('guest');
Route::post('/log', [loginController::class, 'login'])->name('login.store');

// ================= Register Mahasiswa =================
Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');


// ================= Mahasiswa Index =================
Route::get('/mahasiswa', [App\Http\Controllers\MahasiswaController::class, 'index'])
    ->name('mahasiswa.index');

    