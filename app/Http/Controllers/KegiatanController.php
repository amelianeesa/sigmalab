<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Alat;
use App\Models\Personil;
use App\Models\ParameterUji;
use App\Models\Barang;
use App\Models\TransaksiBarang;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Enums\PeranPengguna;

class KegiatanController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', Kegiatan::class);

        $filterJenis = $request->input('jenis_kegiatan');
        $filterStatus = $request->input('status_kegiatan');
        $search = $request->input('search');

        $query = Kegiatan::with(['pembuatKegiatan', 'alatDigunakan', 'personilTerlibat']);

        if ($search) {
            $query->where('kode_sampel', 'LIKE', "%{$search}%");
        }

        if ($filterJenis) {
            $query->where('jenis_kegiatan', $filterJenis);
        }

        if ($filterStatus) {
            $query->where('status_kegiatan', $filterStatus);
        }

        $kegiatans = $query->latest()->paginate(10);

        return view('kegiatan.index', compact('kegiatans', 'filterJenis', 'filterStatus', 'search'));
    }

    public function create()
    {
        $this->authorize('create', Kegiatan::class);

        $alatList = Alat::where('kondisi_barang', 'baik')->get();
        $personilList = Personil::where('status_aktif', true)->get();
        $barangList = Barang::all();
        $nextKodeSampel = $this->generateKodeSampel();

        return view('kegiatan.create', compact('alatList', 'personilList', 'barangList', 'nextKodeSampel'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Kegiatan::class);

        $validated = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'jenis_kegiatan' => 'required|in:pengujian,kalibrasi',
            'kode_sampel' => 'nullable|string|max:50',
            'tanggal_kegiatan' => 'required|date',
            'status_kegiatan' => 'required|in:draft,berjalan,selesai,dibatalkan',
            'alat_ids' => 'nullable|array',
            'alat_ids.*' => 'exists:alat,alat_id',
            'personil_ids' => 'nullable|array',
            'personil_ids.*' => 'exists:personil,personil_id',
            'personil_peran' => 'nullable|array',
            'barang_ids' => 'nullable|array',
            'barang_ids.*' => 'exists:barang,barang_id',
            'barang_jumlah' => 'nullable|array',
        ]);

        // Gate 1: Validasi Kalibrasi Alat
        if (!empty($validated['alat_ids'])) {
            $alatKedaluwarsa = Alat::whereIn('alat_id', $validated['alat_ids'])
                ->whereDoesntHave('riwayatKalibrasi', function($query) {
                    $query->where('tgl_akhir', '>=', now());
                })->get();
            
            if ($alatKedaluwarsa->isNotEmpty()) {
                $namaAlat = $alatKedaluwarsa->pluck('nama_alat')->join(', ');
                return back()->withInput()->with('error', "Validasi Gagal (Gate 1): Alat berikut belum dikalibrasi atau masa kalibrasinya sudah kedaluwarsa: {$namaAlat}");
            }
        }

        // Gate 2: Validasi Kompetensi Personel
        if (!empty($validated['personil_ids'])) {
            $personelTidakAktif = Personil::whereIn('personil_id', $validated['personil_ids'])
                ->where('status_aktif', false)->get();
            
            if ($personelTidakAktif->isNotEmpty()) {
                $namaPersonel = $personelTidakAktif->pluck('nama_lengkap')->join(', ');
                return back()->withInput()->with('error', "Validasi Gagal (Gate 2): Personel berikut berstatus tidak aktif atau kompetensinya dicabut: {$namaPersonel}");
            }
        }

        // Gate 3: Validasi Stok Bahan/Reagen
        if (!empty($validated['barang_ids'])) {
            $barangTidakCukup = [];
            foreach ($validated['barang_ids'] as $barangId) {
                $jumlahReq = (float) $request->input("barang_jumlah.{$barangId}", 0);
                if ($jumlahReq > 0) {
                    $barang = Barang::find($barangId);
                    if ($barang && $barang->saldo_akhir < $jumlahReq) {
                        $barangTidakCukup[] = "{$barang->nama_barang} (Sisa stok: {$barang->saldo_akhir}, Diminta: {$jumlahReq})";
                    }
                }
            }
            if (!empty($barangTidakCukup)) {
                return back()->withInput()->with('error', "Validasi Gagal (Gate 3): Stok bahan berikut tidak mencukupi untuk kegiatan ini: " . implode(' | ', $barangTidakCukup));
            }
        }

        $lock = Cache::lock('generate_kode_sampel', 10);
        $lock->block(10, function () use ($request, $validated) {
            DB::transaction(function () use ($request, $validated) {
                $kegiatan = Kegiatan::create([
                    'nama_kegiatan' => $validated['nama_kegiatan'],
                    'jenis_kegiatan' => $validated['jenis_kegiatan'],
                    'kode_sampel' => $this->generateKodeSampel(),
                    'tanggal_kegiatan' => $validated['tanggal_kegiatan'],
                    'status_kegiatan' => $validated['status_kegiatan'],
                    'dibuat_oleh' => Auth::id(),
                ]);

                // Attach alat
                if (!empty($validated['alat_ids'])) {
                    $kegiatan->alatDigunakan()->attach($validated['alat_ids']);
                }

                // Attach personil with peran
                if (!empty($validated['personil_ids'])) {
                    $syncData = [];
                    foreach ($validated['personil_ids'] as $index => $personilId) {
                        $peran = $request->input("personil_peran.{$personilId}", 'Analis');
                        $syncData[$personilId] = ['peran' => $peran];
                    }
                    $kegiatan->personilTerlibat()->attach($syncData);
                }

                // Attach barang dan catat transaksi
                if (!empty($validated['barang_ids'])) {
                    foreach ($validated['barang_ids'] as $barangId) {
                        $jumlah = (float) $request->input("barang_jumlah.{$barangId}", 0);
                        if ($jumlah > 0) {
                            $barang = Barang::where('barang_id', $barangId)->lockForUpdate()->first();
                            if ($barang) {
                                $barang->pengeluaran += $jumlah;
                                $barang->saldo_akhir = ($barang->saldo_awal + $barang->penerimaan) - $barang->pengeluaran;
                                $barang->save();

                                TransaksiBarang::create([
                                    'barang_id' => $barangId,
                                    'kegiatan_id' => $kegiatan->kegiatan_id,
                                    'jumlah_pengeluaran' => $jumlah,
                                    'harga' => $barang->harga_rata ?? 0,
                                ]);
                            }
                        }
                    }
                }
            });
        });

        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil dibuat.');
    }

    public function show($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        $this->authorize('view', $kegiatan);

        $kegiatan->load(['pembuatKegiatan', 'alatDigunakan', 'personilTerlibat', 'hasilUji.parameterUji', 'hasilUji.tindakLanjut', 'transaksiBarang.barang']);
        $parameterList = ParameterUji::where('status_aktif', true)->get();

        return view('kegiatan.show', compact('kegiatan', 'parameterList'));
    }

    public function edit($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        $this->authorize('update', $kegiatan);

        $alatList = Alat::where('kondisi_barang', 'baik')->get();
        $personilList = Personil::where('status_aktif', true)->get();
        $barangList = Barang::all();
        $selectedAlat = $kegiatan->alatDigunakan->pluck('alat_id')->toArray();
        $selectedPersonil = $kegiatan->personilTerlibat->pluck('personil_id')->toArray();
        $personilPeran = $kegiatan->personilTerlibat->pluck('pivot.peran', 'personil_id')->toArray();
        
        $transaksis = TransaksiBarang::where('kegiatan_id', $kegiatan->kegiatan_id)->get();
        $selectedBarang = $transaksis->pluck('barang_id')->toArray();
        $barangJumlah = $transaksis->pluck('jumlah_pengeluaran', 'barang_id')->toArray();

        return view('kegiatan.edit', compact('kegiatan', 'alatList', 'personilList', 'barangList', 'selectedAlat', 'selectedPersonil', 'personilPeran', 'selectedBarang', 'barangJumlah'));
    }

    public function update(Request $request, $id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        $this->authorize('update', $kegiatan);

        $validated = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'jenis_kegiatan' => 'required|in:pengujian,kalibrasi',
            'kode_sampel' => 'nullable|string|max:50',
            'tanggal_kegiatan' => 'required|date',
            'status_kegiatan' => 'required|in:draft,berjalan,selesai,dibatalkan',
            'alat_ids' => 'nullable|array',
            'alat_ids.*' => 'exists:alat,alat_id',
            'personil_ids' => 'nullable|array',
            'personil_ids.*' => 'exists:personil,personil_id',
            'personil_peran' => 'nullable|array',
            'barang_ids' => 'nullable|array',
            'barang_ids.*' => 'exists:barang,barang_id',
            'barang_jumlah' => 'nullable|array',
        ]);

        DB::transaction(function () use ($request, $validated, $kegiatan) {
            $kegiatan->update([
                'nama_kegiatan' => $validated['nama_kegiatan'],
                'jenis_kegiatan' => $validated['jenis_kegiatan'],
                'kode_sampel' => $validated['kode_sampel'],
                'tanggal_kegiatan' => $validated['tanggal_kegiatan'],
                'status_kegiatan' => $validated['status_kegiatan'],
            ]);

            $kegiatan->alatDigunakan()->sync($validated['alat_ids'] ?? []);

            $syncData = [];
            if (!empty($validated['personil_ids'])) {
                foreach ($validated['personil_ids'] as $personilId) {
                    $peran = $request->input("personil_peran.{$personilId}", 'Analis');
                    $syncData[$personilId] = ['peran' => $peran];
                }
            }
            $kegiatan->personilTerlibat()->sync($syncData);

            $oldTransaksis = TransaksiBarang::where('kegiatan_id', $kegiatan->kegiatan_id)->get();
            foreach($oldTransaksis as $t) {
                $b = Barang::where('barang_id', $t->barang_id)->lockForUpdate()->first();
                if ($b) {
                    $b->pengeluaran -= $t->jumlah_pengeluaran;
                    $b->saldo_akhir = ($b->saldo_awal + $b->penerimaan) - $b->pengeluaran;
                    $b->save();
                }
            }
            TransaksiBarang::where('kegiatan_id', $kegiatan->kegiatan_id)->delete();

            if (!empty($validated['barang_ids'])) {
                foreach ($validated['barang_ids'] as $barangId) {
                    $jumlah = (float) $request->input("barang_jumlah.{$barangId}", 0);
                    if ($jumlah > 0) {
                        $barang = Barang::where('barang_id', $barangId)->lockForUpdate()->first();
                        if ($barang) {
                            $barang->pengeluaran += $jumlah;
                            $barang->saldo_akhir = ($barang->saldo_awal + $barang->penerimaan) - $barang->pengeluaran;
                            $barang->save();

                            TransaksiBarang::create([
                                'barang_id' => $barangId,
                                'kegiatan_id' => $kegiatan->kegiatan_id,
                                'jumlah_pengeluaran' => $jumlah,
                                'harga' => $barang->harga_rata ?? 0,
                            ]);
                        }
                    }
                }
            }
        });

        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        $this->authorize('delete', $kegiatan);

        DB::transaction(function () use ($kegiatan) {
            $kegiatan->alatDigunakan()->detach();
            $kegiatan->personilTerlibat()->detach();
            
            foreach ($kegiatan->hasilUji as $hasil) {
                $hasil->tindakLanjut()->delete();
            }
            $kegiatan->hasilUji()->delete();

            $oldTransaksis = TransaksiBarang::where('kegiatan_id', $kegiatan->kegiatan_id)->get();
            foreach($oldTransaksis as $t) {
                $b = Barang::where('barang_id', $t->barang_id)->lockForUpdate()->first();
                if ($b) {
                    $b->pengeluaran -= $t->jumlah_pengeluaran;
                    $b->saldo_akhir = ($b->saldo_awal + $b->penerimaan) - $b->pengeluaran;
                    $b->save();
                }
            }
            TransaksiBarang::where('kegiatan_id', $kegiatan->kegiatan_id)->delete();

            $kegiatan->delete();
        });

        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil dihapus.');
    }

    private function generateKodeSampel()
    {
        $year = date('Y');
        $lastKegiatan = Kegiatan::where('kode_sampel', 'like', 'SMP-' . $year . '-%')
            ->orderBy('kode_sampel', 'desc')
            ->first();

        if ($lastKegiatan) {
            $lastSequence = (int) substr($lastKegiatan->kode_sampel, -3);
            $nextSequence = $lastSequence + 1;
        } else {
            $nextSequence = 1;
        }

        return 'SMP-' . $year . '-' . str_pad($nextSequence, 3, '0', STR_PAD_LEFT);
    }
}
