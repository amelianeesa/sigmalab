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
    protected $description = 'Kirim pengingat kalibrasi berkala general h-6 bulan dengan format TO (Analis) dan CC (Koordinator)';
 
    public function handle()
    {
        $batasHari = 180;

        $data = RiwayatKalibrasi::with('alat')
            ->whereDate('tgl_akhir', '<=', now()->addDays($batasHari))
            ->whereDate('tgl_akhir', '>=', now())
            ->get();

        $sekarang = Carbon::now();

        foreach ($data as $item) {
            if (!$item->alat) {
                continue;
            }

            $tglKedaluwarsa = Carbon::parse($item->tgl_akhir);
            $sisaBulan = (int) ceil($sekarang->diffInMonths($tglKedaluwarsa, false));
            if ($sisaBulan < 1) $sisaBulan = 1;

            $namaAlat = $item->alat->nama_alat;
            $kodeAlat = $item->alat->kode_alat ?? '-';

            if ($sisaBulan >= 4) {
                $pesan = "Pengingat Pemeliharaan Alat: Masa kalibrasi {$namaAlat} ({$kodeAlat}) telah memasuki paruh waktu (Sisa {$sisaBulan} bulan). Harap segera menjadwalkan Kalibrasi Ulang serta Pengecekan Antara (khusus timbangan) untuk memastikan akurasi alat";
            } else {
                $pesan = "Peringatan Masa Berlaku Kalibrasi: Masa berlaku kalibrasi alat {$namaAlat} ({$kodeAlat}) akan berakhir dalam {$sisaBulan} bulan lagi. Harap segera menjadwalkan Kalibrasi Ulang.";
            }

            $item->custom_pesan = $pesan;
            $item->sisa_bulan = $sisaBulan;

            $notifTerakhir = DB::table('notifikasi')
                ->where('jenis_notifikasi', 'kalibrasi')
                ->where('pesan', 'LIKE', '%' . $namaAlat . '%')
                ->where('created_at', '>=', now()->subDays(30))
                ->exists();

            if (!$notifTerakhir) {
                $url = route('alat.show', $item->alat, false);

                $analisList = User::whereHas('role', function($q) { 
                    $q->where('nama_role', 'LIKE', '%Analis%'); 
                })->get();

                $koordinatorList = User::whereHas('role', function($q) { 
                    $q->where('nama_role', 'LIKE', '%Koordinator%'); 
                })->get();

                $koordinatorEmails = $koordinatorList->pluck('email')->filter()->toArray();

                foreach ($analisList as $analis) {
                    if ($analis->email) {
                        if (!empty($koordinatorEmails)) {
                            Mail::to($analis->email)
                                ->cc($koordinatorEmails)
                                ->send(new KalibrasiAkanHabis($item));
                        } else {
                            Mail::to($analis->email)->send(new KalibrasiAkanHabis($item));
                        }
                    }
                    
                    DB::table('notifikasi')->insert([
                        'users_id' => $analis->users_id,
                        'jenis_notifikasi' => 'kalibrasi',
                        'pesan' => "[TO] " . $pesan,
                        'url' => $url,
                        'is_read' => 0,
                        'created_at' => now(),
                    ]);
                }

                foreach ($koordinatorList as $koordinator) {
                    DB::table('notifikasi')->insert([
                        'users_id' => $koordinator->users_id,
                        'jenis_notifikasi' => 'kalibrasi',
                        'pesan' => "[CC] " . $pesan,
                        'url' => $url,
                        'is_read' => 0,
                        'created_at' => now(),
                    ]);
                }

                $this->info("Notifikasi & Email general berhasil dikirim untuk alat: " . $namaAlat);
            }
        }

        $this->info("Pengecekan kalibrasi berkala general H-6 bulan selesai");
    }
}