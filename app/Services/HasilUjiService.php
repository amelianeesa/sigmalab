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
    protected $crmService;
    protected $notificationService;

    public function __construct(
        CalculationService $calculationService,
        WestgardService $westgardService,
        CrmService $crmService,
        NotificationService $notificationService
    ) {
        $this->calculationService = $calculationService;
        $this->westgardService = $westgardService;
        $this->crmService = $crmService;
        $this->notificationService = $notificationService;
    }

    /**
     * Memproses data mentah hasil uji, melakukan kalkulasi, evaluasi In-house/CRM, 
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
            $newHasil->run_ke = $hasilUji->run_ke + 1;
            $newHasil->save();

            // Auto Create Tindak Lanjut
            RiwayatTindakLanjut::create([
                'hasil_uji_id'        => $hasilUji->hasil_uji_id,
                'status_tindak_lanjut' => 'belum_ditindaklanjuti',
                'catatan_investigasi'  => "Terdeteksi Gagal Duplo: " . $calcResult['message'],
                'created_at'           => now(),
            ]);

            // Notifikasi
            $pesanNotif = "?? Gagal Duplo pada kegiatan {$kegiatan->kode_sampel}, parameter {$parameter->nama_parameter}. " . $calcResult['message'];
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

        // EVALUATION ROUTING
        if ($hasilUji->jenis_kontrol === 'crm') {
            $certValue = null;
            $certU = null;
            
            if ($kegiatan->crm_katalog_id) {
                $sertifikat = \App\Models\CrmSertifikat::where('crm_katalog_id', $kegiatan->crm_katalog_id)
                    ->where('parameter_uji_id', $parameter->parameter_uji_id)
                    ->first();
                if ($sertifikat) {
                    $certValue = $sertifikat->cert_value;
                    $certU = $sertifikat->cert_u;
                }
            }

            $evaluasi = $this->crmService->evaluate($nilaiHasil, $certValue, $certU);
            
            $hasilUji->status_berketerimaan  = $evaluasi['status_berketerimaan'];
            $hasilUji->kode_aturan_dilanggar = $evaluasi['kode_aturan_dilanggar'];
            
            if ($evaluasi['kode_aturan_dilanggar'] === 'NO_LIMITS') {
                $pesanGagal = "Gagal memproses data karena Sertifikat True Value CRM kosong atau Lot CRM tidak dipilih pada kegiatan ini.";
            } else {
                $pesanGagal = "Nilai di luar rentang absolut Sertifikat CRM.";
            }
        } else {
            $evaluasi = $this->westgardService->evaluate($nilaiHasil, $parameter);
            
            $hasilUji->status_berketerimaan  = $evaluasi['status'];
            $hasilUji->kode_aturan_dilanggar = $evaluasi['kode'];
            $hasilUji->z_score               = $evaluasi['z_score'] ?? null;
            
            $pesanGagal = $evaluasi['pesan'] ?? 'Nilai di luar batas kendali.';
        }

        $hasilUji->save();

        $statusText = strtoupper($hasilUji->status_berketerimaan);
        if ($statusText === 'DITOLAK') $statusText = 'OUTLIER'; // Normalize UI text
        
        $message = "Hasil uji berhasil disimpan. Status: " . $statusText;

        // Auto-resolve Kegiatan to 'selesai' if all parameters are inlier
        if ($hasilUji->status_berketerimaan === 'inlier') {
            $totalParams = $kegiatan->hasilUji()->select('parameter_uji_id')->distinct()->count();
            $inlierParams = $kegiatan->hasilUji()
                ->where('status_berketerimaan', 'inlier')
                ->select('parameter_uji_id')
                ->distinct()
                ->count();
                
            if ($totalParams > 0 && $inlierParams === $totalParams) {
                if ($kegiatan->status_kegiatan !== 'selesai') {
                    $kegiatan->status_kegiatan = 'selesai';
                    $kegiatan->save();
                    
                    $notifMsg = "âœ… Pengujian Sampel {$kegiatan->kode_sampel} telah Selesai (Semua Parameter Inlier). Siap untuk ditinjau.";
                    $this->notificationService->notifyRoles($notifMsg, 'qc', [
                        PeranPengguna::KOORDINATOR_LAB->value,
                        PeranPengguna::MANAJER_TEKNIS->value,
                    ]);
                    
                    $message .= " (Semua parameter selesai, status sampel diubah menjadi Selesai).";
                }
            }
        }

        if ($hasilUji->status_berketerimaan === 'outlier' || $hasilUji->status_berketerimaan === 'ditolak') {
            $kodeLabel = $hasilUji->kode_aturan_dilanggar ? " [{$hasilUji->kode_aturan_dilanggar}]" : '';
            $message .= " — OUT-OF-CONTROL$kodeLabel. Tindak lanjut telah dibuat.";

            // Auto Create Tindak Lanjut
            RiwayatTindakLanjut::create([
                'hasil_uji_id'        => $hasilUji->hasil_uji_id,
                'status_tindak_lanjut' => 'belum_ditindaklanjuti',
                'catatan_investigasi'  => "Terdeteksi pelanggaran QC$kodeLabel: " . $pesanGagal,
                'created_at'           => now(),
            ]);

            // Notifikasi
            $pesanNotif = "?? Out-of-Control$kodeLabel pada kegiatan {$kegiatan->kode_sampel}, parameter {$parameter->nama_parameter}. " . $pesanGagal;
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