<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Alat;
use App\Models\Personil;
use App\Models\ParameterUji;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KegiatanController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', Kegiatan::class);

        $filterJenis = $request->input('filter_jenis');
        $filterStatus = $request->input('filter_status');
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
        $nextKodeSampel = $this->generateKodeSampel();

        return view('kegiatan.create', compact('alatList', 'personilList', 'nextKodeSampel'));
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
        ]);

        DB::transaction(function () use ($request, $validated) {
            $kegiatan = Kegiatan::create([
                'nama_kegiatan' => $validated['nama_kegiatan'],
                'jenis_kegiatan' => $validated['jenis_kegiatan'],
                'kode_sampel' => $this->generateKodeSampel(), // Selalu generate saat store untuk mencegah duplikasi

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
        });

        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil dibuat.');
    }

    public function show($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        $this->authorize('view', $kegiatan);

        $kegiatan->load(['pembuatKegiatan', 'alatDigunakan', 'personilTerlibat', 'hasilUji.parameterUji', 'hasilUji.tindakLanjut']);
        $parameterList = ParameterUji::where('status_aktif', true)->get();

        return view('kegiatan.show', compact('kegiatan', 'parameterList'));
    }

    public function edit($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        $this->authorize('update', $kegiatan);

        $alatList = Alat::where('kondisi_barang', 'baik')->get();
        $personilList = Personil::where('status_aktif', true)->get();
        $selectedAlat = $kegiatan->alatDigunakan->pluck('alat_id')->toArray();
        $selectedPersonil = $kegiatan->personilTerlibat->pluck('personil_id')->toArray();
        $personilPeran = $kegiatan->personilTerlibat->pluck('pivot.peran', 'personil_id')->toArray();

        return view('kegiatan.edit', compact('kegiatan', 'alatList', 'personilList', 'selectedAlat', 'selectedPersonil', 'personilPeran'));
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
        ]);

        DB::transaction(function () use ($request, $validated, $kegiatan) {
            $kegiatan->update([
                'nama_kegiatan' => $validated['nama_kegiatan'],
                'jenis_kegiatan' => $validated['jenis_kegiatan'],
                'kode_sampel' => $validated['kode_sampel'],
                'tanggal_kegiatan' => $validated['tanggal_kegiatan'],
                'status_kegiatan' => $validated['status_kegiatan'],
            ]);

            // Sync alat
            $kegiatan->alatDigunakan()->sync($validated['alat_ids'] ?? []);

            // Sync personil with peran
            $syncData = [];
            if (!empty($validated['personil_ids'])) {
                foreach ($validated['personil_ids'] as $personilId) {
                    $peran = $request->input("personil_peran.{$personilId}", 'Analis');
                    $syncData[$personilId] = ['peran' => $peran];
                }
            }
            $kegiatan->personilTerlibat()->sync($syncData);
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
            
            // Hapus semua tindak lanjut yang terkait dengan hasil uji kegiatan ini
            foreach ($kegiatan->hasilUji as $hasil) {
                $hasil->tindakLanjut()->delete();
            }
            // Hapus hasil uji
            $kegiatan->hasilUji()->delete();

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
