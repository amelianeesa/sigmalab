<?php

namespace App\Http\Controllers;

use App\Mail\KeterlambatanPengadaanMail;
use App\Mail\PengingatSetengahTargetMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\QcHarian;
use App\Models\QcCrm;
use App\Models\QcUjiBandingParameter;
use App\Models\RiwayatTindakLanjut;
use App\Models\Alat;
use App\Models\Barang;
use App\Models\Kegiatan;
use App\Models\PermintaanPengadaan;
use App\Models\Personil;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class DashboardController extends Controller
{
    public function index()
    {
        $role = Auth::user()->role->nama_role ?? '';

        $tenggatKalibrasi = Alat::with('riwayatKalibrasi')
            ->get()
            ->filter(function ($alat) {
                $tglAkhirTerbaru = $alat->riwayatKalibrasi->max('tgl_akhir');

                return $tglAkhirTerbaru
                    && Carbon::parse($tglAkhirTerbaru)->lte(Carbon::now()->addMonths(6));
            })
            ->count();
        // $tenggatKalibrasi = Alat::whereHas('riwayatKalibrasi', function($query) {
        //     $query->where('tgl_akhir', '<=', Carbon::now()->addDays(180));
        // })->count();

        $stokTipis = Barang::whereColumn('saldo_akhir', '<', 'minimal_stok')->count();

        $sertifikasiHampirHabis = Personil::where('status_aktif', true)
            ->whereHas('kompetensi', function ($query) {
                $query->whereNotNull('tanggal_berakhir')
                    ->where('tanggal_berakhir', '<=', Carbon::now()->addMonths(6));
            })->count();

        // Hitung pengajuan pengadaan yang masih menunggu persetujuan, disesuaikan
        // dengan tahap siapa yang login (Koordinator Lab -> menunggu_koordinator,
        // GA Officer -> menunggu_ga). Role lain melihat total kedua tahap sebagai gambaran umum.
        $statusPendingUntukRole = match ($role) {
            \App\Enums\PeranPengguna::KOORDINATOR_LAB->value => 'menunggu_koordinator',
            \App\Enums\PeranPengguna::GA_OFFICER->value => 'menunggu_ga',
            default => null,
        };

        $pengadaanPending = $statusPendingUntukRole
            ? PermintaanPengadaan::where('status', $statusPendingUntukRole)->count()
            : PermintaanPengadaan::whereIn('status', ['menunggu_koordinator', 'menunggu_ga'])->count();

        $barangExp = Barang::whereNotNull('tgl_exp')
            ->where('tgl_exp', '<=', Carbon::now()->addDays(180))
            ->count();

        $kegiatanBerjalan = Kegiatan::whereIn('status_kegiatan', ['draft', 'berjalan'])->count();

        $statusArsip = ['selesai', 'ditolak', 'ditolak_koordinator', 'ditolak_ga', 'batal'];

        $relasiPengadaan = ['barang', 'pemohon.role', 'penyetuju', 'logs.user'];

        $pengadaanAktif = PermintaanPengadaan::with($relasiPengadaan)
            ->whereNotIn('status', $statusArsip)
            ->orderBy('created_at', 'desc')
            ->get();

        $pengadaanSelesai = PermintaanPengadaan::with($relasiPengadaan)
            ->whereIn('status', $statusArsip)
            ->orderBy('created_at', 'desc')
            ->limit(100)
            ->get();

        $emailGa = User::whereHas('role', fn ($q) => $q->where('nama_role', 'GA'))
            ->pluck('email')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        $emailKoor = User::whereHas('role', fn ($q) => $q->where('nama_role', 'Koordinator Laboratorium'))
            ->pluck('email')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        if (!empty($emailGa)) {
            $primaryGa = $emailGa[0];
            $ccRecipients = array_merge(
                array_slice($emailGa, 1),
                array_diff($emailKoor, $emailGa)
            );
            $sekarang = Carbon::now();

            foreach ($pengadaanAktif as $p) {
                $tglBuat = Carbon::parse($p->created_at);

                $diff = $tglBuat->diff($sekarang);
                $formatHariBerjalan = '';
                if ($diff->y > 0) {
                    $formatHariBerjalan .= $diff->y . ' tahun ';
                }
                if ($diff->m > 0) {
                    $formatHariBerjalan .= $diff->m . ' bulan ';
                }
                if ($diff->d > 0 || $formatHariBerjalan === '') {
                    $formatHariBerjalan .= $diff->d . ' hari';
                }

                $hariBerjalan = (int) $tglBuat->diffInDays($sekarang);
                $totalHariTarget = max((int) $p->target_hari, 1);
                $setengahTarget = $totalHariTarget / 2;

                $cacheKeyTerlambat = 'email_terlambat_' . $p->permintaan_id . '_' . date('Y-m-d');
                $cacheKeySetengah = 'email_setengah_' . $p->permintaan_id . '_' . date('Y-m-d');

                if ($hariBerjalan >= $totalHariTarget) {
                    if (!Cache::has($cacheKeyTerlambat)) {
                        try {
                            Mail::to($primaryGa)
                                ->cc($ccRecipients)
                                ->send(new KeterlambatanPengadaanMail($p, $formatHariBerjalan));

                            Cache::put($cacheKeyTerlambat, true, now()->addDay());
                        } catch (\Exception $e) {
                            report($e);
                        }
                    }
                } elseif (
                    $hariBerjalan >= $setengahTarget
                    && in_array($p->status, ['disetujui', 'menunggu_ga'])
                ) {
                    if (!Cache::has($cacheKeySetengah)) {
                        try {
                            Mail::to($primaryGa)
                                ->send(new PengingatSetengahTargetMail($p, $formatHariBerjalan));

                            Cache::put($cacheKeySetengah, true, now()->addDay());
                        } catch (\Exception $e) {
                            report($e);
                        }
                    }
                }
            }
        }

        $qcInhouseOutliers = QcHarian::with(['parameterUji'])
            ->where('status_evaluasi', 'outlier')
            ->where('status_investigasi', 'menunggu_investigasi')
            ->get()->map(function($item) {
                return [
                    'modul' => 'QC In-House',
                    'parameter' => $item->parameterUji->nama_parameter ?? '-',
                    'tanggal' => $item->tanggal_uji,
                    'url' => route('qc-harian.investigasi', $item->id)
                ];
            });

      
        $qcCrmOutliers = QcCrm::with(['parameterUji'])
            ->where('status_evaluasi', 'outlier')
            ->where('status_investigasi', 'menunggu_investigasi')
            ->get()->map(function($item) {
                return [
                    'modul' => 'QC CRM',
                    'parameter' => $item->parameterUji->nama_parameter ?? '-',
                    'tanggal' => $item->tanggal_uji,
                    'url' => route('qc-crm.investigasi', $item->id)
                ];
            });

        $qcUjiBandingOutliers = QcUjiBandingParameter::with(['parameterUji'])
            ->where('status_evaluasi', 'outlier')
            ->where('status_investigasi', 'menunggu_investigasi')
            ->get()->map(function($item) {
                return [
                    'modul' => 'QC Uji Banding',
                    'parameter' => $item->parameterUji->nama_parameter ?? '-',
                    'tanggal' => $item->updated_at->format('Y-m-d'),
                    'url' => route('qc-uji-banding.investigasi', ['id' => $item->qc_uji_banding_id, 'param_id' => $item->id])
                ];
            });

        
        $daftarQcOutlier = $qcInhouseOutliers->concat($qcCrmOutliers)->concat($qcUjiBandingOutliers)
            ->sortByDesc('tanggal')->take(15)->values();
        
        $totalQcOutlier = $qcInhouseOutliers->count() + $qcCrmOutliers->count() + $qcUjiBandingOutliers->count();

        return view('dashboard-index', compact(
            'role',
            'tenggatKalibrasi',
            'stokTipis',
            'sertifikasiHampirHabis',
            'pengadaanPending',
            'barangExp',
            'kegiatanBerjalan',
            'pengadaanAktif',
            'pengadaanSelesai',
            'daftarQcOutlier',
            'totalQcOutlier'
        ));
    }
}