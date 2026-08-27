<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RiwayatTindakLanjut;
use App\Models\Alat;
use App\Models\Barang;
use App\Models\Personil;
use App\Models\PermintaanPengadaan;
use App\Models\Kegiatan;
use App\Models\RiwayatKalibrasi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $role = Auth::user()->role->nama_role ?? '';

        $outliers = RiwayatTindakLanjut::whereIn('status_tindak_lanjut', ['belum_ditindaklanjuti', 'dalam_investigasi'])->count();

        $tenggatKalibrasi = Alat::whereHas('riwayatKalibrasi', function($query) {
            $query->where('tgl_akhir', '<=', Carbon::now()->addDays(180));
        })->count();

        $stokTipis = Barang::whereColumn('saldo_akhir', '<', 'minimal_stok')->count();

        $sertifikasiHampirHabis = Personil::where('status_aktif', true)
            ->whereHas('kompetensi', function ($query) {
                $query->whereNotNull('tanggal_berakhir')
                    ->where('tanggal_berakhir', '<=', Carbon::now()->addMonths(6));
            })->count();

        $pengadaanPending = PermintaanPengadaan::where('status', 'diajukan')->count();

        $barangExp = Barang::whereNotNull('tgl_exp')->where('tgl_exp', '<=', Carbon::now()->addDays(180))->count();

        $kegiatanBerjalan = Kegiatan::whereIn('status_kegiatan', ['draft', 'berjalan'])->count();

        return view('dashboard-index', compact(
            'role',
            'outliers',
            'tenggatKalibrasi',
            'stokTipis',
            'sertifikasiHampirHabis',
            'pengadaanPending',
            'barangExp',
            'kegiatanBerjalan'
        ));
    }
}