<?php

namespace App\Http\Controllers;

use App\Models\QcHarian;
use App\Models\SampelInhouse;
use App\Models\ParameterUji;
use App\Services\WestgardService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class QcHarianController extends Controller
{
    protected $westgard;

    public function __construct(WestgardService $westgard)
    {
        $this->westgard = $westgard;
    }

    public function index()
    {
        $activeBatch = SampelInhouse::where('status', 'aktif')->latest()->first();
        $parameters = collect();
        $recentLogs = collect();

        if ($activeBatch) {
            $parameters = $activeBatch->parameters()->where('status_parameter', 'stabil')->get();
            $recentLogs = QcHarian::where('sampel_inhouse_id', $activeBatch->sampel_inhouse_id)
                ->with(['parameterUji', 'analis'])
                ->orderBy('tanggal_uji', 'desc')
                ->orderBy('created_at', 'desc')
                ->take(50)
                ->get();
        }

        return view('qc-harian.index', compact('activeBatch', 'parameters', 'recentLogs'));
    }

    public function create()
    {
        $activeBatch = SampelInhouse::where('status', 'aktif')->latest()->first();
        if (!$activeBatch) {
            return redirect()->route('qc-harian.index')->with('error', 'Tidak ada sampel QC In-House yang berstatus aktif.');
        }

        $parameters = $activeBatch->parameters()->where('status_parameter', 'stabil')->with('parameterUji')->get();
        $alatList = \App\Models\Alat::where('status_barang', 'Baik')->orderBy('nama_alat')->get();
        $personilList = \App\Models\Personil::orderBy('nama')->get();
        $barangList = \App\Models\Barang::orderBy('nama_barang')->get();

        return view('qc-harian.create', compact('activeBatch', 'parameters', 'alatList', 'personilList', 'barangList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_uji' => 'required|date',
            'params' => 'required|array',
        ]);

        $activeBatch = SampelInhouse::where('status', 'aktif')->latest()->first();
        if (!$activeBatch) {
            return back()->with('error', 'Tidak ada batch aktif.');
        }

        $outlierParam = null;
        $outlierQch = null;
        $savedCount = 0;
        $results = [];

        foreach ($request->input('params') as $paramUjiId => $data) {
            // Hanya proses parameter yang dicentang
            if (empty($data['selected'])) continue;

            $paramUji = ParameterUji::find($paramUjiId);
            if (!$paramUji) continue;

            // Pengecekan lock (outlier menunggu investigasi)
            $locked = QcHarian::where('sampel_inhouse_id', $activeBatch->sampel_inhouse_id)
                ->where('parameter_uji_id', $paramUji->parameter_uji_id)
                ->where('status_evaluasi', 'outlier')
                ->where('status_investigasi', 'menunggu_investigasi')
                ->exists();
            if ($locked) continue;

            $d1 = (float)($data['d1'] ?? 0);
            $d2 = (float)($data['d2'] ?? 0);
            $db1 = null;
            $db2 = null;

            $pName = strtoupper($paramUji->nama_parameter);
            $needsDb = in_array($pName, ['ASH', 'VM', 'CV', 'TS', 'FC']);

            if ($needsDb) {
                $im1 = (float)($data['im_d1'] ?? 0);
                $im2 = (float)($data['im_d2'] ?? 0);
                
                if ($im1 >= 100 || $im2 >= 100) continue;

                $db1 = (100 / (100 - $im1)) * $d1;
                $db2 = (100 / (100 - $im2)) * $d2;
                $val1 = $db1;
                $val2 = $db2;
            } else {
                $val1 = $d1;
                $val2 = $d2;
            }

            $meanAcuan = $paramUji->mean;
            $sdAcuan = $paramUji->sd;
            if ($meanAcuan === null || $sdAcuan === null) continue;

            // Ambil histori 9 data terakhir untuk Westgard
            $history = QcHarian::where('sampel_inhouse_id', $activeBatch->sampel_inhouse_id)
                ->where('parameter_uji_id', $paramUji->parameter_uji_id)
                ->orderBy('tanggal_uji', 'desc')
                ->orderBy('created_at', 'desc')
                ->take(9)
                ->get();

            $eval = $this->westgard->evaluate($val1, $val2, $meanAcuan, $sdAcuan, $history);

            // Coba ekstrak im_d1 dan im_d2 jika ada di tabel (untuk data mentah DB info)
            $mentah = $data['mentah'] ?? [];
            $mentah['nama_sampel_uji'] = $request->input('nama_sampel_uji');
            
            if ($needsDb) {
                // If IM was pulled from VM table or explicitly passed
                $mentah['im_d1'] = $im1;
                $mentah['im_d2'] = $im2;
            }

            $qch = QcHarian::create([
                'sampel_inhouse_id' => $activeBatch->sampel_inhouse_id,
                'parameter_uji_id' => $paramUji->parameter_uji_id,
                'tanggal_uji' => $request->tanggal_uji,
                'analis_id' => auth()->id() ?? 1,
                'nilai_d1' => $d1,
                'nilai_d2' => $d2,
                'nilai_db_1' => $db1,
                'nilai_db_2' => $db2,
                'nilai_akhir' => $eval['nilai_akhir'],
                'mean_acuan' => $meanAcuan,
                'sd_acuan' => $sdAcuan,
                'status_evaluasi' => $eval['status'],
                'pelanggaran_rule' => $eval['rule'] ? $eval['message'] : null,
                'status_investigasi' => $eval['status'] === 'outlier' ? 'menunggu_investigasi' : 'aman',
                'data_mentah' => $mentah,
            ]);

            $savedCount++;
            $results[] = strtoupper($pName) . ': ' . strtoupper($eval['status']);

            if ($eval['status'] === 'outlier' && !$outlierParam) {
                $outlierParam = $pName;
                $outlierQch = $qch;
            }
        }

        if ($savedCount === 0) {
            return back()->with('error', 'Tidak ada parameter yang berhasil disimpan. Pastikan Anda mencentang minimal 1 parameter dan mengisi nilainya.');
        }

        // Jika ada outlier, arahkan ke form investigasi untuk outlier pertama
        if ($outlierQch) {
            return redirect()->route('qc-harian.investigasi', $outlierQch->id)
                ->with('error', "Peringatan Outlier pada {$outlierParam}! Anda diwajibkan mengisi form investigasi sebelum dapat melanjutkan.");
        }

        return redirect()->route('qc-harian.index')->with('success', "Data QC Harian berhasil disimpan ({$savedCount} parameter). " . implode(' | ', $results));
    }

    public function chart($parameter_uji_id)
    {
        $activeBatch = SampelInhouse::where('status', 'aktif')->latest()->first();
        if (!$activeBatch) {
            return redirect()->route('qc-harian.index')->with('error', 'Tidak ada batch aktif.');
        }

        $paramUji = ParameterUji::findOrFail($parameter_uji_id);
        
        // Ambil 30 data terakhir untuk diplot di grafik
        $logs = QcHarian::where('sampel_inhouse_id', $activeBatch->sampel_inhouse_id)
            ->where('parameter_uji_id', $parameter_uji_id)
            ->orderBy('tanggal_uji', 'asc')
            ->orderBy('created_at', 'asc')
            ->take(30)
            ->get();

        return view('qc-harian.chart', compact('activeBatch', 'paramUji', 'logs'));
    }

    public function investigasi($id)
    {
        $qc = QcHarian::with(['parameterUji', 'sampelInhouse'])->findOrFail($id);
        return view('qc-harian.investigasi', compact('qc'));
    }

    public function storeInvestigasi(Request $request, $id)
    {
        $request->validate([
            'catatan_investigasi' => 'required|string|min:10',
        ]);

        $qc = QcHarian::findOrFail($id);
        $qc->update([
            'catatan_investigasi' => $request->catatan_investigasi,
            'status_investigasi' => 'selesai_investigasi'
        ]);

        return redirect()->route('qc-harian.index')->with('success', 'Investigasi berhasil disimpan. Kunci parameter telah dibuka kembali.');
    }
}
