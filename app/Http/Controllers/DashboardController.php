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

        // 1. Peringatan Outlier / Tindak Lanjut (Status: belum_ditindaklanjuti atau dalam_investigasi)
        $outliers = RiwayatTindakLanjut::whereIn('status_tindak_lanjut', ['belum_ditindaklanjuti', 'dalam_investigasi'])->count();

        // 2. Jadwal Kalibrasi Mendekati Tenggat (H-30 atau kedaluwarsa)
        // Kita hitung riwayat kalibrasi aktif terbaru dari setiap alat yang kedaluwarsa <= H-30
        $tenggatKalibrasi = Alat::whereHas('riwayatKalibrasi', function($query) {
            $query->where('tgl_akhir', '<=', Carbon::now()->addDays(30));
        })->count();

        // 3. Peringatan Stok Tipis (stok < minimal_stok)
        $stokTipis = Barang::whereColumn('saldo_akhir', '<', 'minimal_stok')->count();

        // 4. Kelengkapan Dokumen SDM (CV Kosong)
        $personilBelumLengkap = Personil::whereNull('file_cv')->count();

        // 5. Pengajuan Pengadaan (Status: diajukan)
        $pengadaanPending = PermintaanPengadaan::where('status', 'diajukan')->count();

        // 6. Barang Mendekati Kedaluwarsa (H-30)
        $barangExp = Barang::whereNotNull('tgl_exp')->where('tgl_exp', '<=', Carbon::now()->addDays(30))->count();

        // 7. Ringkasan Kegiatan Berjalan (Status: draft atau berjalan)
        $kegiatanBerjalan = Kegiatan::whereIn('status_kegiatan', ['draft', 'berjalan'])->count();

        return view('dashboard-index', compact(
            'role',
            'outliers',
            'tenggatKalibrasi',
            'stokTipis',
            'personilBelumLengkap',
            'pengadaanPending',
            'barangExp',
            'kegiatanBerjalan'
        ));
    }
}
