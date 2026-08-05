<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\RiwayatKalibrasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
                $kalibrasiTerakhir = $item->riwayatKalibrasi()->latest('tgl_kalibrasi')->first();
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
                'signifikan' => $request->signifikan,
            ]);
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

        // Ubah validasi bagian kalibrasi menjadi 'nullable' agar boleh dikosongkan
        $request->validate([
            'kode_alat' => 'required|string|max:50|unique:alat,kode_alat,' . $id . ',alat_id',
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
        ]);

        DB::transaction(function () use ($request, $alat) {
            $alat->update([
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
                'signifikan' => $request->signifikan,
            ];

            if ($kalibrasiTerakhir) {
                $kalibrasiTerakhir->update($dataKalibrasi);
            } else {
                // Hanya buat baru jika ada salah satu input kalibrasi yang diisi, atau langsung buat
                $dataKalibrasi['alat_id'] = $alat->alat_id;
                RiwayatKalibrasi::create($dataKalibrasi);
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
}