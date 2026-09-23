<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\RiwayatTindakLanjut;
use App\Models\Alat;
use App\Models\Barang;
use App\Models\Personil;
use App\Models\PermintaanPengadaan;
use App\Models\Kegiatan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\PengingatSetengahTargetMail;
use App\Mail\KeterlambatanPengadaanMail;

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

        $pengadaanAktif = PermintaanPengadaan::with(['barang', 'pemohon'])
            ->whereNotIn('status', ['selesai', 'ditolak', 'batal'])
            ->orderBy('created_at', 'desc')
            ->get();        
        
        $emailGa = User::whereHas('role', fn($q) => $q->where('nama_role', 'GA'))
            ->pluck('email')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        $emailKoor = User::whereHas('role', fn($q) => $q->where('nama_role', 'Koordinator Laboratorium'))
            ->pluck('email')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

            foreach ($pengadaanAktif as $p) {
                $tglBuat = Carbon::parse($p->created_at);
                $sekarang = Carbon::now();
                
                $diff = $tglBuat->diff($sekarang);
                $formatHariBerjalan = '';
                if ($diff->y > 0) { $formatHariBerjalan .= $diff->y . ' tahun '; }
                if ($diff->m > 0) { $formatHariBerjalan .= $diff->m . ' bulan '; }
                if ($diff->d > 0 || $formatHariBerjalan == '') { $formatHariBerjalan .= $diff->d . ' hari'; }
    
                $hariBerjalanAngka = round($tglBuat->diffInDays($sekarang));
                $totalHariTarget = ($p->target_tahun * 365) + ($p->target_bulan * 30) + $p->target_hari;
                if ($totalHariTarget <= 0) { $totalHariTarget = 1; }
                $setengahTarget = $totalHariTarget / 2;
    
                if (!empty($emailGa)) {
                    $primaryGa = $emailGa[0]; 
                    $sisaGa = array_slice($emailGa, 1);
                    $cleanKoor = array_diff($emailKoor, $emailGa); 
                    $ccRecipients = array_merge($sisaGa, $cleanKoor);
    
                    $cacheKeyTerlambat = 'email_terlambat_' . $p->permintaan_pengadaan_id . '_' . date('Y-m-d');
                    $cacheKeySetengah = 'email_setengah_' . $p->permintaan_pengadaan_id . '_' . date('Y-m-d');
    
                    if ($hariBerjalanAngka >= $totalHariTarget && $p->status != 'selesai') {
                        if (!Cache::has($cacheKeyTerlambat)) {
                            try {
                                Mail::to($primaryGa)
                                    ->cc($ccRecipients)
                                    ->send(new KeterlambatanPengadaanMail($p, $formatHariBerjalan));
                                
                                Cache::put($cacheKeyTerlambat, true, now()->addDay());
                            } catch (\Exception $e) {
                            }
                        }
                    } 
                    elseif ($hariBerjalanAngka >= $setengahTarget && $hariBerjalanAngka < $totalHariTarget && in_array($p->status, ['disetujui', 'menunggu_ga'])) {
                        if (!Cache::has($cacheKeySetengah)) {
                            try {
                                Mail::to($primaryGa)
                                    ->send(new PengingatSetengahTargetMail($p, $formatHariBerjalan));
                                
                                Cache::put($cacheKeySetengah, true, now()->addDay());
                            } catch (\Exception $e) {
                            }
                        }
                    }
                }
            }

        return view('dashboard-index', compact(
            'role',
            'outliers',
            'tenggatKalibrasi',
            'stokTipis',
            'sertifikasiHampirHabis',
            'pengadaanPending',
            'barangExp',
            'kegiatanBerjalan',
            'pengadaanAktif'
        ));
    }
}