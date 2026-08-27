<?php

namespace App\Console\Commands;

use App\Models\Barang;
use App\Models\User;
use App\Mail\BarangStokHabis;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class CekStokBarangHabis extends Command
{
    protected $signature = 'barang:cek-stok';
    protected $description = 'Kirim pengingat berkala setiap 1 bulan sekali khusus untuk barang yang stoknya habis atau menipis';

    public function handle()
    {
        $barangs = Barang::all();

        foreach ($barangs as $barang) {
            $sisaStok = $barang->saldo_akhir ?? 0;
            $minStock = $barang->minimal_stok ?? 0;

            $statusPesan = '';

            if ($sisaStok <= 0) {
                $statusPesan = "Stok HABIS (Sisa: {$sisaStok})";
            } elseif ($sisaStok <= $minStock) {
                $statusPesan = "Stok MENIPIS (Sisa: {$sisaStok}, Minimal: {$minStock})";
            }

            if ($statusPesan != '') {
                $pesan = "Perhatian! Barang \"{$barang->nama_barang}\" (Kode: {$barang->kode_barang}) dalam kondisi: {$statusPesan}.";

                $notifTerakhir = DB::table('notifikasi')
                    ->where('jenis_notifikasi', 'stok')
                    ->where('pesan', 'LIKE', '%' . $barang->nama_barang . '%')
                    ->where('created_at', '>=', now()->subDays(30))
                    ->exists();

                if (!$notifTerakhir) {
                    $users = User::whereHas('role', function($q) { 
                        $q->where('nama_role', 'HR & GA')
                          ->orWhere('nama_role', 'Analis Lab'); 
                    })->get();

                    foreach ($users as $user) {
                        if ($user->email) {
                            Mail::to($user->email)->send(new BarangStokHabis($barang));
                        }

                        DB::table('notifikasi')->insert([
                            'users_id' => $user->users_id,
                            'jenis_notifikasi' => 'stok',
                            'pesan' => $pesan,
                            'is_read' => 0,
                            'created_at' => now(),
                        ]);
                    }

                    $this->info("Notifikasi stok berhasil dikirim untuk barang: " . $barang->nama_barang);
                }
            }
        }

        $this->info("Pengecekan stok barang habis/menipis selesai");
    }
}