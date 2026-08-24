<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\HasilUji;
use App\Models\RiwayatTindakLanjut;
use App\Models\ParameterUji;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportingController extends Controller
{
    public function index()
    {
        $totalKegiatan = Kegiatan::count();
        $kegiatanPerStatus = Kegiatan::select('status_kegiatan', DB::raw('count(*) as total'))
            ->groupBy('status_kegiatan')
            ->pluck('total', 'status_kegiatan')
            ->toArray();

        $totalHasilUji = HasilUji::count();
        $totalInlier = HasilUji::where('status_berketerimaan', 'inlier')->count();
        $totalOutlier = HasilUji::where('status_berketerimaan', 'outlier')->count();

        $totalTindakLanjut = RiwayatTindakLanjut::count();
        $tindakLanjutPerStatus = RiwayatTindakLanjut::select('status_tindak_lanjut', DB::raw('count(*) as total'))
            ->groupBy('status_tindak_lanjut')
            ->pluck('total', 'status_tindak_lanjut')
            ->toArray();

        $topOutlier = HasilUji::where('status_berketerimaan', 'outlier')
            ->select('parameter_uji_id', DB::raw('count(*) as jumlah_outlier'))
            ->groupBy('parameter_uji_id')
            ->orderByDesc('jumlah_outlier')
            ->limit(5)
            ->with('parameterUji')
            ->get();

        return view('reporting.index', compact(
            'totalKegiatan',
            'kegiatanPerStatus',
            'totalHasilUji',
            'totalInlier',
            'totalOutlier',
            'totalTindakLanjut',
            'tindakLanjutPerStatus',
            'topOutlier'
        ));
    }

    public function exportPdf()
    {
        $hasilUjiList = HasilUji::with(['kegiatan', 'parameterUji', 'penginput'])->orderBy('created_at', 'desc')->get();
        
        $pdf = Pdf::loadView('reporting.pdf', compact('hasilUjiList'));
        
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->download('Laporan_QC_Laboratorium_' . date('Y-m-d') . '.pdf');
    }
}
