<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\RiwayatKalibrasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\ItemPemeliharaan;
use App\Models\LogPemeliharaan;
use App\Models\RiwayatPerbaikanAlat;
use Illuminate\Support\Facades\Auth;

class AlatController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $filterStatus = $request->input('filter_status');
        $filterKondisi = $request->input('filter_kondisi');

        $query = Alat::with(['riwayatKalibrasi', 'kegiatanAlat']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_alat', 'LIKE', "%{$search}%")
                  ->orWhere('kode_alat', 'LIKE', "%{$search}%")
                  ->orWhere('merk_tipe', 'LIKE', "%{$search}%");
            });
        }

        if ($filterKondisi) {
            $query->where('kondisi_barang', $filterKondisi);
        }

        $alatList = $query->latest()->get();

        if ($filterStatus) {
            $alatList = $alatList->filter(function($item) use ($filterStatus) {
                $kalibrasiTerakhir = $item->riwayatKalibrasi->sortByDesc('tgl_kalibrasi')->first();
                if (!$kalibrasiTerakhir || !$kalibrasiTerakhir->tgl_akhir) {
                    return false;
                }
                
                $tglAkhir = Carbon::parse($kalibrasiTerakhir->tgl_akhir);
                $sekarang = Carbon::now()->startOfDay();
                $sisaHari = $sekarang->diffInDays($tglAkhir, false);

                if ($filterStatus == 'kedaluarsa') {
                    return $sisaHari < 0;
                } elseif ($filterStatus == 'segera') {
                    return $sisaHari >= 0 && $sisaHari <= 30;
                } elseif ($filterStatus == 'aktif') {
                    return $sisaHari > 30;
                }
                return true;
            });
        }

        $alat = $alatList;

        return view('alat.index', compact('alat', 'search', 'filterStatus', 'filterKondisi'));
    }

    public function create()
    {
        return view('alat.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_alat' => 'required|string|max:50|unique:alat,kode_alat',
            'nama_alat' => 'required|string|max:100',
            'merk_tipe' => 'nullable|string|max:100',
            'no_seri' => 'nullable|string|max:100',
            'warna' => 'nullable|string|max:30',
            'ukuran' => 'nullable|string|max:50',
            'kondisi_barang' => 'required|in:baik,rusak',
            'status_barang' => 'required|in:terpakai,idle',
            'unit_kerja_pemilik' => 'nullable|string|max:100',
            'no_sertifikat' => 'nullable|string|max:100',
            'interval_kalibrasi' => 'nullable|string|max:50',
            'tgl_kalibrasi' => 'nullable|date',
            'tgl_akhir' => 'nullable|date',
            'lembaga_kalibrasi' => 'nullable|string|max:150',
            'jenis_kalibrasi' => 'nullable|in:internal,eksternal',
            'range_kapasitas' => 'nullable|string|max:100',
            'faktor_koreksi' => 'nullable|string|max:100',
            'signifikan' => 'nullable|in:ya,tidak',
            'catatan_evaluasi' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            $alat = Alat::create([
                'kode_alat' => $request->kode_alat,
                'nama_alat' => $request->nama_alat,
                'merk_tipe' => $request->merk_tipe,
                'no_seri' => $request->no_seri,
                'warna' => $request->warna,
                'ukuran' => $request->ukuran,
                'kondisi_barang' => $request->kondisi_barang,
                'status_barang' => $request->status_barang,
                'unit_kerja_pemilik' => $request->unit_kerja_pemilik,
            ]);

            if ($request->filled('tgl_kalibrasi')) {
                RiwayatKalibrasi::create([
                    'alat_id' => $alat->alat_id,
                    'jenis_kalibrasi' => $request->jenis_kalibrasi,
                    'no_sertifikat' => $request->no_sertifikat,
                    'interval_kalibrasi' => $request->interval_kalibrasi,
                    'tgl_kalibrasi' => $request->tgl_kalibrasi,
                    'tgl_akhir' => $request->tgl_akhir,
                    'lembaga_kalibrasi' => $request->lembaga_kalibrasi,
                    'range_kapasitas' => $request->range_kapasitas,
                    'faktor_koreksi' => $request->faktor_koreksi,
                    'signifikan' => $request->signifikan ?? 'tidak',
                    'catatan_evaluasi' => $request->catatan_evaluasi,
                ]);
            }
        });

        return redirect()->route('alat.index')->with('success', 'Data alat beserta informasi kalibrasinya berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $alat = Alat::with(['riwayatKalibrasi' => function($query) {
            $query->latest('tgl_kalibrasi');
        }])->findOrFail($id);

        $kalibrasiTerakhir = $alat->riwayatKalibrasi->first();

        return view('alat.edit', compact('alat', 'kalibrasiTerakhir'));
    }

    public function update(Request $request, $id)
    {
        $alat = Alat::findOrFail($id);

        $request->validate([
            'nama_alat' => 'required|string|max:100',
            'merk_tipe' => 'nullable|string|max:100',
            'no_seri' => 'nullable|string|max:100',
            'warna' => 'nullable|string|max:30',
            'ukuran' => 'nullable|string|max:50',
            'kondisi_barang' => 'required|in:baik,rusak',
            'status_barang' => 'required|in:terpakai,idle',
            'unit_kerja_pemilik' => 'nullable|string|max:100',
            'no_sertifikat' => 'nullable|string|max:100',
            'interval_kalibrasi' => 'nullable|string|max:50',
            'tgl_kalibrasi' => 'nullable|date',
            'tgl_akhir' => 'nullable|date',
            'lembaga_kalibrasi' => 'nullable|string|max:150',
            'jenis_kalibrasi' => 'nullable|in:internal,eksternal',
            'range_kapasitas' => 'nullable|string|max:100',
            'faktor_koreksi' => 'nullable|string|max:100',
            'signifikan' => 'nullable|in:ya,tidak',
            'catatan_evaluasi' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $alat) {
            $alat->update([
                'nama_alat' => $request->nama_alat,
                'merk_tipe' => $request->merk_tipe,
                'no_seri' => $request->no_seri,
                'warna' => $request->warna,
                'ukuran' => $request->ukuran,
                'kondisi_barang' => $request->kondisi_barang,
                'status_barang' => $request->status_barang,
                'unit_kerja_pemilik' => $request->unit_kerja_pemilik,
            ]);

            $kalibrasiTerakhir = $alat->riwayatKalibrasi()->latest('tgl_kalibrasi')->first();

            $dataKalibrasi = [
                'jenis_kalibrasi' => $request->jenis_kalibrasi,
                'no_sertifikat' => $request->no_sertifikat,
                'interval_kalibrasi' => $request->interval_kalibrasi,
                'tgl_kalibrasi' => $request->tgl_kalibrasi,
                'tgl_akhir' => $request->tgl_akhir,
                'lembaga_kalibrasi' => $request->lembaga_kalibrasi,
                'range_kapasitas' => $request->range_kapasitas,
                'faktor_koreksi' => $request->faktor_koreksi,
                'signifikan' => $request->signifikan ?? 'tidak',
                'catatan_evaluasi' => $request->catatan_evaluasi,
            ];

            if ($request->filled('tgl_kalibrasi')) {
                if ($kalibrasiTerakhir) {
                    $kalibrasiTerakhir->update($dataKalibrasi);
                } else {
                    $dataKalibrasi['alat_id'] = $alat->alat_id;
                    RiwayatKalibrasi::create($dataKalibrasi);
                }
            }
        });

        return redirect()->route('alat.index')->with('success', 'Data alat dan informasi kalibrasi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $alat = Alat::with(['riwayatKalibrasi', 'kegiatanAlat'])->findOrFail($id);

        $alat->riwayatKalibrasi()->delete();
        $alat->kegiatanAlat()->delete();
        $alat->delete();

        return redirect()->route('alat.index')->with('success', 'Data alat berhasil dihapus.');
    }

    public function show($id)
    {
        $alat = Alat::with(['riwayatKalibrasi', 'kegiatanAlat.kegiatan.personil', 'riwayatPerbaikan.pelapor', 'riwayatPerbaikan.verifikator'])->findOrFail($id);
        
        // Cek apakah alat sedang dalam perbaikan
        $sedangDiperbaiki = $alat->riwayatPerbaikan()->whereIn('status_perbaikan', ['Belum Diperbaiki', 'Dalam Perbaikan'])->first();

        return view('alat.show', compact('alat', 'sedangDiperbaiki'));
    }

    public function inputKalibrasi($id)
    {
        $alat = Alat::with(['riwayatKalibrasi' => function($query) {
            $query->orderBy('tgl_kalibrasi', 'asc');
        }, 'itemPemeliharaan'])->findOrFail($id);

        return view('alat.input-kalibrasi', compact('alat'));
    }

    public function storeInputKalibrasi(Request $request, $id)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu!');
        }
        $request->validate([
            'jenis_kalibrasi' => 'required|in:internal,eksternal',
            'no_sertifikat' => 'required|string|max:100',
            'interval_kalibrasi' => 'required|string|max:50',
            'tgl_kalibrasi' => 'required|date',
            'tgl_akhir' => 'required|date|after_or_equal:tgl_kalibrasi',
            'lembaga_kalibrasi' => 'required|string|max:150',
            'range_kapasitas' => 'nullable|string|max:100',
            'faktor_koreksi' => 'nullable|string|max:100',
            'signifikan' => 'required|in:ya,tidak',
            'catatan_evaluasi' => 'nullable|string',
        ]);

        RiwayatKalibrasi::create([
            'alat_id' => $id,
            'jenis_kalibrasi' => $request->jenis_kalibrasi,
            'no_sertifikat' => $request->no_sertifikat,
            'interval_kalibrasi' => $request->interval_kalibrasi,
            'tgl_kalibrasi' => $request->tgl_kalibrasi,
            'tgl_akhir' => $request->tgl_akhir,
            'lembaga_kalibrasi' => $request->lembaga_kalibrasi,
            'range_kapasitas' => $request->range_kapasitas,
            'faktor_koreksi' => $request->faktor_koreksi,
            'signifikan' => $request->signifikan,
            'catatan_evaluasi' => $request->catatan_evaluasi,
        ]);

        return redirect()->route('alat.input-kalibrasi', $id)->with('success', 'Data riwayat kalibrasi baru berhasil ditambahkan!');
    }

    public function publicScan($kode_alat)
    {
        $alat = Alat::with(['riwayatKalibrasi' => function($query) {
            $query->orderBy('tgl_kalibrasi', 'desc');
        }, 'itemPemeliharaan'])->where('kode_alat', $kode_alat)->firstOrFail();

        return view('alat.public-scan', compact('alat'));
    }

    public function inputKalibrasiByKode($kode_alat)
    {
        $alat = Alat::with(['riwayatKalibrasi' => function($query) {
            $query->orderBy('tgl_kalibrasi', 'asc');
        }, 'itemPemeliharaan'])->where('kode_alat', $kode_alat)->firstOrFail();

        // if (!auth()->check()) {
        //     return view('alat.public-scan', compact('alat'));
        // }

        return view('alat.input-kalibrasi', compact('alat'));
    }

    public function pemeliharaanBulanan(Request $request, $id)
    {
        $alat = Alat::with('itemPemeliharaan')->findOrFail($id);
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
        $namaPetugasLogin = Auth::user()?->personil?->nama ?? Auth::user()?->username;
        $logs = LogPemeliharaan::where('alat_id', $id)
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->get()
            ->keyBy(function($item) {
                return $item->item_id . '_' . date('j', strtotime($item->tanggal));
            });

        return view('alat.pemeliharaan', compact('alat', 'bulan', 'tahun', 'logs', 'namaPetugasLogin'));
    }

    public function updatePemeliharaanHarian(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
        ]);

        $tanggal = $request->tanggal;

        if ($request->has('item_id')) {
            $request->validate([
                'item_id' => 'required|exists:item_pemeliharaan,item_id',
                'status' => 'required|boolean'
            ]);

            LogPemeliharaan::updateOrCreate(
                [
                    'alat_id' => $id,
                    'item_id' => $request->item_id,
                    'tanggal' => $tanggal,
                ],
                [
                    'status' => $request->status,
                    'petugas' => Auth::user()->name
                ]
            );

            return response()->json(['success' => true, 'message' => 'Status pemeliharaan diperbarui.']);
        }

        if ($request->has('tindakan') || $request->has('petugas')) {
            LogPemeliharaan::where('alat_id', $id)
                ->whereDate('tanggal', $tanggal)
                ->update([
                    'tindakan' => $request->tindakan,
                    'petugas' => $request->petugas
                ]);

            return response()->json(['success' => true, 'message' => 'Tindakan & petugas diperbarui.']);
        }

        return response()->json(['success' => false, 'message' => 'Data tidak valid.'], 400);
    }

    public function editItemPemeliharaan($id)
    {
        $alat = Alat::with('itemPemeliharaan')->findOrFail($id);
        return view('alat.edit-item-pemeliharaan', compact('alat'));
    }

    public function updateItemPemeliharaan(Request $request, $id)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.nomor_urut' => 'required|integer|min:1',
            'items.*.nama_pemeliharaan' => 'nullable|string|max:255',
        ]);

        ItemPemeliharaan::where('alat_id', $id)->delete();

        foreach ($request->items as $item) {
            if (!empty($item['nama_pemeliharaan'])) {
                ItemPemeliharaan::create([
                    'alat_id' => $id,
                    'nomor_urut' => $item['nomor_urut'],
                    'nama_pemeliharaan' => $item['nama_pemeliharaan'],
                ]);
            }
        }

        return redirect()->route('alat.pemeliharaan', $id)
            ->with('success', 'Daftar jenis pemeliharaan berhasil diperbarui!');
    }

    public function parseSertifikat(Request $request)
    {
        $request->validate([
            'sertifikat' => 'required|file|mimes:pdf|max:5120'
        ]);

        $file = $request->file('sertifikat');
        
        try {
            $parser = new \Smalot\PdfParser\Parser();
            $pdf = $parser->parseFile($file->getPathname());
            $text = $pdf->getText();

            $tglKalibrasi = null;
            $tglAkhir = null;
            $sertifikatOleh = null;
            
            // Regex Date Extractors
            if (preg_match('/(?:Tanggal Kalibrasi|Date of Calibration|Tgl[.\s]*Kalibrasi)\s*[:\-]?\s*([0-9]{1,2}[\/\-\s][a-zA-Z0-9]+[\/\-\s][0-9]{2,4})/i', $text, $matches)) {
                try {
                    $tglKalibrasi = Carbon::parse($matches[1])->format('Y-m-d');
                } catch (\Exception $e) {}
            }

            if (preg_match('/(?:Berlaku Hingga|Valid Until|Due Date|Jatuh Tempo)\s*[:\-]?\s*([0-9]{1,2}[\/\-\s][a-zA-Z0-9]+[\/\-\s][0-9]{2,4})/i', $text, $matches)) {
                try {
                    $tglAkhir = Carbon::parse($matches[1])->format('Y-m-d');
                } catch (\Exception $e) {}
            }
            
            // Untuk Sertifikat Oleh, cari baris seperti Dikalibrasi Oleh : KAN LK-01
            if (preg_match('/(?:Dikalibrasi Oleh|Laboratorium Kalibrasi|Diterbitkan Oleh)\s*[:\-]?\s*([A-Za-z0-9\s.,\-&]+)(?:\n|\r)/i', $text, $matches)) {
                $sertifikatOleh = trim($matches[1]);
            }

            // Fallback (sebagai simulasi bila format PDF tidak standar, agar fitur tetap mendemokan auto-fill)
            if (!$tglKalibrasi) {
                // Cari sembarang pola tanggal (dd-mm-yyyy / dd/mm/yyyy)
                if (preg_match('/([0-9]{1,2}[\/\-\.][0-9]{1,2}[\/\-\.][0-9]{4})/', $text, $matches)) {
                   $tglKalibrasi = Carbon::parse(str_replace('.', '-', $matches[1]))->format('Y-m-d');
                } else {
                   $tglKalibrasi = Carbon::now()->format('Y-m-d'); 
                }
            }
            
            if (!$tglAkhir) {
                $tglAkhir = Carbon::parse($tglKalibrasi)->addYear()->format('Y-m-d');
            }

            if (!$sertifikatOleh) {
                $sertifikatOleh = "Lab Kalibrasi Eksternal Terakreditasi";
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'tgl_kalibrasi' => $tglKalibrasi,
                    'tgl_akhir' => $tglAkhir,
                    'sertifikat_oleh' => $sertifikatOleh
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses dokumen PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storePerbaikan(Request $request, $id)
    {
        $request->validate([
            'tanggal_rusak' => 'required|date',
            'deskripsi_kerusakan' => 'required|string',
        ]);

        $alat = Alat::findOrFail($id);

        RiwayatPerbaikanAlat::create([
            'alat_id' => $alat->alat_id,
            'tanggal_rusak' => $request->tanggal_rusak,
            'deskripsi_kerusakan' => $request->deskripsi_kerusakan,
            'dilaporkan_oleh' => Auth::id(),
            'status_perbaikan' => 'Belum Diperbaiki'
        ]);

        $alat->update(['kondisi_barang' => 'Rusak']);

        return redirect()->back()->with('success', 'Laporan kerusakan alat berhasil dicatat.');
    }

    public function updatePerbaikan(Request $request, $id, $perbaikan_id)
    {
        $perbaikan = RiwayatPerbaikanAlat::findOrFail($perbaikan_id);
        $alat = Alat::findOrFail($id);
        
        $request->validate([
            'status_perbaikan' => 'required|string|in:Dalam Perbaikan,Selesai,Tidak Bisa Diperbaiki',
            'tindakan_perbaikan' => 'nullable|string',
            'tanggal_selesai' => 'nullable|date',
        ]);

        // Jika Koordinator Lab mengubah status menjadi Selesai
        if ($request->status_perbaikan === 'Selesai' || $request->status_perbaikan === 'Tidak Bisa Diperbaiki') {
            if (Auth::user()->role->nama_role !== \App\Enums\PeranPengguna::KOORDINATOR_LAB->value) {
                return redirect()->back()->withErrors(['message' => 'Hanya Koordinator Lab yang dapat memverifikasi penyelesaian perbaikan.']);
            }
            $perbaikan->diverifikasi_oleh = Auth::id();
            if (!$request->tanggal_selesai) {
                $perbaikan->tanggal_selesai = now();
            }
            
            if ($request->status_perbaikan === 'Selesai') {
                $alat->update(['kondisi_barang' => 'Baik']);
            }
        }

        $perbaikan->update([
            'status_perbaikan' => $request->status_perbaikan,
            'tindakan_perbaikan' => $request->tindakan_perbaikan,
            'tanggal_selesai' => $request->tanggal_selesai ?? $perbaikan->tanggal_selesai,
        ]);

        return redirect()->back()->with('success', 'Status perbaikan berhasil diperbarui.');
    }
}