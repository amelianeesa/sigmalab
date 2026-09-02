<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MonitoringRuangan;
use App\Models\Alat;
use App\Services\KalibrasiService;

class MonitoringRuanganController extends Controller
{
    protected $kalibrasiService;

    public function __construct(KalibrasiService $kalibrasiService)
    {
        $this->kalibrasiService = $kalibrasiService;
    }

    public function index(Request $request)
    {
        $daftarAlat = Alat::all();
        
        $alatId = $request->input('alat_id');
        $alatAktif = $alatId ? Alat::find($alatId) : null;

        $bulan = $request->input('bulan', 'M');
        $tahun = $request->input('tahun', date('Y'));
        $ruangan = $request->input('nama_ruangan', '');

        // Ambil data monitoring untuk 31 hari
        $monitoringData = [];
        for ($i = 1; $i <= 31; $i++) {
            $monitoringData[$i] = MonitoringRuangan::where('bulan', $bulan)
                ->where('tahun', $tahun)
                ->where('nama_ruangan', $ruangan)
                ->where('alat_id', $alatId)
                ->where('tanggal', $i)
                ->first();
        }

        // Ambil nilai persyaratan dari record yang sudah ada di database jika tersedia
        $firstRecord = collect($monitoringData)->first(fn($item) => $item !== null);
        $persyaratanSuhu = $request->input('persyaratan_suhu', $firstRecord?->persyaratan_suhu ?? '');
        $persyaratanKelembaban = $request->input('persyaratan_kelembaban', $firstRecord?->persyaratan_kelembaban ?? '');

        return view('monitoring_ruang.index', compact(
            'daftarAlat', 
            'alatAktif', 
            'bulan', 
            'tahun', 
            'ruangan', 
            'monitoringData', 
            'alatId', 
            'persyaratanSuhu', 
            'persyaratanKelembaban'
        ));
    }

    public function updateBaris(Request $request)
    {
        $request->validate([
            'alat_id' => 'required|integer',
            'bulan' => 'required',
            'tahun' => 'required',
            'nama_ruangan' => 'required',
            'tanggal' => 'required|integer|min:1|max:31',
        ]);

        $alat = Alat::findOrFail($request->alat_id);
        $codeAlat = $alat->code ?? 'CODE 1'; // Kode acuan tabel kalibrasi

        // Hitung otomatis suhu & kelembaban terkoreksi Sesi 1
        $suhuTerkoreksi1 = $request->suhu_pembacaan_1 ? $this->kalibrasiService->hitungKoreksi($codeAlat, 'temperature', $request->suhu_pembacaan_1) : null;
        $lembapTerkoreksi1 = $request->kelembaban_pembacaan_1 ? $this->kalibrasiService->hitungKoreksi($codeAlat, 'humidity', $request->kelembaban_pembacaan_1) : null;

        // Hitung otomatis suhu & kelembaban terkoreksi Sesi 2
        $suhuTerkoreksi2 = $request->suhu_pembacaan_2 ? $this->kalibrasiService->hitungKoreksi($codeAlat, 'temperature', $request->suhu_pembacaan_2) : null;
        $lembapTerkoreksi2 = $request->kelembaban_pembacaan_2 ? $this->kalibrasiService->hitungKoreksi($codeAlat, 'humidity', $request->kelembaban_pembacaan_2) : null;

        MonitoringRuangan::updateOrCreate(
            [
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
                'nama_ruangan' => $request->nama_ruangan,
                'alat_id' => $request->alat_id,
                'tanggal' => $request->tanggal,
            ],
            [
                'persyaratan_suhu' => $request->persyaratan_suhu,
                'persyaratan_kelembaban' => $request->persyaratan_kelembaban,
                
                'waktu_1' => $request->waktu_1,
                'suhu_pembacaan_1' => $request->suhu_pembacaan_1,
                'suhu_terkoreksi_1' => $suhuTerkoreksi1,
                'kelembaban_pembacaan_1' => $request->kelembaban_pembacaan_1,
                'kelembaban_terkoreksi_1' => $lembapTerkoreksi1,
                'paraf_1' => $request->has('paraf_1') ? 1 : 0,

                'waktu_2' => $request->waktu_2,
                'suhu_pembacaan_2' => $request->suhu_pembacaan_2,
                'suhu_terkoreksi_2' => $suhuTerkoreksi2,
                'kelembaban_pembacaan_2' => $request->kelembaban_pembacaan_2,
                'kelembaban_terkoreksi_2' => $lembapTerkoreksi2,
                'paraf_2' => $request->has('paraf_2') ? 1 : 0,

                'keterangan' => $request->keterangan,
            ]
        );

        return redirect()->back()->with('success', 'Data monitoring tanggal ' . $request->tanggal . ' berhasil disimpan!');
    }
}