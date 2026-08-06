<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SdmController;

// Halaman Utama & Autentikasi
Route::get('/', [AuthController::class, 'showLogin']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'processLogin'])->name('login.process');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'processRegister'])->name('register.process');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Middleware Auth untuk halaman privat (setelah login)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [SdmController::class, 'index'])->name('dashboard');
    Route::get('/sdm', [SdmController::class, 'sdmIndex'])->name('sdm.index'); // <-- Mengarah ke sdmIndex
    Route::get('/sdm/kompetensi', [SdmController::class, 'kompetensi'])->name('sdm.kompetensi');
});