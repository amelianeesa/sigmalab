<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Personil;
use App\Models\KompetensiPersonil;

class SdmController extends Controller
{
   public function index()
{
    return view('dashboard'); // Mengarahkan langsung ke dashboard.blade.php
}

    public function kompetensi()
    {
        $kompetensi = KompetensiPersonil::with('personil')->get();
        return view('sdm.kompetensi', compact('kompetensi'));
    }
}