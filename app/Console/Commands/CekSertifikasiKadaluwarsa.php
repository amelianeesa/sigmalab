<?php

namespace App\Console\Commands;

use App\Mail\SertifikasiAkanHabis;
use App\Models\KompetensiPersonil;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CekSertifikasiKadaluwarsa extends Command
{
    protected $signature = 'sertifikasi:cek-kadaluwarsa';

    protected $description = 'Cek sertifikasi/pelatihan yang akan habis dalam 6 bulan, kirim reminder email & notifikasi in-app setiap bulan sampai diperbarui';

    protected int $batasBulan = 6;

    public function handle()
    {
        $this->info('Mengecek sertifikasi yang akan habis dalam ' . $this->batasBulan . ' bulan...');

        $data = KompetensiPersonil::with('personil.user')
            ->whereNotNull('tanggal_berakhir')
            ->whereDate('tanggal_berakhir', '<=', now()->addMonths($this->batasBulan))
            ->whereDate('tanggal_berakhir', '>=', now())
            ->where(function ($query) {
                $query->whereNull('reminder_terakhir_dikirim')
                    ->orWhere('reminder_terakhir_dikirim', '<=', now()->subMonth());
            })
            ->get();

        if ($data->isEmpty()) {
            $this->info('Tidak ada sertifikasi yang perlu diingatkan bulan ini.');
            return;
        }

        $emailTerkirim = 0;
        $notifTerkirim = 0;

        foreach ($data as $item) {
            $personil = $item->personil;
            $user = $personil?->user;

            $pesan = "Sertifikasi \"{$item->jenis_sertifikasi}\" milik {$personil->nama} akan habis masa berlakunya pada "
                . $item->tanggal_berakhir->format('d-m-Y') . '.';

            if ($user && $user->email) {
                try {
                    Mail::to($user->email)->send(new SertifikasiAkanHabis($item));
                    $emailTerkirim++;
                    $this->line("Email terkirim ke {$user->email} untuk {$item->jenis_sertifikasi}");
                } catch (\Exception $e) {
                    $this->error("Gagal kirim email untuk ID {$item->kompetensi_personil_id}: " . $e->getMessage());
                }
            } else {
                $this->warn("Personil '{$personil->nama}' belum punya akun/email, email dilewati.");
            }

            if ($user) {
                DB::table('notifikasi')->insert([
                    'users_id'         => $user->users_id,
                    'jenis_notifikasi' => 'sertifikasi',
                    'pesan'            => $pesan,
                    'is_read'          => 0,
                    'created_at'       => now(),
                ]);
                $notifTerkirim++;
            }

            $item->update(['reminder_terakhir_dikirim' => now()]);
        }

        $this->info("Selesai. {$emailTerkirim} email terkirim, {$notifTerkirim} notifikasi in-app dibuat.");
    }
}