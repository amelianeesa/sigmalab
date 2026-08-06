<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlatController;

Route::get('/', function () {
    return view('dashboard');
});

Route::resource('alat', AlatController::class);