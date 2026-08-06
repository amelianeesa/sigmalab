<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SdmController;
use App\Http\Controllers\AlatController;
use App\Http\Controllers\BarangController;

Route::get('/', [AuthController::class, 'showLogin']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'processLogin'])->name('login.process');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'processRegister'])->name('register.process');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard-index');
    })->name('dashboard');

    Route::get('/sdm', [SdmController::class, 'index'])->name('sdm.index');
    Route::get('/sdm/create', [SdmController::class, 'create'])->name('sdm.create');
    Route::post('/sdm', [SdmController::class, 'store'])->name('sdm.store');
    Route::get('/sdm/{id}/edit', [SdmController::class, 'edit'])->name('sdm.edit');
    Route::put('/sdm/{id}', [SdmController::class, 'update'])->name('sdm.update');
    Route::delete('/sdm/{id}', [SdmController::class, 'destroy'])->name('sdm.destroy');
    Route::patch('/sdm/{id}/aktifkan', [SdmController::class, 'activate'])->name('sdm.activate');
    Route::delete('/sdm/{id}/permanen', [SdmController::class, 'forceDestroy'])->name('sdm.force-destroy');
    
    Route::get('/sdm/{id}/kompetensi', [SdmController::class, 'kompetensiDetail'])->name('sdm.kompetensi.detail');
    Route::post('/sdm/{id}/kompetensi', [SdmController::class, 'storeKompetensi'])->name('sdm.kompetensi.store');
    Route::put('/sdm/{id}/kompetensi/{kompetensiId}', [SdmController::class, 'updateKompetensi'])->name('sdm.kompetensi.update');
    Route::delete('/sdm/{id}/kompetensi/{kompetensiId}', [SdmController::class, 'destroyKompetensi'])->name('sdm.kompetensi.destroy');
    Route::get('/sdm/{id}/cv', [SdmController::class, 'showCv'])->name('sdm.cv');

    Route::resource('alat', AlatController::class);
    Route::resource('barang', BarangController::class);
});


