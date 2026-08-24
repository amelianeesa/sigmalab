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

    protected $description = 'Cek sertifikasi/pelatihan yang akan habis masa berlakunya, kirim email & notifikasi in-app';

    protected int $batasHari = 30;

    public function handle()
    {
        $this->info('Mengecek sertifikasi yang akan habis dalam ' . $this->batasHari . ' hari...');

        $data = KompetensiPersonil::with('personil.user')
            ->where('reminder_terkirim', false)
            ->whereNotNull('tanggal_berakhir')
            ->whereDate('tanggal_berakhir', '<=', now()->addDays($this->batasHari))
            ->whereDate('tanggal_berakhir', '>=', now())
            ->get();

        if ($data->isEmpty()) {
            $this->info('Tidak ada sertifikasi yang perlu diingatkan hari ini.');
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

            $item->update(['reminder_terkirim' => true]);
        }

        $this->info("Selesai. {$emailTerkirim} email terkirim, {$notifTerkirim} notifikasi in-app dibuat.");
    }
}
