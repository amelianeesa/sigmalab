<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Personil; // Sesuaikan dengan model Anda
use App\Models\KompetensiPersonil;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SdmController extends Controller
{
    public function index()
    {
        $showInactive = request('status') === 'nonaktif';
        $personil = Personil::with(['kompetensi' => fn ($query) => $query->orderByDesc('tanggal_terbit')])
            ->where('status_aktif', ! $showInactive)
            ->get();

        $jumlahPersonilAktif = Personil::where('status_aktif', true)->count();
        $jumlahPersonilNonaktif = Personil::where('status_aktif', false)->count();

        $personil->each(function (Personil $item) {
            $sertifikasi = $item->kompetensi->first();
            $item->sertifikasiTerakhir = $sertifikasi;

            if (! $sertifikasi) {
                $item->statusSertifikasi = ['label' => 'Belum bersertifikat', 'class' => 'status-empty', 'icon' => 'dash-circle'];
            } elseif (! $sertifikasi->tanggal_berakhir) {
                $item->statusSertifikasi = ['label' => 'Aktif', 'class' => 'status-active', 'icon' => 'check-circle'];
            } elseif ($sertifikasi->tanggal_berakhir->isPast()) {
                $item->statusSertifikasi = ['label' => 'Kedaluwarsa', 'class' => 'status-expired', 'icon' => 'x-circle'];
            } elseif ($sertifikasi->tanggal_berakhir->lessThanOrEqualTo(today()->addDays(60))) {
                $item->statusSertifikasi = ['label' => 'Segera berakhir', 'class' => 'status-warning', 'icon' => 'exclamation-circle'];
            } else {
                $item->statusSertifikasi = ['label' => 'Aktif', 'class' => 'status-active', 'icon' => 'check-circle'];
            }
        });
        $selectedPersonil = $personil->firstWhere('personil_id', request('personil_id'));

        return view('sdm.index', compact(
            'personil',
            'selectedPersonil',
            'showInactive',
            'jumlahPersonilAktif',
            'jumlahPersonilNonaktif'
        ));
    }

    public function create()
    {
        return view('sdm.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_induk' => 'required|unique:personil,no_induk',
            'nama' => 'required|string|max:100',
            'jabatan' => 'required|string|max:100',
            'unit_kerja' => 'required|string|max:100',
            'file_cv' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
            'nama_sertifikasi' => 'nullable|string|max:100',
            'no_sertifikasi' => 'nullable|string|max:100',
            'tanggal_terbit' => 'nullable|date',
            'tanggal_berakhir' => 'nullable|date|after_or_equal:tanggal_terbit',
        ]);

        $cvName = null;
        if ($request->hasFile('file_cv')) {
            $cvName = time() . '_' . $request->file_cv->getClientOriginalName();
            Storage::disk('local')->putFileAs('public/uploads/cv', $request->file('file_cv'), $cvName);
        }

        DB::transaction(function () use ($request, $cvName) {
            $personil = Personil::create([
                'no_induk' => $request->no_induk,
                'nama' => $request->nama,
                'jabatan' => $request->jabatan,
                'unit_kerja' => $request->unit_kerja,
                'file_cv' => $cvName,
                'status_aktif' => true,
            ]);

            if ($request->filled('nama_sertifikasi')) {
                $tanggalTerbit = Carbon::parse($request->input('tanggal_terbit') ?: today()->toDateString());

                KompetensiPersonil::create([
                    'personil_id' => $personil->personil_id,
                    'jenis_sertifikasi' => $request->nama_sertifikasi,
                    'no_sertifikasi' => $request->no_sertifikasi,
                    'tanggal_terbit' => $tanggalTerbit->toDateString(),
                    'tanggal_berakhir' => $request->tanggal_berakhir,
                ]);
            }
        });

        return redirect()->route('sdm.index')->with('success', 'Data personil berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $personil = Personil::with(['kompetensi' => fn ($query) => $query->orderByDesc('tanggal_terbit')])->findOrFail($id);
        $sertifikasi = $personil->kompetensi->first();

        return view('sdm.edit', compact('personil', 'sertifikasi'));
    }

    public function update(Request $request, $id)
    {
        $personil = Personil::findOrFail($id);

        $request->validate([
            'no_induk' => 'required|unique:personil,no_induk,' . $id . ',personil_id',
            'nama' => 'required|string|max:100',
            'jabatan' => 'required|string|max:100',
            'unit_kerja' => 'required|string|max:100',
            'file_cv' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
            'nama_sertifikasi' => 'nullable|string|max:100',
            'no_sertifikasi' => 'nullable|string|max:100',
            'tanggal_terbit' => 'nullable|date',
            'tanggal_berakhir' => 'nullable|date|after_or_equal:tanggal_terbit',
        ]);

        if ($request->hasFile('file_cv')) {
            if ($personil->file_cv && Storage::disk('local')->exists('public/uploads/cv/' . $personil->file_cv)) {
                Storage::disk('local')->delete('public/uploads/cv/' . $personil->file_cv);
            }
            $cvName = time() . '_' . $request->file_cv->getClientOriginalName();
            Storage::disk('local')->putFileAs('public/uploads/cv', $request->file('file_cv'), $cvName);
            $personil->file_cv = $cvName;
        }

        DB::transaction(function () use ($personil, $request) {
            $personil->update([
                'no_induk' => $request->no_induk,
                'nama' => $request->nama,
                'jabatan' => $request->jabatan,
                'unit_kerja' => $request->unit_kerja,
            ]);

            if ($request->filled('nama_sertifikasi')) {
                $dataSertifikasi = [
                    'jenis_sertifikasi' => $request->nama_sertifikasi,
                    'no_sertifikasi' => $request->no_sertifikasi,
                    'tanggal_terbit' => $request->tanggal_terbit,
                    'tanggal_berakhir' => $request->tanggal_berakhir,
                ];

                $sertifikasi = $personil->kompetensi()->orderByDesc('tanggal_terbit')->first();
                $sertifikasi
                    ? $sertifikasi->update($dataSertifikasi)
                    : $personil->kompetensi()->create($dataSertifikasi);
            }
        });

        return redirect()->route('sdm.index')->with('success', 'Data personil berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $personil = Personil::findOrFail($id);
        // Soft delete sesuai rancangan database
        $personil->update(['status_aktif' => false]);

        return redirect()->route('sdm.index')->with('success', 'Data personil dinonaktifkan (Soft Delete).');
    }

    public function activate($id)
    {
        $personil = Personil::findOrFail($id);
        $personil->update(['status_aktif' => true]);

        return redirect()->route('sdm.index')->with('success', 'Personil berhasil diaktifkan kembali.');
    }

    public function forceDestroy($id)
    {
        $personil = Personil::where('status_aktif', false)->findOrFail($id);

        if (DB::table('kegiatan_personil')->where('personil_id', $personil->personil_id)->exists()) {
            return redirect()->route('sdm.index', ['status' => 'nonaktif'])
                ->with('error', 'Personil tidak dapat dihapus permanen karena masih tercatat dalam kegiatan laboratorium.');
        }

        $fileCv = $personil->file_cv;
        $personil->delete();

        if ($fileCv && Storage::disk('local')->exists('public/uploads/cv/' . $fileCv)) {
            Storage::disk('local')->delete('public/uploads/cv/' . $fileCv);
        }

        return redirect()->route('sdm.index', ['status' => 'nonaktif'])
            ->with('success', 'Data personil beserta CV dan sertifikasinya telah dihapus permanen.');
    }

    public function kompetensiDetail($id)
    {
        $personil = Personil::with(['kompetensi' => fn ($query) => $query->orderByDesc('tanggal_terbit')])->findOrFail($id);
        return view('sdm.kompetensi_detail', compact('personil'));
    }

    public function storeKompetensi(Request $request, $id)
    {
        $personil = Personil::findOrFail($id);
        $data = $request->validate([
            'jenis_sertifikasi' => 'required|string|max:100',
            'no_sertifikasi' => 'nullable|string|max:100',
            'tanggal_terbit' => 'nullable|date',
            'tanggal_berakhir' => 'nullable|date|after_or_equal:tanggal_terbit',
        ]);

        $personil->kompetensi()->create($data);

        return redirect()->route('sdm.kompetensi.detail', $personil->personil_id)
            ->with('success', 'Sertifikat berhasil ditambahkan.');
    }

    public function updateKompetensi(Request $request, $id, $kompetensiId)
    {
        $personil = Personil::findOrFail($id);
        $kompetensi = $personil->kompetensi()->findOrFail($kompetensiId);
        $data = $request->validate([
            'jenis_sertifikasi' => 'required|string|max:100',
            'no_sertifikasi' => 'nullable|string|max:100',
            'tanggal_terbit' => 'nullable|date',
            'tanggal_berakhir' => 'nullable|date|after_or_equal:tanggal_terbit',
        ]);

        $kompetensi->update($data);

        return redirect()->route('sdm.kompetensi.detail', $personil->personil_id)
            ->with('success', 'Sertifikat berhasil diperbarui.');
    }

    public function destroyKompetensi($id, $kompetensiId)
    {
        $personil = Personil::findOrFail($id);
        $personil->kompetensi()->findOrFail($kompetensiId)->delete();

        return redirect()->route('sdm.kompetensi.detail', $personil->personil_id)
            ->with('success', 'Sertifikat berhasil dihapus.');
    }

    public function showCv($id)
    {
    $personil = Personil::findOrFail($id);
        $path = 'public/uploads/cv/' . $personil->file_cv;

        abort_unless($personil->file_cv && Storage::disk('local')->exists($path), 404, 'File CV tidak ditemukan.');

        return Storage::disk('local')->response($path, $personil->file_cv, [
            'Content-Disposition' => 'inline; filename="' . $personil->file_cv . '"',
        ]);
    }
}
