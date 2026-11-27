<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\MonitoringRuangan;
use App\Models\Alat;
use App\Models\TitikKalibrasi;
use App\Services\KalibrasiService;
use App\Models\DokumenReferensiRuangan;
use App\Models\PeralatanRuangan;
use App\Models\RiwayatKalibrasi;
use App\Models\PersyaratanRuangan;
use Carbon\Carbon;

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
        if ($alatId) {
            $alatAktif = Alat::where('alat_id', $alatId)->first();
        }

        $kalibrasiTerakhir = $alatAktif
            ? RiwayatKalibrasi::where('alat_id', $alatAktif->alat_id)->latest('tgl_kalibrasi')->first()
            : null;

        $namaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $bulan = $request->input('bulan') ?: $namaBulan[(int) date('n') - 1];
        $tahun = $request->input('tahun', date('Y'));
        $ruangan = trim((string) $request->input('nama_ruangan', ''));

        $titikKalibrasiList = $alatAktif
            ? TitikKalibrasi::where('alat_id', $alatAktif->alat_id)
                ->orderBy('kategori')
                ->orderBy('equipment_reading')
                ->get()
            : [];

        $titikSuhu = collect($titikKalibrasiList)->where('kategori', 'temperature');
        $titikLembap = collect($titikKalibrasiList)->where('kategori', 'humidity');

        $batasAcuan = [
            'suhuMin' => $titikSuhu->isNotEmpty() ? (float) $titikSuhu->min('equipment_reading') : null,
            'suhuMax' => $titikSuhu->isNotEmpty() ? (float) $titikSuhu->max('equipment_reading') : null,
            'lembapMin' => $titikLembap->isNotEmpty() ? (float) $titikLembap->min('equipment_reading') : null,
            'lembapMax' => $titikLembap->isNotEmpty() ? (float) $titikLembap->max('equipment_reading') : null,
        ];

        $monitoringData = [];
        for ($i = 1; $i <= 31; $i++) {
            $monitoringData[$i] = MonitoringRuangan::where('bulan', $bulan)
                ->where('tahun', $tahun)
                ->where('nama_ruangan', $ruangan)
                ->where('alat_id', $alatId)
                ->where('tanggal', $i)
                ->first();
        }

        $persyaratan = $ruangan !== ''
            ? PersyaratanRuangan::where('nama_ruangan', $ruangan)->first()
            : null;

        $batasSyarat = [
            'suhuMin' => $persyaratan?->suhu_min,
            'suhuMax' => $persyaratan?->suhu_max,
            'lembapMin' => $persyaratan?->kelembaban_min,
            'lembapMax' => $persyaratan?->kelembaban_max,
        ];

        $dokumenList = DokumenReferensiRuangan::where('alat_id', $alatId)
            ->where('nama_ruangan', $ruangan)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->get();

        $peralatanList = !empty($ruangan)
            ? PeralatanRuangan::where('nama_ruangan', trim($ruangan))->orderBy('nama_peralatan')->get()
            : collect();

        return view('monitoring_ruangan.index', compact(
            'daftarAlat',
            'alatAktif',
            'kalibrasiTerakhir',
            'bulan',
            'tahun',
            'ruangan',
            'monitoringData',
            'alatId',
            'persyaratan',
            'batasSyarat',
            'batasAcuan',
            'dokumenList',
            'peralatanList',
            'titikKalibrasiList',
        ));
    }

    private function bolehKelola(): bool
    {
        return in_array(Auth::user()->role->nama_role ?? '', [
            'Koordinator Laboratorium', 'Analis Lab', 'Admin Aplikasi']);
    }

    public function storeTitikKalibrasi(Request $request, $alatId)
    {
        $kalibrasiTerakhir = RiwayatKalibrasi::where('alat_id', $alatId)->latest('tgl_kalibrasi')->first();

        if (!$kalibrasiTerakhir?->tgl_kalibrasi || !$kalibrasiTerakhir?->tgl_akhir) {
            return redirect()->back()->with('error', 'Lengkapi data kalibrasi alat ini terlebih dahulu di menu Alat & Kalibrasi.');
        }

        $tglKalibrasi = Carbon::parse($kalibrasiTerakhir->tgl_kalibrasi)->format('Y-m-d');
        $tglExpired = Carbon::parse($kalibrasiTerakhir->tgl_akhir)->format('Y-m-d');

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

    public function updatePersyaratan(Request $request)
    {
        abort_unless($this->bolehKelola(), 403);

        $data = $request->validate([
            'alat_id' => 'nullable|integer',
            'nama_ruangan' => 'required|string|max:255',
            'bulan' => 'required',
            'tahun' => 'required',
            'suhu_min' => 'nullable|numeric|required_with:suhu_max',
            'suhu_max' => 'nullable|numeric|required_with:suhu_min|gte:suhu_min',
            'kelembaban_min' => 'nullable|numeric|required_with:kelembaban_max',
            'kelembaban_max' => 'nullable|numeric|required_with:kelembaban_min|gte:kelembaban_min',
        ]);

        $persyaratan = PersyaratanRuangan::updateOrCreate(
            ['nama_ruangan' => trim($data['nama_ruangan'])],
            [
                'suhu_min' => $data['suhu_min'] ?? null,
                'suhu_max' => $data['suhu_max'] ?? null,
                'kelembaban_min' => $data['kelembaban_min'] ?? null,
                'kelembaban_max' => $data['kelembaban_max'] ?? null,
            ]
        );

        MonitoringRuangan::where('nama_ruangan', $persyaratan->nama_ruangan)
            ->where('bulan', $data['bulan'])
            ->where('tahun', $data['tahun'])
            ->update([
                'persyaratan_suhu' => $persyaratan->teksSuhu(),
                'persyaratan_kelembaban' => $persyaratan->teksKelembaban(),
            ]);

        $titikList = !empty($data['alat_id'])
            ? TitikKalibrasi::where('alat_id', $data['alat_id'])->get()
            : collect();

        $peringatan = [];
        $kategori = ['suhu' => 'temperature', 'kelembaban' => 'humidity'];

        foreach ($kategori as $nama => $jenis) {
            $titik = $titikList->where('kategori', $jenis);

            if ($titik->isEmpty()) {
                continue;
            }

            $acuanMin = (float) $titik->min('equipment_reading');
            $acuanMax = (float) $titik->max('equipment_reading');
            $syaratMin = isset($data[$nama . '_min']) ? (float) $data[$nama . '_min'] : null;
            $syaratMax = isset($data[$nama . '_max']) ? (float) $data[$nama . '_max'] : null;

            $luar = ($syaratMin !== null && ($syaratMin < $acuanMin || $syaratMin > $acuanMax))
                || ($syaratMax !== null && ($syaratMax < $acuanMin || $syaratMax > $acuanMax));

            if ($luar) {
                $peringatan[] = "{$nama} ({$syaratMin} - {$syaratMax}) di luar titik acuan kalibrasi ({$acuanMin} - {$acuanMax})";
            }
        }

        if ($peringatan) {
            return redirect()->back()->with('error', 'Persyaratan disimpan dengan peringatan: persyaratan ' . implode(', ', $peringatan) . '.');
        }

        return redirect()->back()->with('success', 'Persyaratan suhu dan kelembaban berhasil disimpan.');
    }

    public function updateBaris(Request $request)
    {
        abort_unless($this->bolehKelola(), 403);

        $request->validate([
            'alat_id' => 'required|integer',
            'bulan' => 'required',
            'tahun' => 'required',
            'nama_ruangan' => 'required',
            'tanggal' => 'required|integer|min:1|max:31',
            'suhu_pembacaan_1' => 'nullable|numeric',
            'suhu_pembacaan_2' => 'nullable|numeric',
            'kelembaban_pembacaan_1' => 'nullable|numeric',
            'kelembaban_pembacaan_2' => 'nullable|numeric',
        ]);

        $alatId = $request->alat_id;
        $userLogin = Auth::user();
        $namaAnalisLogin = $userLogin->name ?? ($userLogin->username ?? 'Analis');

        $titikList = TitikKalibrasi::where('alat_id', $alatId)->get();
        $persyaratan = PersyaratanRuangan::where('nama_ruangan', trim($request->nama_ruangan))->first();

        $kolom = [
            'suhu_pembacaan_1' => ['suhu_terkoreksi_1', 'temperature', 'suhu pagi'],
            'suhu_pembacaan_2' => ['suhu_terkoreksi_2', 'temperature', 'suhu sore'],
            'kelembaban_pembacaan_1' => ['kelembaban_terkoreksi_1', 'humidity', 'kelembaban pagi'],
            'kelembaban_pembacaan_2' => ['kelembaban_terkoreksi_2', 'humidity', 'kelembaban sore'],
        ];

        $terkoreksi = [];
        $peringatan = [];

        foreach ($kolom as $input => [$output, $kategori, $label]) {
            $terkoreksi[$output] = null;

            if (!$request->filled($input)) {
                continue;
            }

            $nilai = (float) $request->input($input);
            $titik = $titikList->where('kategori', $kategori);
            $min = $titik->min('equipment_reading');
            $max = $titik->max('equipment_reading');

            $luarAcuan = $min !== null && ($nilai < $min || $nilai > $max);
            $luarSyarat = $kategori === 'temperature'
                ? $persyaratan?->suhuDiLuar($nilai)
                : $persyaratan?->kelembabanDiLuar($nilai);

            if ($luarAcuan) {
                $peringatan[] = "{$label} ({$nilai}) di luar titik acuan";
            } else {
                $terkoreksi[$output] = $this->kalibrasiService->hitungKoreksi($alatId, $kategori, $nilai);
            }

            if ($luarSyarat) {
                $peringatan[] = "{$label} ({$nilai}) di luar persyaratan ruangan";
            }
        }

        $kunci = [
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'nama_ruangan' => $request->nama_ruangan,
            'alat_id' => $alatId,
            'tanggal' => $request->tanggal,
        ];

        $lama = MonitoringRuangan::where($kunci)->first();

        $paraf1 = $lama?->paraf_1 ?: ($request->anyFilled(['suhu_pembacaan_1', 'kelembaban_pembacaan_1', 'waktu_1']) ? $namaAnalisLogin : null);
        $paraf2 = $lama?->paraf_2 ?: ($request->anyFilled(['suhu_pembacaan_2', 'kelembaban_pembacaan_2', 'waktu_2']) ? $namaAnalisLogin : null);

        MonitoringRuangan::updateOrCreate($kunci, array_merge($terkoreksi, [
            'persyaratan_suhu' => $persyaratan?->teksSuhu(),
            'persyaratan_kelembaban' => $persyaratan?->teksKelembaban(),
            'waktu_1' => $request->waktu_1,
            'suhu_pembacaan_1' => $request->suhu_pembacaan_1,
            'kelembaban_pembacaan_1' => $request->kelembaban_pembacaan_1,
            'paraf_1' => $paraf1,
            'waktu_2' => $request->waktu_2,
            'suhu_pembacaan_2' => $request->suhu_pembacaan_2,
            'kelembaban_pembacaan_2' => $request->kelembaban_pembacaan_2,
            'paraf_2' => $paraf2,
            'status' => $request->status,
        ]));

        if ($peringatan) {
            return redirect()->back()->with('error', "Data tanggal {$request->tanggal} disimpan dengan peringatan: " . implode(', ', $peringatan) . '.');
        }

        return redirect()->back()->with('success', "Data monitoring tanggal {$request->tanggal} berhasil disimpan!");
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
        $persyaratan = PersyaratanRuangan::where('nama_ruangan', trim((string) $ruangan))->first();
        $persyaratanSuhu = $firstRecord?->persyaratan_suhu ?? $persyaratan?->teksSuhu() ?? '-';
        $persyaratanKelembaban = $firstRecord?->persyaratan_kelembaban ?? $persyaratan?->teksKelembaban() ?? '-';
        $alat = Alat::where('alat_id', $alatId)->first();

        $userCetak = \Illuminate\Support\Facades\Auth::user();
        $namaUserCetak = $userCetak?->personil?->nama ?? ($userCetak?->name ?? ($userCetak?->username ?? 'System'));

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('monitoring_ruangan.pdf', compact(
            'monitoringData',
            'alat',
            'bulan',
            'tahun',
            'ruangan',
            'persyaratanSuhu',
            'persyaratanKelembaban',
            'namaUserCetak'
        ));

        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream("Rekap_Monitoring_{$ruangan}_{$bulan}_{$tahun}.pdf");
    }

    public function uploadReferensi(Request $request)
    {
        $request->validate([
            'dokumen_referensi' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:2048',
            'nama_ruangan'      => 'required',
            'bulan'             => 'required',
            'tahun'             => 'required',
            'alat_id'           => 'required',
        ]);

        if ($request->hasFile('dokumen_referensi')) {
            $file = $request->file('dokumen_referensi');
            $namaAsli = $file->getClientOriginalName();
            $filename = time() . '_' . $namaAsli;
            $path = $file->storeAs('dokumen_referensi', $filename, 'public');

            DokumenReferensiRuangan::create([
                'alat_id'        => $request->alat_id,
                'nama_ruangan'   => $request->nama_ruangan,
                'bulan'          => $request->bulan,
                'tahun'          => $request->tahun,
                'file_path'      => $path,
                'nama_file_asli' => $namaAsli,
            ]);
        }

        return redirect()->route('inventori.monitoring.index', [
            'alat_id'      => $request->alat_id,
            'nama_ruangan' => $request->nama_ruangan,
            'bulan'        => $request->bulan,
            'tahun'        => $request->tahun,
        ])->with('success', 'Dokumen referensi berhasil ditambahkan.');
    }

    public function updateReferensi(Request $request, $id)
    {
        $request->validate([
            'dokumen_referensi' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
            'nama_file_asli'    => 'nullable|string|max:255',
        ]);

        $dokumen = DokumenReferensiRuangan::findOrFail($id);

        if ($request->hasFile('dokumen_referensi')) {
            if (Storage::disk('public')->exists($dokumen->file_path)) {
                Storage::disk('public')->delete($dokumen->file_path);
            }

            $file = $request->file('dokumen_referensi');
            $namaAsli = $file->getClientOriginalName();
            $filename = time() . '_' . $namaAsli;
            $path = $file->storeAs('dokumen_referensi', $filename, 'public');

            $dokumen->file_path = $path;
            $dokumen->nama_file_asli = $namaAsli;
        }

        if ($request->filled('nama_file_asli')) {
            $dokumen->nama_file_asli = $request->nama_file_asli;
        }

        $dokumen->save();

        return redirect()->back()->with('success', 'Dokumen referensi berhasil diperbarui.');
    }

    public function destroyReferensi($id)
    {
        $dokumen = DokumenReferensiRuangan::findOrFail($id);

        if (Storage::disk('public')->exists($dokumen->file_path)) {
            Storage::disk('public')->delete($dokumen->file_path);
        }

        $dokumen->delete();

        return redirect()->back()->with('success', 'Dokumen referensi berhasil dihapus.');
    }
}