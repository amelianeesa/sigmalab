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
        $katalogList = CrmKatalog::withCount('sertifikats')->orderBy('created_at', 'desc')->get();
        return view('qc-crm.katalog.index', compact('katalogList'));
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

        return redirect()->route('crm-katalog.index')->with('success', 'Botol CRM berhasil ditambahkan dan menunggu verifikasi administratif.');
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

        return redirect()->route('crm-katalog.index')->with('success', 'Botol CRM berhasil diperbarui.');
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
            'verifikasi_administratif_oleh' => auth()->id(),
            'verifikasi_administratif_at' => now(),
            'status' => $request->keputusan === 'lolos' ? 'menunggu_verifikasi_teknis' : 'ditolak',
        ]);

            if ($request->keputusan === 'lolos') {
                return redirect()->route('crm-katalog.verifikasi-teknis.form', $id)
                    ->with('success', 'Verifikasi administratif lolos. Silakan lanjutkan ke verifikasi teknis.');
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

            $existingData = \App\Models\CrmVerifikasiTeknis::where('crm_katalog_id', $id)
                ->get()
                ->keyBy('parameter_uji_id');

            return view('qc-crm.katalog.verifikasi-teknis', compact('katalog', 'existingData'));
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

                    foreach ($inputRows as $row) {
                        $val1 = $row['nilai_d1'] ?? null;
                        $val2 = $row['nilai_d2'] ?? null;
                        if ($val1 === null || $val1 === '' || $val2 === null || $val2 === '') continue;

                        $meanPerPengujian[] = (floatval($val1) + floatval($val2)) / 2;
                        $mentahSemua[] = $row['mentah'] ?? null;
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
                        $nilai_akhir = array_sum($meanPerPengujian) / count($meanPerPengujian);
                        $status = ($nilai_akhir >= $batas_bawah && $nilai_akhir <= $batas_atas) ? 'inlier' : 'outlier';
                        if ($status === 'outlier') $semuaInlier = false;
                    }

                    \App\Models\CrmVerifikasiTeknis::updateOrCreate(
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
                            'status_evaluasi' => $status,
                            'data_mentah' => $mentahSemua,
                            'tanggal_uji' => now(),
                        ]
                    );
                }

                            // Mode draft: cukup simpan apa adanya, tanpa cek kelengkapan atau ubah status botol
            if ($isDraft) {
                \Illuminate\Support\Facades\DB::commit();
                return redirect()->route('crm-katalog.verifikasi-teknis.form', $id)->with('success', 'Draft berhasil disimpan.');
            }

            if (!$adaData) {
                throw new \Exception('Belum ada parameter yang diisi lengkap.');
            }

            $jumlahParam = $katalog->sertifikats->count();
            $jumlahTerisi = \App\Models\CrmVerifikasiTeknis::where('crm_katalog_id', $katalog->id)->count();

            if ($jumlahTerisi >= $jumlahParam && $semuaInlier) {
                $katalog->update(['status' => 'aktif', 'is_active' => true]);
                \Illuminate\Support\Facades\DB::commit();
                return redirect()->route('crm-katalog.show', $id)->with('success', 'Semua parameter INLIER. Botol CRM kini AKTIF dan bisa digunakan untuk pengujian harian.');
            }

            if ($jumlahTerisi >= $jumlahParam && !$semuaInlier) {
                \Illuminate\Support\Facades\DB::commit();
                return redirect()->route('crm-katalog.show', $id)->with('error', 'Verifikasi teknis selesai, namun ada parameter OUTLIER. Botol belum diaktifkan.');
            }

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('crm-katalog.verifikasi-teknis.form', $id)->with('success', 'Sebagian data tersimpan. Lengkapi parameter lain untuk menuntaskan verifikasi.');

            } catch (\Exception $e) {
                \Illuminate\Support\Facades\DB::rollBack();
                return redirect()->back()->withInput()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
            }
        }

}
