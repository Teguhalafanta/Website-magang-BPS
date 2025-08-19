<?php

use App\Http\Controllers\dashboardController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\registerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MahasiswaController;

//Route::get('/', function () {
//    return view('kerangka.master');
//});

Route::get('/dashboard', [dashboardController::class, 'index']);

Route::post('/logout', [loginController::class, 'logout'])->name('logout');

Route::get('/', [loginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/log', [loginController::class, 'login'])->name('login.store');

Route::get('/register', [registerController::class, 'index'])->name('register');
Route::post('/regist', [registerController::class, 'store'])->name('register.store');

// mahasiswa
Route::get('/data-mahasiswa', [mahasiswaController::class, 'index'])->name('mahasiswa.index');