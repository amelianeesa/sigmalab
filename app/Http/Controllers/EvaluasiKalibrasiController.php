<?php

namespace App\Http\Controllers;

use App\Enums\PeranPengguna;
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
    private function adalahAdminAplikasi(): bool
    {
        $role = Auth::user()->role->nama_role ?? '';

        return $role === PeranPengguna::ADMIN_APLIKASI->value;
    }

    private function pastikanAdminAplikasi(): void
    {
        if (!$this->adalahAdminAplikasi()) {
            abort(403);
        }
    }

    private function evaluasiWorkspace()
    {
        return EvaluasiKalibrasi::whereHas('alat', function ($alat) {
            $alat->withTrashed();
        });
    }

    private function terapkanFilter($query, Request $request)
    {
        if ($request->filled('keputusan')) {
            $query->where('keputusan', $request->keputusan);
        }

        if ($request->filled('q')) {
            $kataKunci = trim($request->q);

            $query->whereHas('alat', function ($alat) use ($kataKunci) {
                $alat->where('nama_alat', 'like', "%{$kataKunci}%")
                    ->orWhere('kode_alat', 'like', "%{$kataKunci}%");
            });
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_evaluasi', $request->tanggal);
        }

        return $query;
    }

    public function index(Request $request)
    {
        $query = $this->evaluasiWorkspace()
            ->with(['alat', 'riwayatKalibrasi', 'evaluator'])
            ->where('diarsipkan', false);

        $this->terapkanFilter($query, $request);

        $evaluasi = $query->latest('evaluasi_id')->paginate(15)->withQueryString();
        $adminAplikasi = $this->adalahAdminAplikasi();

        return view('evaluasi-kalibrasi.index', compact('evaluasi', 'adminAplikasi'));
    }

    public function arsip(Request $request)
    {
        $query = $this->evaluasiWorkspace()
            ->with(['alat', 'riwayatKalibrasi', 'evaluator'])
            ->where('diarsipkan', true);

        $this->terapkanFilter($query, $request);

        $evaluasi = $query->latest('evaluasi_id')->paginate(15)->withQueryString();
        $adminAplikasi = $this->adalahAdminAplikasi();

        return view('evaluasi-kalibrasi.arsip', compact('evaluasi', 'adminAplikasi'));
    }

    public function arsipkan($id)
    {
        $this->pastikanAdminAplikasi();

        $this->evaluasiWorkspace()->where('evaluasi_id', $id)->firstOrFail();
        EvaluasiKalibrasi::where('evaluasi_id', $id)->update(['diarsipkan' => true]);

        return redirect()->route('evaluasi-kalibrasi.index')
            ->with('success', 'Data evaluasi berhasil dipindahkan ke arsip.');
    }

    public function arsipkanSemua()
    {
        $this->pastikanAdminAplikasi();

        $jumlah = EvaluasiKalibrasi::whereIn('evaluasi_id', $this->evaluasiWorkspace()->where('diarsipkan', false)->pluck('evaluasi_id'))->update(['diarsipkan' => true]);

        return redirect()->route('evaluasi-kalibrasi.index')
            ->with('success', $jumlah . ' data evaluasi berhasil dipindahkan ke arsip.');
    }

    public function tampilkan($id)
    {
        $this->pastikanAdminAplikasi();

        $this->evaluasiWorkspace()->where('evaluasi_id', $id)->firstOrFail();
        EvaluasiKalibrasi::where('evaluasi_id', $id)->update(['diarsipkan' => false]);

        return redirect()->route('evaluasi-kalibrasi.arsip')
            ->with('success', 'Data evaluasi berhasil ditampilkan kembali.');
    }

    public function tampilkanSemua()
    {
        $this->pastikanAdminAplikasi();

        $jumlah = EvaluasiKalibrasi::whereIn('evaluasi_id', $this->evaluasiWorkspace()->where('diarsipkan', true)->pluck('evaluasi_id'))->update(['diarsipkan' => false]);

        return redirect()->route('evaluasi-kalibrasi.arsip')
            ->with('success', $jumlah . ' data evaluasi berhasil ditampilkan kembali.');
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
        $evaluasi = $this->evaluasiWorkspace()->where('evaluasi_id', $id)
            ->with(['alat', 'riwayatKalibrasi', 'evaluator'])
            ->firstOrFail();

        return view('evaluasi-kalibrasi.show', compact('evaluasi'));
    }

    public function edit($id)
    {
        $evaluasi = $this->evaluasiWorkspace()->where('evaluasi_id', $id)->firstOrFail();
        $alatList = Alat::orderBy('nama_alat')->get();

        return view('evaluasi-kalibrasi.edit', compact('evaluasi', 'alatList'));
    }

    public function update(Request $request, $id)
    {
        $evaluasi = $this->evaluasiWorkspace()->where('evaluasi_id', $id)->firstOrFail();

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
        $evaluasi = $this->evaluasiWorkspace()->where('evaluasi_id', $id)->firstOrFail();

        if ($evaluasi->file_laporan && Storage::disk('public')->exists($evaluasi->file_laporan)) {
            Storage::disk('public')->delete($evaluasi->file_laporan);
        }

        EvaluasiKalibrasi::where('evaluasi_id', $id)->delete();

        return redirect()->route('evaluasi-kalibrasi.index')
            ->with('success', 'Evaluasi kalibrasi berhasil dihapus.');
    }

    public function hapusTerpilih(Request $request)
    {
        $this->pastikanAdminAplikasi();

        $ids = $request->input('ids', []);

        if (empty($ids) || !is_array($ids)) {
            return back()->with('error', 'Tidak ada data yang dipilih.');
        }

        $data = $this->evaluasiWorkspace()->whereIn('evaluasi_id', $ids)
            ->where('diarsipkan', true)
            ->get();

        foreach ($data as $item) {
            if ($item->file_laporan && Storage::disk('public')->exists($item->file_laporan)) {
                Storage::disk('public')->delete($item->file_laporan);
            }
        }

        $jumlah = EvaluasiKalibrasi::whereIn('evaluasi_id', $data->pluck('evaluasi_id'))->delete();

        return back()->with('success', $jumlah . ' data evaluasi terpilih berhasil dihapus permanen.');
    }

    public function hapusSemua()
    {
        $this->pastikanAdminAplikasi();

        $data = $this->evaluasiWorkspace()->where('diarsipkan', true)->get();

        foreach ($data as $item) {
            if ($item->file_laporan && Storage::disk('public')->exists($item->file_laporan)) {
                Storage::disk('public')->delete($item->file_laporan);
            }
        }

        $jumlah = EvaluasiKalibrasi::whereIn('evaluasi_id', $data->pluck('evaluasi_id'))->delete();

        return back()->with('success', $jumlah . ' data arsip evaluasi berhasil dihapus permanen.');
    }
}