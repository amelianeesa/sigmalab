<?php

namespace App\Console\Commands;

use App\Mail\KalibrasiAkanHabis;
use App\Models\RiwayatKalibrasi;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CekKalibrasiKadaluwarsa extends Command
{
    protected $signature = 'kalibrasi:cek-kadaluwarsa';

    public function handle()
    {
        $batasHari = 30;
        $data = RiwayatKalibrasi::with('alat')
            ->where('reminder_terkirim', false)
            ->whereDate('tgl_akhir', '<=', now()->addDays($batasHari))
            ->whereDate('tgl_akhir', '>=', now())
            ->get();

        foreach ($data as $item) {
            $pesan = "Alat \"{$item->alat->nama_alat}\" akan habis masa kalibrasinya pada " . $item->tgl_akhir->format('d-m-Y');
            
            $users = User::whereHas('role', function($q) { $q->where('nama_role', 'Admin Lab'); })->get();

            foreach ($users as $user) {
                if ($user->email) Mail::to($user->email)->send(new KalibrasiAkanHabis($item));
                
                DB::table('notifikasi')->insert([
                    'users_id' => $user->users_id,
                    'jenis_notifikasi' => 'kalibrasi',
                    'pesan' => $pesan,
                    'is_read' => 0,
                    'created_at' => now(),
                ]);
            }
            $item->update(['reminder_terkirim' => true]);
        }
    }
}