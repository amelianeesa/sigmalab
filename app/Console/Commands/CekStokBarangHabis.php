<?php

namespace App\Console\Commands;

use App\Models\Barang;
use App\Models\User;
use App\Mail\BarangStokHabis;
use App\Enums\PeranPengguna;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class CekStokBarangHabis extends Command
{
    protected $signature = 'barang:cek-stok {--force : Abaikan pengecekan reminder 30 hari terakhir, untuk keperluan testing}';
    protected $description = 'Pengecekan stok minim dan barang kadaluarsa (< 6 bulan) untuk Analis & Koordinator Lab';

    public function handle()
    {
        $barangs = Barang::all();
        $enamBulanLagi = Carbon::now()->addMonths(6);

        // Role penerima reminder "tolong ajukan pengadaan" — pakai Enum, BUKAN string manual,
        // supaya nama role selalu sinkron dengan tabel roles (sebelumnya 'Koordinator Lab' typo,
        // seharusnya 'Koordinator Laboratorium', jadi Koordinator Lab tidak pernah kebagian notifikasi).
        $rolePenerima = [
            PeranPengguna::ANALIS->value,
            PeranPengguna::KOORDINATOR_LAB->value,
        ];

        foreach ($barangs as $barang) {
            $sisaStok = $barang->saldo_akhir ?? 0;
            $minStock = $barang->minimal_stok ?? 0;
            $tglExp   = $barang->tgl_exp ? Carbon::parse($barang->tgl_exp) : null;

            $perluNotif = false;
            $statusPesan = '';

            // Cek kondisi Stok Minim/Habis ATAU Kadaluarsa <= 6 bulan lagi
            if ($sisaStok <= $minStock) {
                $statusPesan = "Stok Menipis/Habis (Sisa: {$sisaStok}, Min: {$minStock})";
                $perluNotif = true;
            } elseif ($tglExp && $tglExp->lte($enamBulanLagi)) {
                $statusPesan = "Mendekati Kadaluarsa (Exp: " . $tglExp->format('d-m-Y') . ")";
                $perluNotif = true;
            }

            if (!$perluNotif) {
                continue;
            }

            $pesan = "Perhatian! Barang \"{$barang->nama_barang}\" (Kode: {$barang->kode_barang}) {$statusPesan}. Silakan ajukan pengadaan.";

            // Reminder berkelanjutan: dikirim ulang tiap 1 bulan (30 hari) selama kondisi
            // (stok menipis / mendekati exp) masih berlaku. Dicek per-barang, jadi kalau sudah
            // diajukan & saldo/exp diperbarui, siklus reminder untuk barang itu otomatis berhenti.
            //
            // ⬅️ FIX: tambah filter frasa "Silakan ajukan pengadaan" karena jenis_notifikasi 'stok'
            // juga dipakai oleh notifikasi lain yang menyebut nama barang yang sama, misalnya notif
            // ke GA/Kabid saat ada pengajuan (lihat PengadaanController::notifikasiInAppGAAndKabid
            // & notifikasiKeKoordinator). Tanpa filter ini, begitu ada orang mengajukan pengadaan
            // untuk barang X, sistem salah kira "reminder bulan ini sudah terkirim" untuk barang X,
            // sehingga siklus reminder 6 bulan bisa berhenti lebih awal dari seharusnya.
            $notifBulanIni = !$this->option('force') && DB::table('notifikasi')
                ->where('jenis_notifikasi', 'stok')
                ->where('pesan', 'LIKE', '%Silakan ajukan pengadaan%')
                ->where('pesan', 'LIKE', '%' . $barang->nama_barang . '%')
                ->where('created_at', '>=', now()->subDays(30))
                ->exists();

            if ($notifBulanIni) {
                continue;
            }

            $penerima = User::whereHas('role', function ($q) use ($rolePenerima) {
                $q->whereIn('nama_role', $rolePenerima);
            })->whereNotNull('email')->get();

            foreach ($penerima as $user) {
                Mail::to($user->email)->send(new BarangStokHabis($barang, $statusPesan));

                DB::table('notifikasi')->insert([
                    'users_id'         => $user->users_id,
                    'jenis_notifikasi' => 'stok',
                    'pesan'            => $pesan,
                    'is_read'          => 0,
                    'created_at'       => now(),
                ]);
            }

            $this->info("Reminder stok dikirim ke Analis & Koordinator untuk: " . $barang->nama_barang);
        }

        $this->info("Pengecekan stok & kadaluarsa selesai.");
    }
}