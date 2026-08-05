<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Personil;
use App\Models\KompetensiPersonil;

class SdmController extends Controller
{
    public function index()
    {
        $personil = Personil::all();
        return view('sdm.index', compact('personil'));
    }

    public function kompetensi()
    {
        $kompetensi = KompetensiPersonil::with('personil')->get();
        return view('sdm.kompetensi', compact('kompetensi'));
    }
}