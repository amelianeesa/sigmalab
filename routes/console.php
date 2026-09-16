<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\RiwayatKalibrasi;
use App\Models\RiwayatPerbaikanAlat;
use App\Models\User;
use App\Mail\KalibrasiAkanHabis;
use App\Mail\LaporanKerusakanMail; // Pastikan Mailable ini di-import
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// 1. Inspiring quote bawaan
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// kalibrasi
Artisan::command('kalibrasi:cek-kadaluwarsa', function () {
    $batasHari = 180;
    
    $data = RiwayatKalibrasi::with('alat')
        ->whereDate('tgl_akhir', '<=', now()->addDays($batasHari))
        ->whereDate('tgl_akhir', '>=', now())
        ->get();

    $sekarang = Carbon::now();
    $bulanIni = $sekarang->format('Y-m'); 

    foreach ($data as $item) {
        if (!$item->alat) continue;
        
        $tglKedaluwarsa = Carbon::parse($item->tgl_akhir);
        $sisaBulan = (int) ceil($sekarang->diffInMonths($tglKedaluwarsa, false));
        if ($sisaBulan < 1) $sisaBulan = 1;

        $namaAlat = $item->alat->nama_alat;
        $kodeAlat = $item->alat->kode_alat ?? '-';

        if ($sisaBulan >= 4) {
            $pesan = "Pengingat Pemeliharaan Alat: Masa kalibrasi {$namaAlat} ({$kodeAlat}) telah memasuki paruh waktu (Sisa {$sisaBulan} bulan). Harap segera menjadwalkan Kalibrasi Ulang dan Pengecekan Antara (khusus Timbangan) untuk memastikan akurasi alat";
        } else {
            $pesan = "Peringatan Masa Berlaku Kalibrasi: Masa berlaku kalibrasi alat {$namaAlat} ({$kodeAlat}) akan berakhir dalam {$sisaBulan} bulan lagi. Harap segera menjadwalkan Kalibrasi Ulang";
        }

        $item->custom_pesan = $pesan;
        $item->sisa_bulan = $sisaBulan;
        
        $sudahKirimBulanIni = DB::table('notifikasi')
            ->where('jenis_notifikasi', 'kalibrasi')
            ->where('pesan', 'LIKE', '%' . $namaAlat . '%')
            ->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$bulanIni])
            ->exists();

        if (!$sudahKirimBulanIni) {
            $analisList = User::whereHas('role', function($q) { 
                $q->where('nama_role', 'LIKE', '%Analis%'); 
            })->get();

            $koordinatorList = User::whereHas('role', function($q) { 
                $q->where('nama_role', 'LIKE', '%Koordinator%'); 
            })->get();

            $koordinatorEmails = $koordinatorList->pluck('email')->filter()->toArray();

            foreach ($analisList as $analis) {
                if ($analis->email) {
                    try {
                        if (!empty($koordinatorEmails)) {
                            Mail::to($analis->email)->cc($koordinatorEmails)->send(new KalibrasiAkanHabis($item));
                        } else {
                            Mail::to($analis->email)->send(new KalibrasiAkanHabis($item));
                        }
                    } catch (\Exception $e) {}
                }
                
                DB::table('notifikasi')->insert([
                    'users_id' => $analis->users_id,
                    'jenis_notifikasi' => 'kalibrasi',
                    'pesan' => "[TO] " . $pesan,
                    'is_read' => 0,
                    'created_at' => now(),
                ]);
            }

            foreach ($koordinatorList as $koordinator) {
                DB::table('notifikasi')->insert([
                    'users_id' => $koordinator->users_id,
                    'jenis_notifikasi' => 'kalibrasi',
                    'pesan' => "[CC] " . $pesan,
                    'is_read' => 0,
                    'created_at' => now(),
                ]);
            }

            $this->info("Notifikasi & Email kalibrasi berhasil dikirim untuk alat: {$namaAlat}");
        }
    }

    $this->info('Pengecekan kalibrasi berkala H-6 bulan selesai');
})->description('Mengecek kalibrasi H-6 bulan dengan format TO/CC khusus kalibrasi');

// perbaikan alat
Artisan::command('perbaikan:cek-status', function () {
    $dataPerbaikan = RiwayatPerbaikanAlat::with('alat')
        ->whereIn('status_perbaikan', ['Belum Diperbaiki', 'Dalam Perbaikan'])
        ->get();

    if ($dataPerbaikan->isEmpty()) {
        $this->info("Tidak ada alat yang sedang dalam status rusak atau perbaikan.");
        return;
    }

    $sekarang = Carbon::now();
    $bulanIni = $sekarang->format('Y-m');

    foreach ($dataPerbaikan as $item) {
        if (!$item->alat) continue;

        $namaAlat = $item->alat->nama_alat;
        $kodeAlat = $item->alat->kode_alat ?? '-';
        $status = $item->status_perbaikan;
        
        $pesan = "Pengingat Perbaikan Alat: Alat \"{$namaAlat}\" ({$kodeAlat}) saat ini masih berstatus \"{$status}\". Harap segera ditindaklanjuti.";

        $sudahKirimBulanIni = DB::table('notifikasi')
            ->where('jenis_notifikasi', 'perbaikan')
            ->where('pesan', 'LIKE', '%' . $namaAlat . '%')
            ->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$bulanIni])
            ->exists();

        if (!$sudahKirimBulanIni) {
            $gaList = User::whereHas('role', function($q) { 
                $q->where('nama_role', 'GA'); 
            })->get();

            $koordinatorList = User::whereHas('role', function($q) { 
                $q->whereIn('nama_role', [
                    'Koordinator Laboratorium',
                    'Kabid Inspeksi dan Solusi Perdagangan',
                    'Kabid Dukungan Bisnis'
                ]); 
            })->get();

            $koordinatorEmails = $koordinatorList->pluck('email')->filter()->toArray();

            foreach ($gaList as $ga) {
                if ($ga->email) {
                    try {
                        if (!empty($koordinatorEmails)) {
                            $primaryGaEmail = $ga->email;
                            $allCcEmails = $koordinatorEmails;
                            
                            // Menggunakan Mailable View HTML yang sama
                            Mail::to($primaryGaEmail)->cc($allCcEmails)->send(new LaporanKerusakanMail($item));
                        } else {
                            Mail::to($ga->email)->send(new LaporanKerusakanMail($item));
                        }
                    } catch (\Exception $e) {}
                }

                DB::table('notifikasi')->insert([
                    'users_id' => $ga->users_id,
                    'jenis_notifikasi' => 'perbaikan',
                    'pesan' => "[TO] " . $pesan,
                    'is_read' => 0,
                    'created_at' => now(),
                ]);
            }

            foreach ($koordinatorList as $koordinator) {
                DB::table('notifikasi')->insert([
                    'users_id' => $koordinator->users_id,
                    'jenis_notifikasi' => 'perbaikan',
                    'pesan' => "[CC] " . $pesan,
                    'is_read' => 0,
                    'created_at' => now(),
                ]);
            }

            $this->info("Notifikasi status perbaikan dikirim untuk alat: {$namaAlat}");
        }
    }

    $this->info('Pengecekan rekap perbaikan alat selesai.');
})->description('Mengecek dan mengirim pengingat berkala untuk alat yang masih dalam status perbaikan');

// jadwal otomatis
Schedule::command('sertifikasi:cek-kadaluwarsa')->dailyAt('08:00');
Schedule::command('kalibrasi:cek-kadaluwarsa')->dailyAt('08:00');
Schedule::command('perbaikan:cek-status')->dailyAt('08:05');
Schedule::command('barang:cek-stok')->dailyAt('08:00');