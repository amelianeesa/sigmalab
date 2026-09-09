<?php

namespace App\Console\Commands;

use App\Mail\KalibrasiAkanHabis;
use App\Models\RiwayatKalibrasi;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class CekKalibrasiKadaluwarsa extends Command
{
    protected $signature = 'kalibrasi:cek-kadaluwarsa';
    protected $description = 'Kirim pengingat kalibrasi berkala setiap 1 bulan sekali dalam rentang H-6 bulan';
 
    public function handle()
    {
        $batasHari = 180; 

        $data = RiwayatKalibrasi::with('alat')
            ->whereDate('tgl_akhir', '<=', now()->addDays($batasHari))
            ->whereDate('tgl_akhir', '>=', now())
            ->get();

        foreach ($data as $item) {
            $pesan = "Alat \"{$item->alat->nama_alat}\" akan habis masa kalibrasinya pada " . Carbon::parse($item->tgl_akhir)->format('d-m-Y');

            $notifTerakhir = DB::table('notifikasi')
                ->where('jenis_notifikasi', 'kalibrasi')
                ->where('pesan', 'LIKE', '%' . $item->alat->nama_alat . '%')
                ->where('created_at', '>=', now()->subDays(30))
                ->exists();

            if (!$notifTerakhir) {
                $users = User::whereHas('role', function($q) { 
                    $q->where('nama_role', 'HR & GA')
                      ->orWhere('nama_role', 'Analis Lab'); 
                })->get();

                foreach ($users as $user) {
                    if ($user->email) {
                        Mail::to($user->email)->send(new KalibrasiAkanHabis($item));
                        // $this->info("Email pengingat dikirim ke: {$user->email} untuk alat: {$item->alat->nama_alat}");
                    }
                    
                    DB::table('notifikasi')->insert([
                        'users_id' => $user->users_id,
                        'jenis_notifikasi' => 'kalibrasi',
                        'pesan' => $pesan,
                        'is_read' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                $this->info("Notifikasi berkala berhasil dikirim untuk alat: " . $item->alat->nama_alat);
                // $this->line("--------------------------------------------------");
            }
        }

        $this->info("Pengecekan kalibrasi berkala H-6 bulan selesai");
    }
}