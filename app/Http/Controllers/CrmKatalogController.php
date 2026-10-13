<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CrmKatalog;
use App\Models\CrmSertifikat;
use App\Models\ParameterUji;

class CrmKatalogController extends Controller
{
    public function index()
    {
        return redirect()->route('qc-crm.index');
    }

    public function create()
    {
        $parameterList = ParameterUji::where('status_aktif', true)->orderBy('nama_parameter')->get();
        return view('qc-crm.katalog.create', compact('parameterList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:150',
            'produsen' => 'nullable|string|max:150',
            'nomor_lot' => 'required|string|max:50|unique:crm_katalog,nomor_lot',
            'nomor_sertifikat' => 'nullable|string|max:100',
            'tanggal_expired' => 'nullable|date',
            'coa_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'parameters' => 'nullable|array',
            'parameters.*.parameter_uji_id' => 'required|exists:parameter_uji,parameter_uji_id',
            'parameters.*.cert_value' => 'required|numeric',
            'parameters.*.cert_u' => 'required|numeric',
        ]);

        $coaPath = null;
        if ($request->hasFile('coa_file')) {
            $coaPath = $request->file('coa_file')->store('crm-coa', 'public');
        }

        $katalog = CrmKatalog::create([
            'nama_produk' => $request->nama_produk,
            'produsen' => $request->produsen,
            'nomor_lot' => $request->nomor_lot,
            'nomor_sertifikat' => $request->nomor_sertifikat,
            'tanggal_expired' => $request->tanggal_expired,
            'coa_file' => $coaPath,
            'status' => 'menunggu_verifikasi',
            'is_active' => false,
        ]);

        if (!empty($request->parameters)) {
            $sertifikats = [];
            foreach ($request->parameters as $p) {
                $sertifikats[] = [
                    'crm_katalog_id' => $katalog->id,
                    'parameter_uji_id' => $p['parameter_uji_id'],
                    'cert_value' => $p['cert_value'],
                    'cert_u' => $p['cert_u'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            CrmSertifikat::insert($sertifikats);
        }

        return redirect()->route('qc-crm.index')->with('success', 'Botol CRM berhasil ditambahkan dan menunggu verifikasi administratif.');
    }

    public function update(Request $request, $id)
    {
        $katalog = CrmKatalog::findOrFail($id);
        $request->validate([
            'nama_produk' => 'required|string|max:150',
            'produsen' => 'nullable|string|max:150',
            'nomor_lot' => 'required|string|max:50|unique:crm_katalog,nomor_lot,'.$id,
            'nomor_sertifikat' => 'nullable|string|max:100',
            'tanggal_expired' => 'nullable|date',
            'coa_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'is_active' => 'boolean'
        ]);

        $dataToUpdate = [
            'nama_produk' => $request->nama_produk,
            'produsen' => $request->produsen,
            'nomor_lot' => $request->nomor_lot,
            'nomor_sertifikat' => $request->nomor_sertifikat,
            'tanggal_expired' => $request->tanggal_expired,
            'is_active' => $request->has('is_active') ? true : false,
        ];

        if ($request->hasFile('coa_file')) {
            // Hapus file lama supaya tidak menumpuk sampah di storage
            if ($katalog->coa_file) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($katalog->coa_file);
            }
            $dataToUpdate['coa_file'] = $request->file('coa_file')->store('crm-coa', 'public');
        }

        $katalog->update($dataToUpdate);

        return redirect()->route('qc-crm.index')->with('success', 'Botol CRM berhasil diperbarui.');
    }

    public function show($id)
    {
        $katalog = CrmKatalog::with('sertifikats.parameterUji')->findOrFail($id);
        $parameterList = ParameterUji::where('status_aktif', true)->orderBy('nama_parameter')->get();
        
        return view('qc-crm.katalog.show', compact('katalog', 'parameterList'));
    }

    public function storeSertifikat(Request $request, $id)
    {
        $katalog = CrmKatalog::findOrFail($id);
        $request->validate([
            'parameter_uji_id' => 'required|exists:parameter_uji,parameter_uji_id',
            'cert_value' => 'required|numeric',
            'cert_u' => 'required|numeric',
        ]);

        // Cek apakah parameter sudah ada di sertifikat ini
        if ($katalog->sertifikats()->where('parameter_uji_id', $request->parameter_uji_id)->exists()) {
            return redirect()->back()->with('error', 'Parameter uji tersebut sudah ada di sertifikat botol ini. Silakan hapus yang lama terlebih dahulu jika ingin mengganti.');
        }

        CrmSertifikat::create([
            'crm_katalog_id' => $katalog->id,
            'parameter_uji_id' => $request->parameter_uji_id,
            'cert_value' => $request->cert_value,
            'cert_u' => $request->cert_u,
        ]);

        return redirect()->route('crm-katalog.show', $katalog->id)->with('success', 'Nilai sertifikat parameter berhasil ditambahkan.');
    }

    public function destroySertifikat($id, $sertifikat_id)
    {
        $sertifikat = CrmSertifikat::where('crm_katalog_id', $id)->findOrFail($sertifikat_id);
        $sertifikat->delete();

        return redirect()->route('crm-katalog.show', $id)->with('success', 'Nilai sertifikat parameter berhasil dihapus.');
    }

    public function verifikasiAdministratifForm($id)
    {
        $katalog = CrmKatalog::findOrFail($id);

        if ($katalog->status !== 'menunggu_verifikasi') {
            return redirect()->route('crm-katalog.show', $id)->with('error', 'Botol ini sudah melewati tahap verifikasi administratif.');
        }

        return view('qc-crm.katalog.verifikasi-administratif', compact('katalog'));
    }

    public function verifikasiAdministratifStore(Request $request, $id)
    {
        $katalog = CrmKatalog::findOrFail($id);

        $request->validate([
            'checklist' => 'required|array',
            'checklist.segel_utuh' => 'required|in:0,1',
            'checklist.fisik_baik' => 'required|in:0,1',
            'checklist.label_sesuai' => 'required|in:0,1',
            'checklist.belum_kadaluarsa' => 'required|in:0,1',
            'catatan' => 'nullable|string',
            'keputusan' => 'required|in:lolos,ditolak',
        ]);

        $katalog->update([
            'verifikasi_administratif_checklist' => $request->checklist,
            'verifikasi_administratif_catatan' => $request->catatan,
            'verifikasi_administratif_oleh' => auth()->user()->personil_id,
            'verifikasi_administratif_at' => now(),
            'status' => $request->keputusan === 'lolos' ? 'menunggu_verifikasi_teknis' : 'ditolak',
        ]);

            if ($request->keputusan === 'lolos') {
                return redirect()->route('crm-katalog.verifikasi-teknis.form', $id)
                    ->with('success', 'Verifikasi administratif berhasil. Silakan lengkapi Verifikasi Teknis.');
            }

            return redirect()->route('crm-katalog.show', $id)
                ->with('success', 'Botol ditolak pada tahap verifikasi administratif.');
    }

    public function verifikasiTeknisForm($id)
    {
        $katalog = CrmKatalog::with('sertifikats.parameterUji')->findOrFail($id);

        if ($katalog->status !== 'menunggu_verifikasi_teknis') {
            return redirect()->route('crm-katalog.show', $id)->with('error', 'Botol ini belum siap atau sudah melewati tahap verifikasi teknis.');
        }

        $existingData = \App\Models\CrmVerifikasiTeknis::with(['personils', 'alats', 'bahans'])
            ->where('crm_katalog_id', $id)
            ->get()
            ->keyBy('parameter_uji_id');

        $personilList = \App\Models\Personil::orderBy('nama')->get();
        $alatList = \App\Models\Alat::where('kondisi_barang', 'baik')->orderBy('nama_alat')->get();
        $barangList = \App\Models\Barang::orderBy('nama_barang')->get();

        return view('qc-crm.katalog.verifikasi-teknis', compact('katalog', 'existingData', 'personilList', 'alatList', 'barangList'));
    }

        public function verifikasiTeknisStore(Request $request, $id)
    {
        $katalog = CrmKatalog::with('sertifikats')->findOrFail($id);
        $sertifikatMap = $katalog->sertifikats->keyBy('parameter_uji_id');
        $isDraft = $request->input('action') === 'draft';

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $adaData = false;
            $semuaInlier = true;

            foreach ($request->input('params', []) as $paramId => $paramData) {
                if (!isset($sertifikatMap[$paramId])) continue;
                $inputRows = $paramData['data'] ?? [];
                if (empty($inputRows)) continue;

                $sertifikat = $sertifikatMap[$paramId];
                $meanPerPengujian = [];
                $mentahSemua = [];
                $terakhirD1 = null;
                $terakhirD2 = null;

                $totalRows = count($inputRows);

                foreach ($inputRows as $index => $row) {
                    $val1 = $row['nilai_d1'] ?? null;
                    $val2 = $row['nilai_d2'] ?? null;
                    
                    $isHistorical = $index < ($totalRows - 1);

                    if (!$isDraft && !$isHistorical && ($val1 === null || $val1 === '' || $val2 === null || $val2 === '')) {
                        continue;
                    }

                    if ($val1 !== null && $val1 !== '' && $val2 !== null && $val2 !== '') {
                        $meanPerPengujian[] = (floatval($val1) + floatval($val2)) / 2;
                    }

                    $mentahSemua[] = $row;
                    $terakhirD1 = $val1;
                    $terakhirD2 = $val2;
                }

                if (empty($meanPerPengujian) && !$isDraft) continue;
                $adaData = true;
                $nilai_akhir = null;
                $batas_bawah = $sertifikat->cert_value - $sertifikat->cert_u;
                $batas_atas = $sertifikat->cert_value + $sertifikat->cert_u;
                $status = null;

                if (!empty($meanPerPengujian)) {
                    $nilai_akhir = end($meanPerPengujian); 
                    
                    $status = ($nilai_akhir >= $batas_bawah && $nilai_akhir <= $batas_atas) ? 'inlier' : 'outlier';
                    if ($status === 'outlier') $semuaInlier = false;
                }

                // Ambil data verifikasi sebelum diupdate untuk mengecek status dan qty lama
                $existingVerif = \App\Models\CrmVerifikasiTeknis::where('crm_katalog_id', $katalog->id)
                                    ->where('parameter_uji_id', $paramId)->first();
                $wasFinal = $existingVerif && $existingVerif->status_evaluasi !== 'draft';
                $oldBahans = [];
                if ($wasFinal) {
                    // Jika sebelumnya final, berarti stok sudah terpotong
                    $oldBahans = $existingVerif->bahans()->pluck('crm_verif_teknis_bahan.qty', 'crm_verif_teknis_bahan.barang_id')->toArray();
                }

                                $verif = \App\Models\CrmVerifikasiTeknis::updateOrCreate(
                    ['crm_katalog_id' => $katalog->id, 'parameter_uji_id' => $paramId],
                    [
                        'analis_id' => $request->input('analis_id'), 
                        'nilai_d1' => $terakhirD1,
                        'nilai_d2' => $terakhirD2,
                        'nilai_akhir' => $nilai_akhir,
                        'cert_value' => $sertifikat->cert_value,
                        'cert_u' => $sertifikat->cert_u,
                        'batas_bawah' => $batas_bawah,
                        'batas_atas' => $batas_atas,
                        'data_mentah' => $mentahSemua,
                        'tanggal_uji' => $paramData['tanggal_uji'] ?? now(),
                        'catatan' => $paramData['catatan'] ?? null,
                        'status_evaluasi' => $isDraft ? 'draft' : $status,
                        'status_investigasi' => (!$isDraft && $status === 'outlier') ? 'menunggu_investigasi' : 'aman',
                    ]
                );
                
                if (!empty($paramData['personil_ids'])) {
                    $syncPersonil = [];
                    foreach ($paramData['personil_ids'] as $pidPersonil) {
                        $syncPersonil[$pidPersonil] = ['peran' => $paramData['personil_peran'][$pidPersonil] ?? null];
                    }
                    $verif->personils()->sync($syncPersonil);
                } else {
                    $verif->personils()->detach();
                }

                if (!empty($paramData['alat_ids'])) {
                    $verif->alats()->sync($paramData['alat_ids']);
                } else {
                    $verif->alats()->detach();
                }

                if (!empty($paramData['barang_ids'])) {
                    $syncBahan = [];
                    foreach ($paramData['barang_ids'] as $bidBarang) {
                        $syncBahan[$bidBarang] = ['qty' => $paramData['barang_jumlah'][$bidBarang] ?? 1];
                    }
                    $verif->bahans()->sync($syncBahan);
                } else {
                    $verif->bahans()->detach();
                }

                // AUTO DEDUCT LOGIC
                $newDeducted = [];
                if (!$isDraft && !empty($paramData['barang_ids'])) {
                    foreach ($paramData['barang_ids'] as $bidBarang) {
                        $newDeducted[$bidBarang] = $paramData['barang_jumlah'][$bidBarang] ?? 1;
                    }
                }

                // 1. Proses penambahan potongan atau pengembalian sebagian
                foreach ($newDeducted as $bid => $newQty) {
                    $oldQty = $oldBahans[$bid] ?? 0;
                    $diff = $newQty - $oldQty;
                    if ($diff != 0) {
                        $barang = \App\Models\Barang::find($bid);
                        if ($barang) {
                            $barang->pengeluaran += $diff;
                            $barang->save();
                        }
                    }
                }

                // 2. Proses pengembalian utuh untuk bahan yang dihapus (atau jika kembali jadi draft)
                foreach ($oldBahans as $oldBid => $oldQty) {
                    if (!isset($newDeducted[$oldBid])) {
                        $barang = \App\Models\Barang::find($oldBid);
                        if ($barang) {
                            $barang->pengeluaran -= $oldQty;
                            $barang->save();
                        }
                    }
                }
            } 

            if ($isDraft) {
                \Illuminate\Support\Facades\DB::commit();
                return redirect()->route('qc-crm.index')->with('success', 'Draft berhasil disimpan.');
            }

            if (!$adaData) {
                throw new \Exception('Belum ada parameter yang diisi lengkap.');
            }

            $jumlahParam = $katalog->sertifikats->count();
            $jumlahTerisi = \App\Models\CrmVerifikasiTeknis::where('crm_katalog_id', $katalog->id)->count();

            if ($jumlahTerisi >= $jumlahParam && $semuaInlier) {
                $katalog->update(['status' => 'aktif', 'is_active' => true]);
                \Illuminate\Support\Facades\DB::commit();
                return redirect()->route('qc-crm.index')->with('success', 'Semua parameter INLIER. Botol CRM kini AKTIF dan bisa digunakan untuk pengujian harian.');
            }

            if ($jumlahTerisi >= $jumlahParam && !$semuaInlier) {
                \Illuminate\Support\Facades\DB::commit();
                return redirect()->route('qc-crm.index')
                    ->with('error', 'Verifikasi teknis selesai, namun ada parameter OUTLIER. Silakan lakukan uji ulang.');
            }

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('crm-katalog.verifikasi-teknis.form', $id)->with('success', 'Sebagian data tersimpan. Lengkapi parameter lain untuk menuntaskan verifikasi.');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

        public function investigasiVerifTeknis($katalogId, $verifId)
    {
        $katalog = CrmKatalog::findOrFail($katalogId);
        $verif = \App\Models\CrmVerifikasiTeknis::with(['parameterUji'])->findOrFail($verifId);

        if ($verif->status_evaluasi !== 'outlier') {
            return redirect()->route('qc-crm.index')->with('error', 'Hanya data outlier yang memerlukan investigasi.');
        }

        return view('qc-crm.katalog.investigasi', compact('katalog', 'verif'));
    }

    public function storeInvestigasiVerifTeknis(Request $request, $katalogId, $verifId)
    {
        $request->validate([
            'akar_masalah' => 'required|string',
            'catatan_investigasi' => 'required|string|min:10',
            'tindakan_perbaikan' => 'required|string|min:10',
        ]);

        $verif = \App\Models\CrmVerifikasiTeknis::findOrFail($verifId);
        $verif->update([
            'akar_masalah' => $request->akar_masalah,
            'catatan_investigasi' => $request->catatan_investigasi,
            'tindakan_perbaikan' => $request->tindakan_perbaikan,
            'status_investigasi' => 'selesai_investigasi',
        ]);

        return redirect()->route('qc-crm.index')
            ->with('success', 'Laporan CAPA berhasil disimpan. Silakan lakukan Uji Ulang (Retest) untuk botol tersebut.');
    }

}
