<?php

namespace App\Services;

use App\Models\HasilUji;
use App\Models\RiwayatTindakLanjut;
use App\Enums\PeranPengguna;
use Illuminate\Support\Facades\Auth;

class HasilUjiService
{
    protected $calculationService;
    protected $westgardService;
    protected $notificationService;

    public function __construct(
        CalculationService $calculationService,
        WestgardService $westgardService,
        NotificationService $notificationService
    ) {
        $this->calculationService = $calculationService;
        $this->westgardService = $westgardService;
        $this->notificationService = $notificationService;
    }

    /**
     * Memproses data mentah hasil uji, melakukan kalkulasi, evaluasi Westgard, 
     * dan menangani pembuatan tindak lanjut serta notifikasi jika terjadi kegagalan.
     *
     * @param HasilUji $hasilUji
     * @param array $validated
     * @return array
     */
    public function processResult(HasilUji $hasilUji, array $validated): array
    {
        $kegiatan = $hasilUji->kegiatan;
        $parameter = $hasilUji->parameterUji;
        
        $hasilUji->data_mentah = $validated['variabel'] ?? null;
        $hasilUji->diinput_oleh = Auth::id();

        $calcResult = $this->calculationService->calculate($hasilUji);

        if ($calcResult['status'] === 'pending') {
            $hasilUji->status_berketerimaan = 'pending';
            $hasilUji->save();
            return [
                'type' => 'warning',
                'message' => "Hasil uji disimpan dengan status PENDING. " . $calcResult['message'],
            ];
        } 
        
        if ($calcResult['status'] === 'gagal_duplo') {
            $hasilUji->nilai_hasil = (float) $calcResult['nilai'];
            $hasilUji->status_berketerimaan = 'gagal_duplo';
            $hasilUji->save();

            // Replicate a new empty row for re-testing
            $newHasil = $hasilUji->replicate();
            $newHasil->data_mentah = null;
            $newHasil->nilai_hasil = null;
            $newHasil->status_berketerimaan = 'belum_diuji';
            $newHasil->save();

            // Auto Create Tindak Lanjut
            RiwayatTindakLanjut::create([
                'hasil_uji_id'        => $hasilUji->hasil_uji_id,
                'status_tindak_lanjut' => 'belum_ditindaklanjuti',
                'catatan_investigasi'  => "Terdeteksi Gagal Duplo: " . $calcResult['message'],
                'created_at'           => now(),
            ]);

            // Notifikasi
            $pesanNotif = "⚠️ Gagal Duplo pada kegiatan {$kegiatan->kode_sampel}, parameter {$parameter->nama_parameter}. " . $calcResult['message'];
            $this->notificationService->notifyRoles($pesanNotif, 'qc', [
                PeranPengguna::ANALIS->value,
                PeranPengguna::KOORDINATOR_LAB->value,
            ]);

            return [
                'type' => 'error',
                'message' => $calcResult['message'] . " Riwayat tersimpan di Tindak Lanjut. Baris pengujian baru telah ditambahkan untuk uji ulang.",
            ];
        } 
        
        if ($calcResult['status'] === 'error') {
            return [
                'type' => 'error',
                'message' => $calcResult['message'],
            ];
        }

        $nilaiHasil = (float) $calcResult['nilai'];
        $hasilUji->nilai_hasil = $nilaiHasil;

        // WESTGARD EVALUATION
        $evaluasi = $this->westgardService->evaluate($nilaiHasil, $parameter);

        $hasilUji->status_berketerimaan  = $evaluasi['status'];
        $hasilUji->kode_aturan_dilanggar = $evaluasi['kode'];
        $hasilUji->z_score               = $evaluasi['z_score'];
        $hasilUji->save();

        $message = "Hasil uji berhasil disimpan. Status: " . strtoupper($evaluasi['status']);

        if ($evaluasi['status'] === 'outlier') {
            $kodeLabel = $evaluasi['kode'] ? " [{$evaluasi['kode']}]" : '';
            $message .= " — OUT-OF-CONTROL{$kodeLabel}. " . ($evaluasi['pesan'] ?? 'Tindak lanjut telah dibuat.');

            // Auto Create Tindak Lanjut
            RiwayatTindakLanjut::create([
                'hasil_uji_id'        => $hasilUji->hasil_uji_id,
                'status_tindak_lanjut' => 'belum_ditindaklanjuti',
                'catatan_investigasi'  => "Terdeteksi pelanggaran aturan Westgard{$kodeLabel}: " . ($evaluasi['pesan'] ?? 'Nilai di luar batas kendali.'),
                'created_at'           => now(),
            ]);

            // Notifikasi
            $pesanNotif = "⚠️ Out-of-Control{$kodeLabel} pada kegiatan {$kegiatan->kode_sampel}, parameter {$parameter->nama_parameter}. " . ($evaluasi['pesan'] ?? '');
            $this->notificationService->notifyRoles($pesanNotif, 'qc', [
                PeranPengguna::ANALIS->value,
                PeranPengguna::KOORDINATOR_LAB->value,
            ]);
        }
        
        // Auto-resolve pending results
        $resolvedCount = $this->calculationService->resolvePendingResults($kegiatan->kegiatan_id);
        
        if ($resolvedCount > 0) {
            $message .= " (Sistem juga berhasil menghitung otomatis $resolvedCount data pending lainnya).";
        }

        return [
            'type' => 'success',
            'message' => $message,
        ];
    }
}
