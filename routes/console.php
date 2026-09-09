<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\RiwayatKalibrasi;
use App\Models\User;
use App\Mail\KalibrasiAkanHabis;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// notif sertifikasi
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// notif kalibrasi
Artisan::command('kalibrasi:cek-kadaluwarsa', function () {
    $batasHari = 180; 
    
    $data = RiwayatKalibrasi::with('alat')
        ->whereDate('tgl_akhir', '<=', now()->addDays($batasHari))
        ->whereDate('tgl_akhir', '>=', now())
        ->get();

    $bulanIni = now()->format('Y-m'); 

    foreach ($data as $item) {
        if (!$item->alat) continue;
        
        $pesan = "Peringatan Kalibrasi: Alat \"{$item->alat->nama_alat}\" akan habis masa kalibrasinya pada " . Carbon::parse($item->tgl_akhir)->format('d-m-Y') . " (Menjelang H-6 bulan).";
        
        $sudahKirimBulanIni = DB::table('notifikasi')
            ->where('jenis_notifikasi', 'kalibrasi')
            ->where('pesan', 'LIKE', '%' . $item->alat->nama_alat . '%')
            ->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$bulanIni])
            ->exists();

        if (!$sudahKirimBulanIni) {
            $users = User::whereHas('role', function($q) { 
                $q->whereIn('nama_role', ['Analis Lab', 'HR']); 
            })->get();

            foreach ($users as $user) {
           
                if ($user->email) {
                    try {
                        Mail::to($user->email)->send(new KalibrasiAkanHabis($item));
                    } catch (\Exception $e) {}
                }
                
                DB::table('notifikasi')->insert([
                    'users_id' => $user->users_id,
                    'jenis_notifikasi' => 'kalibrasi',
                    'pesan' => $pesan,
                    'is_read' => 0,
                    'created_at' => now(),
                ]);
            }
            $this->info("Notifikasi berkala H-6 bulan berhasil dikirim untuk alat: {$item->alat->nama_alat}");
        }
    }

    $this->info('Pengecekan kalibrasi berkala H-6 bulan selesai');
})->description('Mengecek kalibrasi H-6 bulan dan mengirim pengingat berkala tiap bulan via web dan email');

// Daftar Jadwal Otomatis Harian
Schedule::command('sertifikasi:cek-kadaluwarsa')->dailyAt('08:00');
Schedule::command('kalibrasi:cek-kadaluwarsa')->dailyAt('08:00');
Schedule::command('barang:cek-stok')->dailyAt('08:00');