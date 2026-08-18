<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Personil;
use App\Models\KompetensiPersonil;
use App\Models\KategoriPersonil;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SdmController extends Controller
{
    public function index()
    {
        $showInactive = request('status') === 'nonaktif';
        $kategori = request('kategori');

        $personil = Personil::with([
            'kompetensi' => fn($query) => $query->orderByDesc('tanggal_terbit'),
            'user',
        ])
            ->where('status_aktif', !$showInactive)
            ->when($kategori, fn($query) => $query->where('kategori_personil', $kategori))
            ->get();

        $jumlahPersonilAktif = Personil::where('status_aktif', true)->count();
        $jumlahPersonilNonaktif = Personil::where('status_aktif', false)->count();

        $jumlahSertifikasiSegeraHabis = Personil::where('status_aktif', true)
            ->whereHas('kompetensi', function ($query) {
                $query->whereNotNull('tanggal_berakhir')
                    ->where('tanggal_berakhir', '<=', Carbon::now()->addMonths(6));
            })->count();

        $personil->each(function (Personil $item) {
            $sertifikasi = $item->kompetensi->first();
            $item->sertifikasiTerakhir = $sertifikasi;
            $item->statusSertifikasi = $sertifikasi
                ? $this->resolveStatusSertifikasi($sertifikasi->tanggal_berakhir)
                : ['label' => 'Belum bersertifikat', 'class' => 'bg-light text-dark border', 'icon' => 'dash-circle'];
        });

        $selectedPersonil = $personil->firstWhere('personil_id', request('personil_id'));
        $kategoriOptions = KategoriPersonil::options();
        $roles = Role::all();

        return view('sdm.index', compact(
            'personil',
            'selectedPersonil',
            'showInactive',
            'jumlahPersonilAktif',
            'jumlahPersonilNonaktif',
            'jumlahSertifikasiSegeraHabis',
            'kategori',
            'kategoriOptions',
            'roles'
        ));
    }

    private function resolveStatusSertifikasi($tanggalBerakhir): array
    {
        $tanggalBerakhir = $tanggalBerakhir ? Carbon::parse($tanggalBerakhir) : null;

        if (!$tanggalBerakhir) {
            return ['label' => 'Aktif', 'class' => 'bg-success text-white', 'icon' => 'check-circle'];
        }

        if ($tanggalBerakhir->isPast()) {
            return ['label' => 'Kedaluwarsa', 'class' => 'bg-danger text-white', 'icon' => 'x-circle'];
        }

        if ($tanggalBerakhir->lessThanOrEqualTo(today()->addMonths(6))) {
            return ['label' => 'Segera Berakhir', 'class' => 'bg-warning text-dark', 'icon' => 'exclamation-circle'];
        }

        return ['label' => 'Aktif', 'class' => 'bg-success text-white', 'icon' => 'check-circle'];
    }

    public function create()
    {
        $kategoriOptions = KategoriPersonil::options();

        return view('sdm.create', compact('kategoriOptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_induk' => 'required|unique:personil,no_induk',
            'nama' => 'required|string|max:100',
            'jabatan' => 'required|string|max:100',
            'kategori_personil' => 'nullable|exists:kategori_personil,kode',
            'unit_kerja' => 'required|string|max:100',
            'file_cv' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
            'nama_sertifikasi' => 'nullable|string|max:100',
            'no_sertifikasi' => 'nullable|string|max:100',
            'tanggal_terbit' => 'nullable|date',
            'tanggal_berakhir' => 'nullable|date|after_or_equal:tanggal_terbit',
        ]);

        $cvName = null;
        if ($request->hasFile('file_cv')) {
            $cvName = $request->file('file_cv')->hashName();
            Storage::disk('local')->putFileAs('public/uploads/cv', $request->file('file_cv'), $cvName);
        }

        DB::transaction(function () use ($request, $cvName) {
            $personil = Personil::create([
                'no_induk' => $request->no_induk,
                'nama' => $request->nama,
                'jabatan' => $request->jabatan,
                'kategori_personil' => $request->kategori_personil,
                'unit_kerja' => $request->unit_kerja,
                'file_cv' => $cvName,
                'status_aktif' => true,
            ]);

            if ($request->filled('nama_sertifikasi') && Auth::user()->role->nama_role !== 'Admin Lab') {
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
        $personil = Personil::with(['kompetensi' => fn($query) => $query->orderByDesc('tanggal_terbit')])->findOrFail($id);
        $sertifikasi = $personil->kompetensi->first();
        $kategoriOptions = KategoriPersonil::options();

        return view('sdm.edit', compact('personil', 'sertifikasi', 'kategoriOptions'));
    }

    public function update(Request $request, $id)
    {
        $personil = Personil::findOrFail($id);

        $request->validate([
            'no_induk' => 'required|unique:personil,no_induk,' . $id . ',personil_id',
            'nama' => 'required|string|max:100',
            'jabatan' => 'required|string|max:100',
            'kategori_personil' => 'nullable|exists:kategori_personil,kode',
            'unit_kerja' => 'required|string|max:100',
            'file_cv' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
            'nama_sertifikasi' => 'nullable|string|max:100',
            'no_sertifikasi' => 'nullable|string|max:100',
            'tanggal_terbit' => 'nullable|date',
            'tanggal_berakhir' => 'nullable|date|after_or_equal:tanggal_terbit',
        ]);

        $userRole = Auth::user()->role->nama_role;

        if ($request->hasFile('file_cv')) {
            abort_if($userRole === 'Admin Lab', 403, 'Admin Lab tidak diizinkan mengunggah/mengubah file CV.');
            if ($personil->file_cv && Storage::disk('local')->exists('public/uploads/cv/' . $personil->file_cv)) {
                Storage::disk('local')->delete('public/uploads/cv/' . $personil->file_cv);
            }
            $cvName = $request->file('file_cv')->hashName();
            Storage::disk('local')->putFileAs('public/uploads/cv', $request->file('file_cv'), $cvName);
            $personil->file_cv = $cvName;
        }

        DB::transaction(function () use ($personil, $request, $userRole) {
            $personil->update([
                'no_induk' => $request->no_induk,
                'nama' => $request->nama,
                'jabatan' => $request->jabatan,
                'kategori_personil' => $request->kategori_personil,
                'unit_kerja' => $request->unit_kerja,
            ]);

            if ($request->filled('nama_sertifikasi') && $userRole !== 'Admin Lab') {
                $dataSertifikasi = [
                    'jenis_sertifikasi' => $request->nama_sertifikasi,
                    'no_sertifikasi' => $request->no_sertifikasi,
                    'tanggal_terbit' => $request->tanggal_terbit,
                    'tanggal_berakhir' => $request->tanggal_berakhir,
                ];

                $sertifikasi = $personil->kompetensi()->orderByDesc('tanggal_terbit')->first();

                if ($sertifikasi) {
                    if ((string) $sertifikasi->tanggal_berakhir !== (string) $request->tanggal_berakhir) {
                        $dataSertifikasi['reminder_terakhir_dikirim'] = null;
                    }
                    $sertifikasi->update($dataSertifikasi);
                } else {
                    $personil->kompetensi()->create($dataSertifikasi);
                }
            }
        });

        return redirect()->route('sdm.index')->with('success', 'Data personil berhasil diperbarui.');
    }

    public function storeKategori(Request $request)
    {
        $data = $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori_personil,nama_kategori',
            'redirect_to' => 'nullable|string',
        ]);

        $kode = Str::slug($data['nama_kategori'], '_');

        $kategori = KategoriPersonil::create([
            'kode' => $kode,
            'nama_kategori' => $data['nama_kategori'],
        ]);

        $redirectTo = $request->input('redirect_to') ?: route('sdm.index');

        return redirect($redirectTo)
            ->with('success', 'Kategori "' . $kategori->nama_kategori . '" berhasil ditambahkan.')
            ->with('kategori_baru', $kategori->kode);
    }

    public function destroyKategori(Request $request, $kode)
    {
        $kategori = KategoriPersonil::where('kode', $kode)->firstOrFail();

        $dipakai = Personil::where('kategori_personil', $kode)->exists();

        if ($dipakai) {
            return redirect()->back()->with('error', 'Kategori "' . $kategori->nama_kategori . '" masih dipakai oleh personil, tidak bisa dihapus. Ubah dulu kategori personil yang memakainya.');
        }

        $namaKategori = $kategori->nama_kategori;
        $kategori->delete();

        $redirectTo = $request->input('redirect_to') ?: route('sdm.index');

        return redirect($redirectTo)->with('success', 'Kategori "' . $namaKategori . '" berhasil dihapus.');
    }

    public function destroy($id)
    {
        abort_if(Auth::user()->role->nama_role === 'Admin Lab', 403, 'Admin Lab tidak diizinkan menghapus data personil.');

        $personil = Personil::findOrFail($id);

        DB::transaction(function () use ($personil) {
            $personil->update(['status_aktif' => false]);
            User::where('personil_id', $personil->personil_id)->update(['status_aktif' => false]);
        });

        return redirect()->route('sdm.index')->with('success', 'Data personil dinonaktifkan (Soft Delete).');
    }

    public function activate($id)
    {
        $personil = Personil::findOrFail($id);

        DB::transaction(function () use ($personil) {
            $personil->update(['status_aktif' => true]);
            User::where('personil_id', $personil->personil_id)->update(['status_aktif' => true]);
        });

        return redirect()->route('sdm.index')->with('success', 'Personil berhasil diaktifkan kembali.');
    }

    public function storeAkun(Request $request, $id)
    {
        abort_if(Auth::user()->role->nama_role === 'Admin Lab', 403, 'Admin Lab tidak diizinkan membuat akun pengguna.');

        $personil = Personil::findOrFail($id);

        abort_if($personil->user, 409, 'Personil ini sudah memiliki akun login.');

        $data = $request->validate([
            'username' => 'required|string|max:50|unique:users,username',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:roles,roles_id',
        ]);

        User::create([
            'personil_id' => $personil->personil_id,
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $data['role_id'],
            'status_aktif' => true,
        ]);

        return redirect()->route('sdm.index')->with('success', 'Akun login untuk ' . $personil->nama . ' berhasil dibuat.');
    }

    public function forceDestroy($id)
    {
        $personil = Personil::where('status_aktif', false)->findOrFail($id);
        $fileCv = $personil->file_cv;

        DB::transaction(function () use ($personil) {
            DB::table('kegiatan_personil')->where('personil_id', $personil->personil_id)->delete();

            $personil->delete();
        });

        if ($fileCv && Storage::disk('local')->exists('public/uploads/cv/' . $fileCv)) {
            Storage::disk('local')->delete('public/uploads/cv/' . $fileCv);
        }

        return redirect()->route('sdm.index', ['status' => 'nonaktif'])
            ->with('success', 'Data personil beserta CV dan sertifikasinya telah dihapus permanen.');
    }

    public function kompetensiDetail($id)
    {
        $personil = Personil::with(['kompetensi' => fn($query) => $query->orderByDesc('tanggal_terbit')])->findOrFail($id);

        $personil->kompetensi->each(function ($komp) {
            $komp->status = $this->resolveStatusSertifikasi($komp->tanggal_berakhir);
        });

        return view('sdm.kompetensi_detail', compact('personil'));
    }

    public function storeKompetensi(Request $request, $id)
    {
        abort_if(Auth::user()->role->nama_role === 'Admin Lab', 403, 'Admin Lab tidak diizinkan menambah data sertifikasi.');

        $personil = Personil::findOrFail($id);
        $data = $request->validate([
            'jenis_sertifikasi' => 'required|string|max:100',
            'no_sertifikasi' => 'nullable|string|max:100',
            'tanggal_terbit' => 'nullable|date',
            'tanggal_berakhir' => 'nullable|date|after_or_equal:tanggal_terbit',
            'file_sertifikat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('file_sertifikat')) {
            $fileName = $request->file('file_sertifikat')->hashName();
            Storage::disk('local')->putFileAs('public/uploads/sertifikat', $request->file('file_sertifikat'), $fileName);
            $data['file_sertifikat'] = $fileName;
        }

        $personil->kompetensi()->create($data);

        return redirect()->route('sdm.kompetensi.detail', $personil->personil_id)
            ->with('success', 'Sertifikat berhasil ditambahkan.');
    }

    public function updateKompetensi(Request $request, $id, $kompetensiId)
    {
        abort_if(Auth::user()->role->nama_role === 'Admin Lab', 403, 'Admin Lab tidak diizinkan mengubah data sertifikasi.');

        $personil = Personil::findOrFail($id);
        $kompetensi = $personil->kompetensi()->findOrFail($kompetensiId);
        $data = $request->validate([
            'jenis_sertifikasi' => 'required|string|max:100',
            'no_sertifikasi' => 'nullable|string|max:100',
            'tanggal_terbit' => 'nullable|date',
            'tanggal_berakhir' => 'nullable|date|after_or_equal:tanggal_terbit',
            'file_sertifikat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ((string) $kompetensi->tanggal_berakhir !== (string) $request->tanggal_berakhir) {
            $data['reminder_terakhir_dikirim'] = null;
        }

        if ($request->hasFile('file_sertifikat')) {
            if ($kompetensi->file_sertifikat && Storage::disk('local')->exists('public/uploads/sertifikat/' . $kompetensi->file_sertifikat)) {
                Storage::disk('local')->delete('public/uploads/sertifikat/' . $kompetensi->file_sertifikat);
            }

            $fileName = $request->file('file_sertifikat')->hashName();
            Storage::disk('local')->putFileAs('public/uploads/sertifikat', $request->file('file_sertifikat'), $fileName);
            $data['file_sertifikat'] = $fileName;
        }

        $kompetensi->update($data);

        return redirect()->route('sdm.kompetensi.detail', $personil->personil_id)
            ->with('success', 'Sertifikat berhasil diperbarui.');
    }

    public function destroyKompetensi($id, $kompetensiId)
    {
        abort_if(Auth::user()->role->nama_role === 'Admin Lab', 403, 'Admin Lab tidak diizinkan menghapus data sertifikasi.');

        $personil = Personil::findOrFail($id);
        $personil->kompetensi()->findOrFail($kompetensiId)->delete();

        return redirect()->route('sdm.kompetensi.detail', $personil->personil_id)
            ->with('success', 'Sertifikat berhasil dihapus.');
    }

    public function showKompetensiFile($id, $kompetensiId)
    {
        $personil = Personil::findOrFail($id);
        $kompetensi = $personil->kompetensi()->findOrFail($kompetensiId);
        $path = 'public/uploads/sertifikat/' . $kompetensi->file_sertifikat;

        abort_unless($kompetensi->file_sertifikat && Storage::disk('local')->exists($path), 404, 'File sertifikat tidak ditemukan.');

        $fullPath = Storage::disk('local')->path($path);

        return response()->file($fullPath, [
            'Content-Disposition' => 'inline; filename="' . $kompetensi->file_sertifikat . '"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
        ]);
    }

    public function uploadKompetensiFile(Request $request, $id, $kompetensiId)
    {
        abort_if(Auth::user()->role->nama_role === 'Admin Lab', 403, 'Admin Lab tidak diizinkan mengunggah dokumen sertifikasi.');

        $personil = Personil::findOrFail($id);
        $kompetensi = $personil->kompetensi()->findOrFail($kompetensiId);

        $request->validate([
            'file_sertifikat' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('file_sertifikat')) {
            if ($kompetensi->file_sertifikat && Storage::disk('local')->exists('public/uploads/sertifikat/' . $kompetensi->file_sertifikat)) {
                Storage::disk('local')->delete('public/uploads/sertifikat/' . $kompetensi->file_sertifikat);
            }

            $fileName = $request->file('file_sertifikat')->hashName();
            Storage::disk('local')->putFileAs('public/uploads/sertifikat', $request->file('file_sertifikat'), $fileName);
            $kompetensi->update(['file_sertifikat' => $fileName]);
        }

        return redirect()->route('sdm.kompetensi.detail', $personil->personil_id)
            ->with('success', 'Dokumen sertifikasi berhasil diunggah.');
    }

    public function showCv($id)
    {
        $personil = Personil::findOrFail($id);
        $path = 'public/uploads/cv/' . $personil->file_cv;

        abort_unless($personil->file_cv && Storage::disk('local')->exists($path), 404, 'File CV tidak ditemukan.');

        $fullPath = Storage::disk('local')->path($path);

        return response()->file($fullPath, [
            'Content-Disposition' => 'inline; filename="' . $personil->file_cv . '"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
        ]);
    }

    public function competencyMatrix()
    {
        $kategori = request('kategori');
        [$matrix, $jenisSertifikasiList] = $this->buildCompetencyMatrix($kategori);
        $kategoriOptions = KategoriPersonil::options();

        return view('sdm.competency_matrix', compact('matrix', 'jenisSertifikasiList', 'kategoriOptions', 'kategori'));
    }

    public function competencyMatrixPdf()
    {
        $kategori = request('kategori');
        [$matrix, $jenisSertifikasiList] = $this->buildCompetencyMatrix($kategori);
        $kategoriOptions = KategoriPersonil::options();
        $tanggalCetak = now()->translatedFormat('d F Y, H:i');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('sdm.competency_matrix_pdf', compact(
            'matrix',
            'jenisSertifikasiList',
            'kategori',
            'kategoriOptions',
            'tanggalCetak'
        ))->setPaper('a4', 'landscape');

        $namaFile = 'Competency Matrix - ' . now()->format('d-m-Y') . '.pdf';

        return $pdf->download($namaFile);
    }

    private function buildCompetencyMatrix(?string $kategori): array
    {
        $personil = Personil::with(['kompetensi'])
            ->where('status_aktif', true)
            ->when($kategori, fn($query) => $query->where('kategori_personil', $kategori))
            ->orderBy('nama')
            ->get();

        $jenisSertifikasiList = $personil
            ->flatMap(fn(Personil $p) => $p->kompetensi->pluck('jenis_sertifikasi'))
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->toArray();

        $matrix = $personil->map(function (Personil $p) use ($jenisSertifikasiList) {
            $sel = [];

            foreach ($jenisSertifikasiList as $jenis) {
                $terbaru = $p->kompetensi
                    ->where('jenis_sertifikasi', $jenis)
                    ->sortByDesc('tanggal_terbit')
                    ->first();

                $sel[$jenis] = $terbaru
                    ? ['status' => $this->resolveStatusSertifikasi($terbaru->tanggal_berakhir), 'kompetensi' => $terbaru]
                    : null;
            }

            return ['personil' => $p, 'kompetensi' => $sel];
        });

        return [$matrix, $jenisSertifikasiList];
    }
}