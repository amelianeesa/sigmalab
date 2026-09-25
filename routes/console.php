<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\RiwayatPerbaikanAlat;
use App\Models\User;
use App\Mail\LaporanKerusakanMail; 
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

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
            $url = route('alat.show', $item->alat, false);

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
                            Mail::to($ga->email)->cc($koordinatorEmails)->send(new LaporanKerusakanMail($item));
                        } else {
                            Mail::to($ga->email)->send(new LaporanKerusakanMail($item));
                        }
                    } catch (\Exception $e) {}
                }

                DB::table('notifikasi')->insert([
                    'users_id' => $ga->users_id,
                    'jenis_notifikasi' => 'perbaikan',
                    'pesan' => "[TO] " . $pesan,
                    'url' => $url,
                    'is_read' => 0,
                    'created_at' => now(),
                ]);
            }

            foreach ($koordinatorList as $koordinator) {
                DB::table('notifikasi')->insert([
                    'users_id' => $koordinator->users_id,
                    'jenis_notifikasi' => 'perbaikan',
                    'pesan' => "[CC] " . $pesan,
                    'url' => $url,
                    'is_read' => 0,
                    'created_at' => now(),
                ]);
            }

            $this->info("Notifikasi status perbaikan dikirim untuk alat: {$namaAlat}");
        }
    }

    $this->info('Pengecekan rekap perbaikan alat selesai.');
})->description('Mengecek dan mengirim pengingat berkala untuk alat yang masih dalam status perbaikan');

Schedule::command('sertifikasi:cek-kadaluwarsa')->dailyAt('08:00');
Schedule::command('kalibrasi:cek-kadaluwarsa')->dailyAt('08:00');
Schedule::command('perbaikan:cek-status')->dailyAt('08:05');
Schedule::command('barang:cek-stok')->dailyAt('08:00');
Schedule::command('pengadaan:cek-status')->dailyAt('08:00');