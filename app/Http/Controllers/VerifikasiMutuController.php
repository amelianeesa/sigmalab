<?php

namespace App\Http\Controllers;

use App\Models\SampelInhouse;
use App\Models\Kegiatan;

class VerifikasiMutuController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        // 1. Quick stats for the portal cards
        $inhouseCount = SampelInhouse::where('status', 'aktif')->count();
        $crmCount = Kegiatan::whereHas('hasilUji', fn($q) => $q->where('jenis_kontrol', 'crm'))->count();

        // 2. Fetch Kegiatan for the data table
        $filterJenis = $request->input('jenis_kegiatan');
        $filterStatus = $request->input('status_kegiatan');
        $search = $request->input('search');

        $query = Kegiatan::with(['pembuatKegiatan', 'alatDigunakan', 'personilTerlibat']);

        if ($search) {
            $query->where('kode_sampel', 'LIKE', "%{$search}%");
        }
        if ($filterJenis) {
            $query->where('jenis_kegiatan', $filterJenis);
        }
        if ($filterStatus) {
            $query->where('status_kegiatan', $filterStatus);
        }

        $kegiatans = $query->latest()->paginate(10);

        return view('verifikasi-mutu.index', compact('inhouseCount', 'crmCount', 'kegiatans', 'filterJenis', 'filterStatus', 'search'));
    }
}
