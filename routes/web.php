<?php

use App\Http\Controllers\dashboardController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\mahasiswaController;
use App\Http\Controllers\registerController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return view('kerangka.master');
//});

Route::get('/dashboard', [dashboardController::class, 'index'])->middleware('auth');
Route::get('user/profile/page', [dashboardController::class, 'userProfile'])->name('user/profile/page')->middleware('auth'); 

Route::post('/logout', [loginController::class, 'logout'])->name('logout');

Route::get('/', [loginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/log', [loginController::class, 'login'])->name('login.store');

Route::get('/register', [registerController::class, 'index'])->name('register');
Route::post('/regist', [registerController::class, 'store'])->name('register.store');

// mahasiswa
Route::get('/data-mahasiswa', [mahasiswaController::class, 'index'])->name('mahasiswa.index');
Route::get('/create-mahasiswa', [mahasiswaController::class, 'create'])->name('mahasiswa.create');
Route::post('/mahasiswa', [mahasiswaController::class, 'store'])->name('mahasiswa.store');

// profile
Route::get('/profile', [ProfileController::class, 'profile'])->name('profile')->middleware('auth');
Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');