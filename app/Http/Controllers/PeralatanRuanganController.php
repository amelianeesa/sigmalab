<?php

namespace App\Http\Controllers;

use App\Models\PeralatanRuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PeralatanRuanganController extends Controller
{
    private const ALLOWED_ROLES = [
        'Admin Aplikasi',
        'Koordinator Laboratorium',
        'Analis Lab',
    ];

    public function store(Request $request)
    {
        $this->authorizeManage();

        $data = $request->validateWithBag('peralatan', [
            'nama_ruangan' => ['required', 'string', 'max:150'],
            'nama_peralatan' => ['required', 'array', 'min:1'],
            'nama_peralatan.*' => ['nullable', 'string', 'max:150'],
        ]);

        $daftarNama = collect($data['nama_peralatan'])
            ->map(fn ($nama) => trim((string) $nama))
            ->filter()
            ->unique();

        if ($daftarNama->isEmpty()) {
            return back()->withErrors(['nama_peralatan' => 'Isi minimal satu nama peralatan.'], 'peralatan');
        }

        foreach ($daftarNama as $nama) {
            PeralatanRuangan::firstOrCreate([
                'nama_ruangan' => trim($data['nama_ruangan']),
                'nama_peralatan' => $nama,
            ]);
        }

        return back()->with('success', 'Peralatan ruangan berhasil disimpan.');
    }

    public function update(Request $request, $id)
    {
        $this->authorizeManage();

        $peralatan = PeralatanRuangan::findOrFail($id);

        $data = $request->validateWithBag('peralatan', [
            'nama_peralatan' => [
                'required',
                'string',
                'max:150',
                Rule::unique('peralatan_ruangan', 'nama_peralatan')
                    ->where('nama_ruangan', $peralatan->nama_ruangan)
                    ->ignore($peralatan->peralatan_ruangan_id, 'peralatan_ruangan_id'),
            ],
        ], [
            'nama_peralatan.required' => 'Nama peralatan wajib diisi.',
            'nama_peralatan.unique' => 'Nama peralatan sudah ada di ruangan ini.',
        ]);

        $peralatan->update($data);

        return back()->with('success', 'Peralatan ruangan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $this->authorizeManage();

        PeralatanRuangan::findOrFail($id)->delete();

        return back()->with('success', 'Peralatan ruangan berhasil dihapus.');
    }

    private function authorizeManage(): void
    {
        abort_unless(
            in_array(Auth::user()->role->nama_role ?? '', self::ALLOWED_ROLES, true),
            403,
            'Anda tidak memiliki akses untuk mengelola peralatan ruangan.'
        );
    }
}