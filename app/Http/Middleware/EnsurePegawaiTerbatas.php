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
    ];

    /** Route yang hanya boleh dibaca (GET/HEAD). */
    private const AKSES_BACA = [
        'dashboard',
        'sdm.*',
        'alat.*', 'evaluasi-kalibrasi.*', 'inventori.monitoring.*',
        'barang.*',
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