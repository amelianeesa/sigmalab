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
        // Redirect to the new unified Verifikasi Mutu dashboard
        return redirect()->route('verifikasi-mutu.index');
    }

    public function create()
    {
        $this->authorize('create', Kegiatan::class);

        $alatList = Alat::where('kondisi_barang', 'baik')->get();
        $personilList = Personil::where('status_aktif', true)->get();
        $barangList = Barang::all();
        $parameterList = ParameterUji::where('status_aktif', true)->get();
        $nextKodeSampel = $this->generateKodeSampel();
        $crmKatalogList = \App\Models\CrmKatalog::with('sertifikats')->where('is_active', true)->get();

        return view('kegiatan.create', compact('alatList', 'personilList', 'barangList', 'parameterList', 'nextKodeSampel', 'crmKatalogList'));
    }

    public function store(\App\Http\Requests\KegiatanRequest $request)
    {
        $this->authorize('create', Kegiatan::class);

        $validated = $request->validated();

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
                    $inventoryService = app(\App\Services\InventoryService::class);
                    foreach ($validated['barang_ids'] as $barangId) {
                        $jumlah = (float) $request->input("barang_jumlah.{$barangId}", 0);
                        if ($jumlah > 0) {
                            $barang = Barang::find($barangId);
                            if ($barang) {
                                $inventoryService->deductStock($barang, $jumlah, $kegiatan->kegiatan_id);
                            }
                        }
                    }
                }
                
                // Pre-populate HasilUji
                $hasilData = [];
                if (!empty($validated['normal_parameter_uji_ids'])) {
                    foreach ($validated['normal_parameter_uji_ids'] as $paramId) {
                        $hasilData[] = [
                            'kegiatan_id' => $kegiatan->kegiatan_id,
                            'parameter_uji_id' => $paramId,
                            'jenis_kontrol' => 'normal', // Sampel klien
                            'status_berketerimaan' => 'belum_diuji',
                            'diinput_oleh' => Auth::id(),
                            'created_at' => now(),
                        ];
                    }
                }
                if (!empty($validated['inhouse_parameter_uji_ids'])) {
                    foreach ($validated['inhouse_parameter_uji_ids'] as $paramId) {
                        $hasilData[] = [
                            'kegiatan_id' => $kegiatan->kegiatan_id,
                            'parameter_uji_id' => $paramId,
                            'jenis_kontrol' => 'in_house',
                            'status_berketerimaan' => 'belum_diuji',
                            'diinput_oleh' => Auth::id(),
                            'created_at' => now(),
                        ];
                    }
                }
                if (!empty($validated['crm_parameter_uji_ids'])) {
                    foreach ($validated['crm_parameter_uji_ids'] as $paramId) {
                        $hasilData[] = [
                            'kegiatan_id' => $kegiatan->kegiatan_id,
                            'parameter_uji_id' => $paramId,
                            'jenis_kontrol' => 'crm',
                            'crm_katalog_id' => $request->input("crm_katalog_ids.{$paramId}"),
                            'status_berketerimaan' => 'belum_diuji',
                            'diinput_oleh' => Auth::id(),
                            'created_at' => now(),
                        ];
                    }
                }
                if (!empty($hasilData)) {
                    \App\Models\HasilUji::insert($hasilData);
                }
            });
        });

        return redirect()->route('verifikasi-mutu.index')->with('success', 'Kegiatan berhasil dibuat.');
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

        // Parameter Uji
        $parameterList = \App\Models\ParameterUji::where('status_aktif', true)->get();
        $selectedNormal = \App\Models\HasilUji::where('kegiatan_id', $kegiatan->kegiatan_id)->where('jenis_kontrol', 'normal')->pluck('parameter_uji_id')->toArray();
        $selectedInhouse = \App\Models\HasilUji::where('kegiatan_id', $kegiatan->kegiatan_id)->where('jenis_kontrol', 'in_house')->pluck('parameter_uji_id')->toArray();
        $selectedCrm = \App\Models\HasilUji::where('kegiatan_id', $kegiatan->kegiatan_id)->where('jenis_kontrol', 'crm')->pluck('parameter_uji_id')->toArray();
        $selectedCrmKatalogs = \App\Models\HasilUji::where('kegiatan_id', $kegiatan->kegiatan_id)->where('jenis_kontrol', 'crm')->pluck('crm_katalog_id', 'parameter_uji_id')->toArray();
        $crmKatalogList = \App\Models\CrmKatalog::with('sertifikats')->where('is_active', true)->get();

        return view('kegiatan.edit', compact('kegiatan', 'alatList', 'personilList', 'barangList', 'selectedAlat', 'selectedPersonil', 'personilPeran', 'selectedBarang', 'barangJumlah', 'parameterList', 'selectedNormal', 'selectedInhouse', 'selectedCrm', 'selectedCrmKatalogs', 'crmKatalogList'));
    }

    public function update(\App\Http\Requests\KegiatanRequest $request, $id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        $this->authorize('update', $kegiatan);

        $validated = $request->validated();

        DB::transaction(function () use ($request, $validated, $kegiatan) {
            $kegiatan->update([
                'nama_kegiatan' => $validated['nama_kegiatan'],
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

            $inventoryService = app(\App\Services\InventoryService::class);
            $oldTransaksis = TransaksiBarang::where('kegiatan_id', $kegiatan->kegiatan_id)->get();
            foreach($oldTransaksis as $t) {
                $inventoryService->rollbackTransaction($t);
            }

            if (!empty($validated['barang_ids'])) {
                foreach ($validated['barang_ids'] as $barangId) {
                    $jumlah = (float) $request->input("barang_jumlah.{$barangId}", 0);
                    if ($jumlah > 0) {
                        $barang = Barang::find($barangId);
                        if ($barang) {
                            $inventoryService->deductStock($barang, $jumlah, $kegiatan->kegiatan_id);
                        }
                    }
                }
            } // End if barang_ids
                
            // Add-Only Sync for Parameter Uji (Penambahan Susulan)
            $hasilData = [];
            
            if (!empty($validated['parameter_uji_ids'])) {
                if (!empty($validated['is_normal'])) {
                    $existingNormal = \App\Models\HasilUji::where('kegiatan_id', $kegiatan->kegiatan_id)->where('jenis_kontrol', 'normal')->pluck('parameter_uji_id')->toArray();
                    $newNormal = array_diff($validated['parameter_uji_ids'], $existingNormal);
                    foreach ($newNormal as $paramId) {
                        $hasilData[] = [
                            'kegiatan_id' => $kegiatan->kegiatan_id,
                            'parameter_uji_id' => $paramId,
                            'jenis_kontrol' => 'normal',
                            'status_berketerimaan' => 'belum_diuji',
                            'diinput_oleh' => Auth::id(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }

                if (!empty($validated['is_inhouse'])) {
                    $existingInhouse = \App\Models\HasilUji::where('kegiatan_id', $kegiatan->kegiatan_id)->where('jenis_kontrol', 'in_house')->pluck('parameter_uji_id')->toArray();
                    $newInhouse = array_diff($validated['parameter_uji_ids'], $existingInhouse);
                    foreach ($newInhouse as $paramId) {
                        $hasilData[] = [
                            'kegiatan_id' => $kegiatan->kegiatan_id,
                            'parameter_uji_id' => $paramId,
                            'jenis_kontrol' => 'in_house',
                            'status_berketerimaan' => 'belum_diuji',
                            'diinput_oleh' => Auth::id(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
                
                if (!empty($validated['is_crm'])) {
                    $existingCrm = \App\Models\HasilUji::where('kegiatan_id', $kegiatan->kegiatan_id)->where('jenis_kontrol', 'crm')->pluck('parameter_uji_id')->toArray();
                    $newCrm = array_diff($validated['parameter_uji_ids'], $existingCrm);
                    foreach ($newCrm as $paramId) {
                        $hasilData[] = [
                            'kegiatan_id' => $kegiatan->kegiatan_id,
                            'parameter_uji_id' => $paramId,
                            'jenis_kontrol' => 'crm',
                            'crm_katalog_id' => $request->input("crm_katalog_ids.{$paramId}"),
                            'status_berketerimaan' => 'belum_diuji',
                            'diinput_oleh' => Auth::id(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }
            if (!empty($hasilData)) {
                \App\Models\HasilUji::insert($hasilData);
            }
        });

        return redirect()->route('verifikasi-mutu.index')->with('success', 'Kegiatan berhasil diperbarui.');
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

            $inventoryService = app(\App\Services\InventoryService::class);
            $oldTransaksis = TransaksiBarang::where('kegiatan_id', $kegiatan->kegiatan_id)->get();
            foreach($oldTransaksis as $t) {
                $inventoryService->rollbackTransaction($t);
            }

            $kegiatan->delete();
        });

        return redirect()->route('verifikasi-mutu.index')->with('success', 'Kegiatan berhasil dihapus.');
    }

    public function unlock($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        
        // Authorization: only specific roles can unlock
        if (!auth()->user()->hasRole(['koordinator_lab', 'manajer_teknis', 'admin'])) {
            abort(403, 'Anda tidak memiliki hak akses untuk membuka kunci kegiatan.');
        }

        if ($kegiatan->status_kegiatan !== 'selesai') {
            return back()->with('error', 'Kegiatan ini tidak dalam status Selesai.');
        }

        $kegiatan->status_kegiatan = 'proses';
        $kegiatan->save();

        activity()
            ->performedOn($kegiatan)
            ->causedBy(auth()->user())
            ->log("Membuka kunci (revisi) kegiatan pengujian.");

        return redirect()->route('kegiatan.show', $kegiatan->kegiatan_id)
            ->with('success', 'Kegiatan berhasil dibuka kembali untuk revisi (Status: Proses).');
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
