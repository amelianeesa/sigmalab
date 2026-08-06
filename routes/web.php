<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlatController;
use App\Http\Controllers\BarangController;

Route::get('/', function () {
    return view('dashboard');
});

Route::resource('alat', AlatController::class);
Route::resource('barang', BarangController::class);
