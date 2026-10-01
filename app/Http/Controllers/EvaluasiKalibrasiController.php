<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\EvaluasiKalibrasi;
use App\Models\RiwayatKalibrasi;
use App\Models\RiwayatPerbaikanAlat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EvaluasiKalibrasiController extends Controller
{
    public function index(Request $request)
    {
        $query = EvaluasiKalibrasi::with(['alat', 'riwayatKalibrasi', 'evaluator']);

        if ($request->filled('keputusan')) {
            $query->where('keputusan', $request->keputusan);
        }

        $evaluasi = $query->latest('evaluasi_id')->paginate(15);

        return view('evaluasi-kalibrasi.index', compact('evaluasi'));
    }

    public function create()
    {
        $alatList = Alat::orderBy('nama_alat')->get();

        return view('evaluasi-kalibrasi.create', compact('alatList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'alat_id' => 'required|exists:alat,alat_id',
            'tanggal_evaluasi' => 'required|date',
            'file_laporan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'catatan_spesifikasi' => 'nullable|string',
            'keputusan' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($request) {
            $alat = Alat::findOrFail($request->alat_id);

            $riwayatTerakhir = RiwayatKalibrasi::where('alat_id', $alat->alat_id)
                ->latest('tgl_kalibrasi')
                ->first();

            $dataEvaluasi = [
                'alat_id' => $alat->alat_id,
                'riwayat_kalibrasi_id' => $riwayatTerakhir?->riwayat_kalibrasi_id,
                'tanggal_evaluasi' => $request->tanggal_evaluasi,
                'catatan_spesifikasi' => $request->catatan_spesifikasi,
                'keputusan' => $request->keputusan,
                'dievaluasi_oleh' => Auth::id(),
            ];

            if ($request->hasFile('file_laporan')) {
                $dataEvaluasi['file_laporan'] = $request->file('file_laporan')->store('laporan_evaluasi', 'public');
            }

            EvaluasiKalibrasi::create($dataEvaluasi);

            switch ($request->keputusan) {
                case 'idle':
                    $alat->update([
                        'kondisi_barang' => 'baik',
                        'status_barang' => 'idle',
                        'nonaktif' => false,
                    ]);
                    break;

                case 'perbaikan':
                    RiwayatPerbaikanAlat::create([
                        'alat_id' => $alat->alat_id,
                        'tanggal_rusak' => $request->tanggal_evaluasi,
                        'deskripsi_kerusakan' => 'Hasil evaluasi kalibrasi: ' . ($request->catatan_spesifikasi ?? '-'),
                        'dilaporkan_oleh' => Auth::id(),
                        'status_perbaikan' => 'Belum Diperbaiki',
                    ]);

                    $alat->update([
                        'kondisi_barang' => 'perbaikan',
                        'status_barang' => 'idle',
                        'nonaktif' => false,
                    ]);
                    break;

                case 'ganti_alat':
                    $alat->update([
                        'kondisi_barang' => 'rusak',
                        'status_barang' => 'idle',
                        'nonaktif' => true,
                    ]);
                    break;
            }
        });

        return redirect()->route('evaluasi-kalibrasi.index')
            ->with('success', 'Evaluasi kalibrasi berhasil disimpan dan status alat telah diperbarui');
    }

    public function show($id)
    {
        $evaluasi = EvaluasiKalibrasi::where('evaluasi_id', $id)
            ->with(['alat', 'riwayatKalibrasi', 'evaluator'])
            ->firstOrFail();

        return view('evaluasi-kalibrasi.show', compact('evaluasi'));
    }

    public function edit($id)
    {
        $evaluasi = EvaluasiKalibrasi::where('evaluasi_id', $id)->firstOrFail();
        $alatList = Alat::orderBy('nama_alat')->get();
        return view('evaluasi-kalibrasi.edit', compact('evaluasi', 'alatList'));
    }

    public function update(Request $request, $id)
    {
        $evaluasi = EvaluasiKalibrasi::where('evaluasi_id', $id)->firstOrFail();

        $request->validate([
            'alat_id' => 'required|exists:alat,alat_id',
            'tanggal_evaluasi' => 'required|date',
            'keputusan' => 'required|string|max:255',
            'catatan_spesifikasi' => 'nullable|string',
            'file_laporan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        DB::transaction(function () use ($request, $evaluasi) {
            $alat = Alat::findOrFail($request->alat_id);
            
            $data = [
                'alat_id' => $request->alat_id,
                'tanggal_evaluasi' => $request->tanggal_evaluasi,
                'keputusan' => $request->keputusan,
                'catatan_spesifikasi' => $request->catatan_spesifikasi,
            ];

            if ($request->hasFile('file_laporan')) {
                if ($evaluasi->file_laporan && Storage::disk('public')->exists($evaluasi->file_laporan)) {
                    Storage::disk('public')->delete($evaluasi->file_laporan);
                }
                $data['file_laporan'] = $request->file('file_laporan')->store('laporan_evaluasi', 'public');
            }

            // Update menggunakan query builder / instance model langsung berdasarkan primary key yang pasti
            EvaluasiKalibrasi::where('evaluasi_id', $evaluasi->evaluasi_id)->update($data);

            switch ($request->keputusan) {
                case 'idle':
                    $alat->update([
                        'kondisi_barang' => 'baik',
                        'status_barang' => 'idle',
                        'nonaktif' => false,
                    ]);
                    break;

                case 'perbaikan':
                    $alat->update([
                        'kondisi_barang' => 'perbaikan',
                        'status_barang' => 'idle',
                        'nonaktif' => false,
                    ]);
                    break;

                case 'ganti_alat':
                    $alat->update([
                        'kondisi_barang' => 'rusak',
                        'status_barang' => 'idle',
                        'nonaktif' => true,
                    ]);
                    break;
            }
        });

        return redirect()->route('evaluasi-kalibrasi.index')
            ->with('success', 'Evaluasi kalibrasi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $evaluasi = EvaluasiKalibrasi::where('evaluasi_id', $id)->firstOrFail();

        if ($evaluasi->file_laporan && Storage::disk('public')->exists($evaluasi->file_laporan)) {
            Storage::disk('public')->delete($evaluasi->file_laporan);
        }

        EvaluasiKalibrasi::where('evaluasi_id', $id)->delete();

        return redirect()->route('evaluasi-kalibrasi.index')
            ->with('success', 'Evaluasi kalibrasi berhasil dihapus.');
    }
}