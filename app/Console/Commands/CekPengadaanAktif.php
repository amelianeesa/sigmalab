<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PermintaanPengadaan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\PengingatSetengahTargetMail;
use App\Mail\KeterlambatanPengadaanMail;

class CekPengadaanAktif extends Command
{
    protected $signature = 'pengadaan:cek-status';
    protected $description = 'Mengecek dan mengirim email otomatis untuk pengadaan setengah target atau terlambat';

    public function handle()
    {
        $pengadaanAktif = PermintaanPengadaan::with(['barang', 'pemohon'])
            ->whereNotIn('status', ['selesai', 'ditolak', 'batal'])
            ->get();        

        $emailGa = User::whereHas('role', fn($q) => $q->where('nama_role', 'GA'))
            ->pluck('email')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        $emailKoor = User::whereHas('role', fn($q) => $q->where('nama_role', 'Koordinator Laboratorium'))
            ->pluck('email')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        foreach ($pengadaanAktif as $p) {
            $tglBuat = Carbon::parse($p->created_at);
            $sekarang = Carbon::now();
            
            $diff = $tglBuat->diff($sekarang);
            $formatHariBerjalan = '';
            if ($diff->y > 0) { $formatHariBerjalan .= $diff->y . ' tahun '; }
            if ($diff->m > 0) { $formatHariBerjalan .= $diff->m . ' bulan '; }
            if ($diff->d > 0 || $formatHariBerjalan == '') { $formatHariBerjalan .= $diff->d . ' hari'; }

            $hariBerjalanAngka = round($tglBuat->diffInDays($sekarang));
            $totalHariTarget = ($p->target_tahun * 365) + ($p->target_bulan * 30) + $p->target_hari;
            if ($totalHariTarget <= 0) { $totalHariTarget = 1; }
            $setengahTarget = $totalHariTarget / 2;

            if (!empty($emailGa)) {
                $primaryGa = $emailGa[0]; 
                $sisaGa = array_slice($emailGa, 1);
                $cleanKoor = array_diff($emailKoor, $emailGa); 
                $ccRecipients = array_merge($sisaGa, $cleanKoor);

                if ($hariBerjalanAngka >= $totalHariTarget && $p->status != 'selesai') {
                    try {
                        Mail::to($primaryGa)
                            ->cc($ccRecipients)
                            ->send(new KeterlambatanPengadaanMail($p, $formatHariBerjalan));
                    } catch (\Exception $e) {
                    }
                } 
                elseif ($hariBerjalanAngka >= $setengahTarget && $hariBerjalanAngka < $totalHariTarget && in_array($p->status, ['disetujui', 'menunggu_ga'])) {
                    try {
                        Mail::to($primaryGa)
                            ->send(new PengingatSetengahTargetMail($p, $formatHariBerjalan));
                    } catch (\Exception $e) {
                    }
                }
            }
        }

        $this->info('Pengecekan dan pengiriman notifikasi pengadaan selesai dijalankan.');
    }
}