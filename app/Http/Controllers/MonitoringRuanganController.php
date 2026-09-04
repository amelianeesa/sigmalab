<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MonitoringRuangan;
use App\Models\Alat;
use App\Models\TitikKalibrasi;
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
        $daftarAlat = Alat::where(function($query) {
            $query->where('nama_alat', 'LIKE', '%Thermohygrometer%')
                  ->orWhere('nama_alat', 'LIKE', '%Thermo Hygrometer%');
        })->get();
        
        $alatId = $request->input('alat_id');
        
        $alatAktif = null;
        if ($alatId){
            $alatAktif = Alat::where('alat_id', $alatId)->first();
        }

        $bulan = $request->input('bulan', 'Januari');
        $tahun = $request->input('tahun', date('Y'));
        $ruangan = $request->input('nama_ruangan', '');

        // Ambil data titik kalibrasi manual untuk alat aktif
        $titikKalibrasiList = [];

        $otomatisSuhu = '';
        $otomatisKelembaban = '';

        if ($alatAktif) {
            $idAlatAktif = $alatAktif->alat_id ?? $alatAktif->id;
            $titikKalibrasiList = TitikKalibrasi::where('alat_id', $idAlatAktif)
                ->orderBy('kategori', 'asc')
                ->orderBy('equipment_reading', 'asc')
                ->get();
         
            $suhuPoints = $titikKalibrasiList->where('kategori', 'temperature');
            if ($suhuPoints->isNotEmpty()) {
                $minSuhu = $suhuPoints->min('equipment_reading');
                $maxSuhu = $suhuPoints->max('equipment_reading');
                $otomatisSuhu = $minSuhu . ' - ' . $maxSuhu . ' °C';
            }

            $lembapPoints = $titikKalibrasiList->where('kategori', 'humidity');
            if ($lembapPoints->isNotEmpty()) {
                $minLembap = $lembapPoints->min('equipment_reading');
                $maxLembap = $lembapPoints->max('equipment_reading');
                $otomatisKelembaban = $minLembap . ' - ' . $maxLembap . ' %';
            }
        }
        
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

        $firstRecord = collect($monitoringData)->first(fn($item) => $item !== null);
        
        $persyaratanSuhu = $request->input('persyaratan_suhu', $otomatisSuhu ?? ($firstRecord?->persyaratan_suhu ?? ''));
        $persyaratanKelembaban = $request->input('persyaratan_kelembaban', $otomatisKelembaban ?? ($firstRecord?->persyaratan_kelembaban ?? ''));

        return view('monitoring_ruangan.index', compact(
            'daftarAlat', 
            'alatAktif', 
            'bulan', 
            'tahun', 
            'ruangan', 
            'monitoringData', 
            'alatId', 
            'persyaratanSuhu', 
            'persyaratanKelembaban',
            'titikKalibrasiList',
            'otomatisSuhu', 
            'otomatisKelembaban'
        ));
    }

    public function storeTitikKalibrasi(Request $request, $alatId)
    {
        $request->validate([
            'tanggal_kalibrasi' => 'required|date',
            'tanggal_expired' => 'required|date|after_or_equal:tanggal_kalibrasi',
        ]);

        $tglKalibrasi = $request->tanggal_kalibrasi;
        $tglExpired = $request->tanggal_expired;

        // Simpan data Humidity jika ada yang diisi
        if ($request->has('humidity_equipment')) {
            foreach ($request->humidity_equipment as $index => $eq) {
                if (!is_null($eq) && !is_null($request->humidity_standard[$index])) {
                    TitikKalibrasi::create([
                        'alat_id' => $alatId,
                        'kategori' => 'humidity',
                        'equipment_reading' => $eq,
                        'standard_reading' => $request->humidity_standard[$index],
                        'tanggal_kalibrasi' => $tglKalibrasi,
                        'tanggal_expired' => $tglExpired,
                    ]);
                }
            }
        }

        // Simpan data Temperature jika ada yang diisi
        if ($request->has('temperature_equipment')) {
            foreach ($request->temperature_equipment as $index => $eq) {
                if (!is_null($eq) && !is_null($request->temperature_standard[$index])) {
                    TitikKalibrasi::create([
                        'alat_id' => $alatId,
                        'kategori' => 'temperature',
                        'equipment_reading' => $eq,
                        'standard_reading' => $request->temperature_standard[$index],
                        'tanggal_kalibrasi' => $tglKalibrasi,
                        'tanggal_expired' => $tglExpired,
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Titik acuan kalibrasi Humidity & Temperature berhasil disimpan!');
    }

    public function destroyTitikKalibrasi($id)
    {
        $titik = TitikKalibrasi::findOrFail($id);
        $titik->delete();

        return redirect()->back()->with('success', 'Titik acuan kalibrasi berhasil dihapus!');
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

        $alatId = $request->alat_id;

        $titikList = \App\Models\TitikKalibrasi::where('alat_id', $alatId)->get();
        $minTemp = $titikList->where('kategori', 'temperature')->min('equipment_reading');
        $maxTemp = $titikList->where('kategori', 'temperature')->max('equipment_reading');
        
        $minHum = $titikList->where('kategori', 'humidity')->min('equipment_reading');
        $maxHum = $titikList->where('kategori', 'humidity')->max('equipment_reading');

        $suhuTerkoreksi1 = null;
        $isS1Out = false;
        if ($request->suhu_pembacaan_1 !== null && $request->suhu_pembacaan_1 !== '') {
            $val = floatval($request->suhu_pembacaan_1);
            if ($minTemp !== null && ($val < $minTemp || $val > $maxTemp)) {
                $isS1Out = true; 
            } else {
                $suhuTerkoreksi1 = $this->kalibrasiService->hitungKoreksi($alatId, 'temperature', $val);
            }
        }

        $suhuTerkoreksi2 = null;
        $isS2Out = false;
        if ($request->suhu_pembacaan_2 !== null && $request->suhu_pembacaan_2 !== '') {
            $val = floatval($request->suhu_pembacaan_2);
            if ($minTemp !== null && ($val < $minTemp || $val > $maxTemp)) {
                $isS2Out = true;
            } else {
                $suhuTerkoreksi2 = $this->kalibrasiService->hitungKoreksi($alatId, 'temperature', $val);
            }
        }

        $lembapTerkoreksi1 = null;
        $isH1Out = false;
        if ($request->kelembaban_pembacaan_1 !== null && $request->kelembaban_pembacaan_1 !== '') {
            $val = floatval($request->kelembaban_pembacaan_1);
            if ($minHum !== null && ($val < $minHum || $val > $maxHum)) {
                $isH1Out = true;
            } else {
                $lembapTerkoreksi1 = $this->kalibrasiService->hitungKoreksi($alatId, 'humidity', $val);
            }
        }

        $lembapTerkoreksi2 = null;
        $isH2Out = false;
        if ($request->kelembaban_pembacaan_2 !== null && $request->kelembaban_pembacaan_2 !== '') {
            $val = floatval($request->kelembaban_pembacaan_2);
            if ($minHum !== null && ($val < $minHum || $val > $maxHum)) {
                $isH2Out = true;
            } else {
                $lembapTerkoreksi2 = $this->kalibrasiService->hitungKoreksi($alatId, 'humidity', $val);
            }
        }

        // Simpan ke database
        MonitoringRuangan::updateOrCreate(
            [
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
                'nama_ruangan' => $request->nama_ruangan,
                'alat_id' => $alatId,
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

        $isWarning = $isS1Out || $isS2Out || $isH1Out || $isH2Out;

        $pesan = 'Data monitoring tanggal ' . $request->tanggal . ' berhasil disimpan!';
        if ($isWarning) {
            $pesanWarning = ' Peringatan: Data monitoring tanggal ' . $request->tanggal . ' berhasil disimpan, tetapi ada nilai input pembacaan yang melebihi batas titik acuan kalibrasi!';
            return redirect()->back()->with('error', $pesanWarning);
        }

        return redirect()->back()->with('success', $pessan ?? $pesan);
    }

    public function exportPdf(Request $request)
    {
        $alatId = $request->input('alat_id');
        $ruangan = $request->input('nama_ruangan');
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        $monitoringData = [];
        for ($i = 1; $i <= 31; $i++) {
            $monitoringData[$i] = MonitoringRuangan::where('bulan', $bulan)
                ->where('tahun', $tahun)
                ->where('nama_ruangan', $ruangan)
                ->where('alat_id', $alatId)
                ->where('tanggal', $i)
                ->first();
        }

        $firstRecord = collect($monitoringData)->first(fn($item) => $item !== null);
        $persyaratanSuhu = $firstRecord?->persyaratan_suhu ?? '-';
        $persyaratanKelembaban = $firstRecord?->persyaratan_kelembaban ?? '-';

        $alat = Alat::where('alat_id', $alatId)->first();

        return view('monitoring_ruangan.pdf', compact('monitoringData', 'alat', 'bulan', 'tahun', 'ruangan', 'persyaratanSuhu', 'persyaratanKelembaban'));
    }
}

