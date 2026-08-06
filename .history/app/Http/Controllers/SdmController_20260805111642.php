<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Personil;
use App\Models\KompetensiPersonil;

class SdmController extends Controller
{
    // Menampilkan halaman Dashboard utama setelah login
public function index()
{
    return view('dashboard-index'); // Sesuaikan dengan nama file Blade Anda
}
    // Menampilkan halaman Tabel Data Personil SDM (/sdm)
    public function sdmIndex()
    {
        $personil = Personil::all();
        return view('sdm.index', compact('personil'));
    }

    // Menampilkan halaman Matriks Kompetensi (/sdm/kompetensi)
    public function kompetensi()
    {
        $kompetensi = KompetensiPersonil::with('personil')->get();
        return view('sdm.kompetensi', compact('kompetensi'));
    }
}