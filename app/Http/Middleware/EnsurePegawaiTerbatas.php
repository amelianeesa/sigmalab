<?php

namespace App\Http\Middleware;

use App\Enums\PeranPengguna;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Role "Non-Lab" (akun Non-Lab yang dibuatkan HR).
 *
 * - Peminjaman, profil, notifikasi, workspace switch, logout : akses penuh.
 *   Ditambah aksi sertifikasi & CV di modul Personil (hanya untuk data milik sendiri, dicek di controller).
 * - Pengadaan : boleh melihat daftar, mengajukan (store), & membatalkan pengajuan sendiri. Selebihnya ditolak.
 * - Dashboard, Personil & Kompetensi, Peralatan, Inventori Barang, Library Digital,
 *   Audit Trail : BACA SAJA (GET/HEAD), tanpa halaman form tambah/ubah.
 * - Selain itu ditolak di server (deny-by-default), karena sebagian controller lama
 *   (alat, barang, sdm, monitoring ruangan) belum punya pengecekan hak akses sendiri.
 */
class EnsurePegawaiTerbatas
{
    /** Route yang boleh dipakai penuh (semua method). */
    private const AKSES_PENUH = [
        'peminjaman.*', 'profil.*', 'notifikasi.*', 'workspace.switch', 'logout',
        // Kelola sertifikasi & CV milik sendiri (kepemilikan dicek di SdmController).
        'sdm.kompetensi.store', 'sdm.kompetensi.update', 'sdm.kompetensi.destroy',
        'sdm.kompetensi.file.upload', 'sdm.cv.upload',
        // Tambah barang/bahan (sama seperti Analis Lab). Edit, hapus & pengeluaran tetap tertutup.
        'barang.create', 'barang.store',
        // Ajukan pengadaan barang/bahan. Pengajuan masuk ke Koordinator dulu. Persetujuan, proses, & konfirmasi terima tetap tertutup.
        'pengadaan.store', 'pengadaan.destroy', // destroy: batalkan pengajuan sendiri (dicek di controller)
    ];

    /** Route yang hanya boleh dibaca (GET/HEAD). */
    private const AKSES_BACA = [
        'dashboard',
        'sdm.*',
        'alat.*', 'evaluasi-kalibrasi.*', 'inventori.monitoring.*',
        'barang.*',
        'pengadaan.index',
        'library.*',
        'audit-log.*',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->role?->nama_role === PeranPengguna::PEGAWAI->value) {
            if ($request->routeIs(...self::AKSES_PENUH)) {
                return $next($request);
            }

            $bacaSaja = $request->isMethodSafe()
                && $request->routeIs(...self::AKSES_BACA)
                && !$request->routeIs('*.create', '*.edit', '*.*.create', '*.*.edit');

            if (!$bacaSaja) {
                abort(403, 'Anda tidak memiliki akses ke halaman ini.');
            }
        }

        return $next($request);
    }
}