<?php

namespace App\Services;

use App\Models\HasilUji;
use App\Models\ParameterUji;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Illuminate\Support\Facades\Log;

class CalculationService
{
    protected $expressionLanguage;
    protected $westgardService;

    public function __construct(WestgardService $westgardService)
    {
        $this->expressionLanguage = new ExpressionLanguage();
        $this->expressionLanguage->register('abs', function ($num) { return sprintf('abs(%s)', $num); }, function ($arguments, $num) { return abs($num); });
        $this->expressionLanguage->register('round', function ($num, $precision = 0) { return sprintf('round(%s, %s)', $num, $precision); }, function ($arguments, $num, $precision = 0) { return round($num, $precision); });
        $this->expressionLanguage->register('pow', function ($base, $exp) { return sprintf('pow(%s, %s)', $base, $exp); }, function ($arguments, $base, $exp) { return pow($base, $exp); });
        $this->expressionLanguage->register('sqrt', function ($num) { return sprintf('sqrt(%s)', $num); }, function ($arguments, $num) { return sqrt($num); });
        
        $this->westgardService = $westgardService;
    }

    /**
     * Mencoba melakukan kalkulasi hasil uji.
     * Jika dependensi tidak terpenuhi, kembalikan status pending.
     */
    public function calculate(HasilUji $hasilUji): array
    {
        $parameter = $hasilUji->parameterUji;
        $variabel = $hasilUji->data_mentah ?? [];
        
        // Jika tidak ada rumus, gunakan nilai M1 (atau yang setara jika ada),
        // atau jika analis kebetulan input manual nilai_hasil dan tidak ada variabel input.
        if (empty($parameter->rumus_kalkulasi)) {
            // Asumsikan jika tidak ada rumus, input dari user adalah langsung ke nilai_hasil 
            // Atau ini untuk parameter sederhana yang tidak ada rumus.
            if (isset($variabel['Nilai'])) {
                return ['status' => 'success', 'nilai' => $variabel['Nilai']];
            }
            return ['status' => 'success', 'nilai' => $hasilUji->nilai_hasil];
        }

        // ==========================================
        // DYNAMIC STEP-BY-STEP CALCULATOR (OVERRIDE)
        // ==========================================
        if (!empty($parameter->langkah_kalkulasi) && is_array($parameter->langkah_kalkulasi)) {
            // Cek dependensi terlebih dahulu
            $dependensi = $parameter->dependensi_parameter ?? [];
            $missingDependencies = [];
            foreach ($dependensi as $depName) {
                $depParam = ParameterUji::where('nama_parameter', $depName)->first();
                if ($depParam) {
                    $depHasil = HasilUji::where('kegiatan_id', $hasilUji->kegiatan_id)
                        ->where('parameter_uji_id', $depParam->parameter_uji_id)
                        ->whereNotNull('nilai_hasil')
                        ->where('status_berketerimaan', '!=', 'pending')
                        ->where('status_berketerimaan', '!=', 'gagal_duplo')
                        ->first();
                    if (!$depHasil) {
                        $missingDependencies[] = $depName;
                    } else {
                        $variabel[$depName] = $depHasil->nilai_hasil;
                    }
                } else {
                    return ['status' => 'error', 'message' => "Parameter dependensi {$depName} tidak ditemukan."];
                }
            }
            if (count($missingDependencies) > 0) {
                return ['status' => 'pending', 'message' => 'Menunggu Data: ' . implode(', ', $missingDependencies)];
            }

            // Eksekusi langkah-langkah kalkulasi
            foreach ($parameter->langkah_kalkulasi as $langkah) {
                if (!empty($langkah['var']) && !empty($langkah['rumus'])) {
                    try {
                        $val = (float) $this->expressionLanguage->evaluate($langkah['rumus'], $variabel);
                        $variabel[$langkah['var']] = $val;
                    } catch (\Throwable $e) {
                        return ['status' => 'error', 'message' => "Gagal menghitung langkah '{$langkah['var']}': " . $e->getMessage()];
                    }
                }
            }
            
            // Simpan variabel hasil kalkulasi interim
            $hasilUji->data_mentah = $variabel;
            $hasilUji->saveQuietly();

            // Cek Evaluasi Duplo (opsional, jika variabel Absolute_Diff diatur)
            if (isset($variabel['Absolute_Diff']) && !empty($parameter->toleransi_duplo)) {
                try {
                    $toleransi = (float) $this->expressionLanguage->evaluate($parameter->toleransi_duplo, $variabel);
                    if ($variabel['Absolute_Diff'] > $toleransi) {
                        $tempVal = $variabel['Nilai_Akhir'] ?? 0;
                        return [
                            'status' => 'gagal_duplo',
                            'nilai' => $tempVal,
                            'message' => "Gagal Validasi Duplo! Selisih (" . number_format($variabel['Absolute_Diff'], 4) . ") melampaui batas toleransi (" . number_format($toleransi, 4) . ")."
                        ];
                    }
                } catch (\Throwable $e) {
                    // Jika toleransi gagal dihitung
                    return ['status' => 'error', 'message' => "Gagal menghitung formula toleransi duplo: " . $e->getMessage()];
                }
            }

            // Eksekusi Nilai Akhir
            if (str_starts_with($parameter->rumus_kalkulasi, 'CUSTOM_')) {
                if (isset($variabel['Nilai_Akhir'])) {
                    return ['status' => 'success', 'nilai' => $variabel['Nilai_Akhir']];
                }
                return ['status' => 'error', 'message' => 'Langkah kalkulasi kustom tidak menghasilkan variabel "Nilai_Akhir". Silakan tambahkan langkah dengan nama variabel output "Nilai_Akhir".'];
            } else {
                try {
                    $finalValue = (float) $this->expressionLanguage->evaluate($parameter->rumus_kalkulasi, $variabel);
                    return ['status' => 'success', 'nilai' => $finalValue];
                } catch (\Throwable $e) {
                    return ['status' => 'error', 'message' => "Gagal menghitung Rumus Kalkulasi Nilai Akhir: " . $e->getMessage()];
                }
            }
        }

        // ==========================================
        // CUSTOM CALCULATORS (e.g. DUPLO LOGIC)
        // ==========================================
        if ($parameter->rumus_kalkulasi === 'CUSTOM_IM') {
            // Pastikan data Duplo lengkap (M1, A, M3)
            $required = ['M1_D1', 'A_D1', 'M3_D1', 'M1_D2', 'A_D2', 'M3_D2'];
            foreach ($required as $req) {
                if (!isset($variabel[$req]) || !is_numeric($variabel[$req])) {
                    return ['status' => 'error', 'message' => "Data {$req} tidak valid atau belum diisi."];
                }
            }

            // Hitung M% Dish 1
            $A1 = $variabel['A_D1'];
            if ($A1 == 0) return ['status' => 'error', 'message' => "Massa awal sampel Dish 1 (A) tidak boleh 0."];
            $M2_1 = $variabel['M1_D1'] + $A1;
            $B1 = $variabel['M3_D1'] - $variabel['M1_D1'];
            $M_D1 = (($A1 - $B1) / $A1) * 100;

            // Hitung M% Dish 2
            $A2 = $variabel['A_D2'];
            if ($A2 == 0) return ['status' => 'error', 'message' => "Massa awal sampel Dish 2 (A) tidak boleh 0."];
            $M2_2 = $variabel['M1_D2'] + $A2;
            $B2 = $variabel['M3_D2'] - $variabel['M1_D2'];
            $M_D2 = (($A2 - $B2) / $A2) * 100;

            // Validasi Toleransi Duplo dinamis (0.09 + (0.1 * Average_M))
            $averageM = ($M_D1 + $M_D2) / 2;
            $toleransiDinamis = 0.09 + (0.1 * $averageM);
            
            $duplo = $this->validateDuplo($M_D1, $M_D2, $toleransiDinamis);
            $absDiff = $duplo['diff'];

            $variabel['M2_D1_Result'] = $M2_1;
            $variabel['M2_D2_Result'] = $M2_2;
            $variabel['M_D1_Result'] = $M_D1;
            $variabel['M_D2_Result'] = $M_D2;
            $variabel['Absolute_Diff'] = $absDiff;
            
            // Simpan variabel hasil kalkulasi interim agar terbaca oleh view
            $hasilUji->data_mentah = $variabel;
            $hasilUji->saveQuietly();

            if (!$duplo['pass']) {
                return [
                    'status' => 'gagal_duplo',
                    'nilai' => $averageM,
                    'message' => "Gagal Validasi Duplo! Selisih M% (" . number_format($absDiff, 4) . ") melampaui batas toleransi dinamis (" . number_format($toleransiDinamis, 4) . ")."
                ];
            }

            return ['status' => 'success', 'nilai' => $averageM];
        }

        if ($parameter->rumus_kalkulasi === 'CUSTOM_ASH') {
            // Pastikan data Duplo lengkap (M1, M2M1, M3)
            $required = ['M1_D1', 'M2M1_D1', 'M3_D1', 'M1_D2', 'M2M1_D2', 'M3_D2'];
            foreach ($required as $req) {
                if (!isset($variabel[$req]) || !is_numeric($variabel[$req])) {
                    return ['status' => 'error', 'message' => "Data {$req} tidak valid atau belum diisi."];
                }
            }

            $imCheck = $this->resolveDependency($hasilUji, $variabel, 'IM');
            if ($imCheck !== true) return $imCheck;

            $imValue = $variabel['IM'];
            if ($imValue == 100) return ['status' => 'error', 'message' => 'Nilai IM tidak boleh 100 (pembagian dengan nol).'];

            // Hitung ASH Dish 1
            $M2M1_1 = $variabel['M2M1_D1'];
            if ($M2M1_1 == 0) return ['status' => 'error', 'message' => "Massa sampel Dish 1 (M2-M1) tidak boleh 0."];
            $M2_1 = $variabel['M1_D1'] + $M2M1_1;
            $M3M1_1 = $variabel['M3_D1'] - $variabel['M1_D1'];
            $ASH_D1 = ($M3M1_1 / $M2M1_1) * 100;

            // Hitung ASH Dish 2
            $M2M1_2 = $variabel['M2M1_D2'];
            if ($M2M1_2 == 0) return ['status' => 'error', 'message' => "Massa sampel Dish 2 (M2-M1) tidak boleh 0."];
            $M2_2 = $variabel['M1_D2'] + $M2M1_2;
            $M3M1_2 = $variabel['M3_D2'] - $variabel['M1_D2'];
            $ASH_D2 = ($M3M1_2 / $M2M1_2) * 100;

            // Validasi Toleransi Duplo (Dinamis dari Database)
            $toleransi = $parameter->toleransi_duplo ?? 0.20;
            $duplo = $this->validateDuplo($ASH_D1, $ASH_D2, $toleransi);
            $absDiff = $duplo['diff'];
            $averageAdb = $duplo['avg'];
            
            // Hitung DB (Dry Basis) per pengujian lalu dirata-rata
            $correctionFactor = 100 / (100 - $imValue);
            $dbValue_D1 = round($ASH_D1 * $correctionFactor, 2);
            $dbValue_D2 = round($ASH_D2 * $correctionFactor, 2);
            $dbValue = ($dbValue_D1 + $dbValue_D2) / 2;

            // Simpan interim
            $variabel['M2_D1_Result'] = $M2_1;
            $variabel['M3M1_D1_Result'] = $M3M1_1;
            $variabel['ASH_D1_Result'] = $ASH_D1;
            $variabel['DB_D1_Result'] = $dbValue_D1;
            
            $variabel['M2_D2_Result'] = $M2_2;
            $variabel['M3M1_D2_Result'] = $M3M1_2;
            $variabel['ASH_D2_Result'] = $ASH_D2;
            $variabel['DB_D2_Result'] = $dbValue_D2;
            
            $variabel['Absolute_Diff'] = $absDiff;
            $variabel['Average_adb'] = $averageAdb;
            $variabel['DB_Result'] = $dbValue;
            
            $hasilUji->data_mentah = $variabel;
            $hasilUji->saveQuietly();

            if (!$duplo['pass']) {
                return [
                    'status' => 'gagal_duplo',
                    'nilai' => $dbValue,
                    'message' => "Gagal Validasi Duplo! Selisih ASH% ({$absDiff}) melebihi toleransi ({$toleransi})."
                ];
            }

            return ['status' => 'success', 'nilai' => $dbValue];
        }

        if ($parameter->rumus_kalkulasi === 'CUSTOM_VM') {
            // Pastikan data Duplo lengkap (M1, M2M1, M3)
            $required = ['M1_D1', 'M2M1_D1', 'M3_D1', 'M1_D2', 'M2M1_D2', 'M3_D2'];
            foreach ($required as $req) {
                if (!isset($variabel[$req]) || !is_numeric($variabel[$req])) {
                    return ['status' => 'error', 'message' => "Data {$req} tidak valid atau belum diisi."];
                }
            }

            $imCheck = $this->resolveDependency($hasilUji, $variabel, 'IM');
            if ($imCheck !== true) return $imCheck;

            $imValue = $variabel['IM'];
            if ($imValue == 100) return ['status' => 'error', 'message' => 'Nilai IM tidak boleh 100 (pembagian dengan nol).'];

            // Hitung VM Dish 1
            $M2M1_1 = $variabel['M2M1_D1'];
            if ($M2M1_1 == 0) return ['status' => 'error', 'message' => "Massa sampel Dish 1 (M2-M1) tidak boleh 0."];
            $M2_1 = $variabel['M1_D1'] + $M2M1_1;
            $M2M3_1 = $M2_1 - $variabel['M3_D1'];
            $Loss_1 = ($M2M3_1 / $M2M1_1) * 100;
            $VM_1 = $Loss_1 - $imValue;

            // Hitung VM Dish 2
            $M2M1_2 = $variabel['M2M1_D2'];
            if ($M2M1_2 == 0) return ['status' => 'error', 'message' => "Massa sampel Dish 2 (M2-M1) tidak boleh 0."];
            $M2_2 = $variabel['M1_D2'] + $M2M1_2;
            $M2M3_2 = $M2_2 - $variabel['M3_D2'];
            $Loss_2 = ($M2M3_2 / $M2M1_2) * 100;
            $VM_2 = $Loss_2 - $imValue;

            // Validasi Toleransi Duplo (Dinamis)
            $toleransi = $parameter->toleransi_duplo ?? 1.00;
            $duplo = $this->validateDuplo($VM_1, $VM_2, $toleransi);
            $absDiff = $duplo['diff'];
            $averageAdb = $duplo['avg'];
            
            // Hitung DB (Dry Basis)
            $dbValue = round($averageAdb * (100 / (100 - $imValue)), 2);

            // Simpan interim
            $variabel['M2_D1_Result'] = $M2_1;
            $variabel['M2M3_D1_Result'] = $M2M3_1;
            $variabel['Loss_D1_Result'] = $Loss_1;
            $variabel['VM_D1_Result'] = $VM_1;
            
            $variabel['M2_D2_Result'] = $M2_2;
            $variabel['M2M3_D2_Result'] = $M2M3_2;
            $variabel['Loss_D2_Result'] = $Loss_2;
            $variabel['VM_D2_Result'] = $VM_2;
            
            $variabel['Absolute_Diff'] = $absDiff;
            $variabel['Average_adb'] = $averageAdb;
            $variabel['DB_Result'] = $dbValue;
            
            $hasilUji->data_mentah = $variabel;
            $hasilUji->saveQuietly();

            if (!$duplo['pass']) {
                return [
                    'status' => 'gagal_duplo',
                    'nilai' => $dbValue,
                    'message' => "Gagal Validasi Duplo! Selisih VM% ({$absDiff}) melebihi toleransi ({$toleransi})."
                ];
            }

            return ['status' => 'success', 'nilai' => $dbValue];
        }

        if ($parameter->rumus_kalkulasi === 'CUSTOM_BIAS_VM') {
            // ===================================================
            // BIAS TEST VM — Up to 20 Pengulangan, Single Dish
            // ===================================================
            
            // Reference value diisi manual oleh analis
            if (!isset($variabel['reference_value']) || !is_numeric($variabel['reference_value'])) {
                return ['status' => 'error', 'message' => 'Reference Value harus diisi.'];
            }
            $referenceValue = (float) $variabel['reference_value'];

            // Hitung jumlah pengulangan yang terisi
            $totalReps = 0;
            for ($r = 1; $r <= 20; $r++) {
                if (isset($variabel["M1_R{$r}"]) && is_numeric($variabel["M1_R{$r}"])
                    && isset($variabel["M2M1_R{$r}"]) && is_numeric($variabel["M2M1_R{$r}"])
                    && isset($variabel["M3_R{$r}"]) && is_numeric($variabel["M3_R{$r}"])) {
                    $totalReps = $r;
                } else {
                    break;
                }
            }

            if ($totalReps < 2) {
                return ['status' => 'error', 'message' => 'Minimal 2 pengulangan harus diisi.'];
            }

            $imCheck = $this->resolveDependency($hasilUji, $variabel, 'IM');
            if ($imCheck !== true) return $imCheck;

            $imValue = (float) $variabel['IM'];
            $imAverage = isset($variabel['IM_Average']) && is_numeric($variabel['IM_Average'])
                ? (float) $variabel['IM_Average'] : $imValue;

            if ($imValue >= 100) {
                return ['status' => 'error', 'message' => 'Nilai IM tidak boleh >= 100.'];
            }

            // Hitung VM% per pengulangan
            $vmResults = [];
            for ($r = 1; $r <= $totalReps; $r++) {
                $M1 = (float) $variabel["M1_R{$r}"];
                $M2M1 = (float) $variabel["M2M1_R{$r}"];
                $M3 = (float) $variabel["M3_R{$r}"];

                if ($M2M1 == 0) {
                    return ['status' => 'error', 'message' => "Massa sampel pengulangan {$r} (M2-M1) tidak boleh 0."];
                }

                $M2 = $M1 + $M2M1;
                $M2M3 = $M2 - $M3;
                $Loss = ($M2M3 / $M2M1) * 100;
                $VM = $Loss - $imValue;
                $db = round($VM * (100 / (100 - $imValue)), 2);

                $vmResults[$r] = ['M2' => $M2, 'M2M3' => $M2M3, 'Loss' => $Loss, 'VM' => $VM, 'db' => $db];

                $variabel["M2_R{$r}_Result"] = $M2;
                $variabel["M2M3_R{$r}_Result"] = $M2M3;
                $variabel["Loss_R{$r}_Result"] = $Loss;
                $variabel["VM_R{$r}_Result"] = $VM;
                $variabel["DB_R{$r}_Result"] = $db;
            }

            // Hitung per pasangan (1&2, 3&4, ...)
            $avgDbValues = [];
            $toleransi = $parameter->toleransi_duplo ?? 1.00;

            for ($p = 1; $p <= $totalReps; $p += 2) {
                $p2 = $p + 1;
                if ($p2 > $totalReps) break;

                $vm1 = $vmResults[$p]['VM'];
                $vm2 = $vmResults[$p2]['VM'];
                $absDiff = abs($vm1 - $vm2);
                $isYes = $absDiff < $toleransi;
                $avgAdb = ($vm1 + $vm2) / 2;
                $avgDb = round($avgAdb * (100 / (100 - $imAverage)), 2);

                $pi = intdiv($p - 1, 2) + 1;
                $variabel["Pair{$pi}_AbsDiff"] = $absDiff;
                $variabel["Pair{$pi}_Eval"] = $isYes ? 'YES' : 'NO';
                $variabel["Pair{$pi}_AvgAdb"] = $avgAdb;
                $variabel["Pair{$pi}_AvgDb"] = $avgDb;

                $avgDbValues[] = $avgDb;
            }

            $variabel['total_reps'] = $totalReps;
            $variabel['total_pairs'] = count($avgDbValues);

            // T-Test
            $n = count($avgDbValues);
            if ($n >= 2) {
                $meanDb = array_sum($avgDbValues) / $n;
                $sumSq = 0;
                foreach ($avgDbValues as $v) $sumSq += pow($v - $meanDb, 2);
                $sdDb = sqrt($sumSq / ($n - 1));
                $tHitung = $sdDb > 0 ? abs($meanDb - $referenceValue) / ($sdDb / sqrt($n)) : 0;
                $tTabel = $this->getTCritical($n - 1);
                $conclusion = $tHitung < $tTabel ? 'Tidak Ada Bias' : 'Ada Bias';

                $variabel['t_test'] = [
                    'mean' => round($meanDb, 4), 'sd' => round($sdDb, 4),
                    'n' => $n, 'df' => $n - 1, 'reference' => $referenceValue,
                    't_hitung' => round($tHitung, 4), 't_tabel' => round($tTabel, 4),
                    'alpha' => 0.05, 'conclusion' => $conclusion,
                ];
                $variabel['final_mean_db'] = round($meanDb, 2);
            }

            $hasilUji->data_mentah = $variabel;
            $hasilUji->saveQuietly();

            $finalValue = isset($variabel['final_mean_db']) ? $variabel['final_mean_db'] : 0;
            return ['status' => 'success', 'nilai' => $finalValue];
        }

        if ($parameter->rumus_kalkulasi === 'CUSTOM_TS') {
            // Pastikan data Duplo lengkap
            $required = ['Massa_D1', 'TS_adb_D1', 'Massa_D2', 'TS_adb_D2'];
            foreach ($required as $req) {
                if (!isset($variabel[$req]) || !is_numeric($variabel[$req])) {
                    return ['status' => 'error', 'message' => "Data {$req} tidak valid atau belum diisi."];
                }
            }

            $imCheck = $this->resolveDependency($hasilUji, $variabel, 'IM');
            if ($imCheck !== true) return $imCheck;

            $imValue = $variabel['IM'];
            if ($imValue == 100) return ['status' => 'error', 'message' => 'Nilai IM tidak boleh 100 (pembagian dengan nol).'];

            $ts1 = (float) $variabel['TS_adb_D1'];
            $ts2 = (float) $variabel['TS_adb_D2'];

            // Validasi Toleransi Duplo (Dinamis dari Master Data, default 0.05)
            $toleransi = $parameter->toleransi_duplo ?? 0.05;
            $duplo = $this->validateDuplo($ts1, $ts2, $toleransi);
            $absDiff = $duplo['diff'];
            $averageAdb = $duplo['avg'];
            
            // Hitung DB (Dry Basis)
            $dbValue = $averageAdb * (100 / (100 - $imValue));

            // Simpan interim
            $variabel['Absolute_Diff'] = $absDiff;
            $variabel['Average_adb'] = $averageAdb;
            $variabel['DB_Result'] = $dbValue;
            
            $hasilUji->data_mentah = $variabel;
            $hasilUji->saveQuietly();

            if (!$duplo['pass']) {
                return [
                    'status' => 'gagal_duplo',
                    'nilai' => $dbValue,
                    'message' => "Gagal Validasi Duplo! Selisih TS% ({$absDiff}) melebihi toleransi ({$toleransi})."
                ];
            }

            return ['status' => 'success', 'nilai' => $dbValue];
        }

        if ($parameter->rumus_kalkulasi === 'CUSTOM_CV') {
            // Pastikan data Duplo lengkap
            $required = [
                'Vessel_D1', 'Call_ID_D1', 'Crucible_D1', 'Massa_D1', 'Primary_D1', 'Ee_D1', 't_D1', 'Titrant_D1', 'Fuse_D1', 'Final_adb_D1',
                'Vessel_D2', 'Call_ID_D2', 'Crucible_D2', 'Massa_D2', 'Primary_D2', 'Ee_D2', 't_D2', 'Titrant_D2', 'Fuse_D2', 'Final_adb_D2'
            ];
            
            foreach ($required as $req) {
                if (!isset($variabel[$req]) || trim($variabel[$req]) === '') {
                    return ['status' => 'error', 'message' => "Data {$req} tidak valid atau belum diisi."];
                }
            }

            $imCheck = $this->resolveDependency($hasilUji, $variabel, 'IM');
            if ($imCheck !== true) return $imCheck;

            $tsCheck = $this->resolveDependency($hasilUji, $variabel, 'Total Sulfur (TS)');
            if ($tsCheck !== true) return $tsCheck;

            $imValue = $variabel['IM'];
            if ($imValue == 100) return ['status' => 'error', 'message' => 'Nilai IM tidak boleh 100 (pembagian dengan nol).'];

            $cv1 = (float) $variabel['Final_adb_D1'];
            $cv2 = (float) $variabel['Final_adb_D2'];

            // Validasi Toleransi Duplo (Dinamis dari Master Data, default 50.0)
            $toleransi = $parameter->toleransi_duplo ?? 50.0;
            $duplo = $this->validateDuplo($cv1, $cv2, $toleransi);
            $absDiff = $duplo['diff'];
            $averageAdb = $duplo['avg'];
            
            // Hitung DB (Dry Basis)
            $dbValue = $averageAdb * (100 / (100 - $imValue));

            // Simpan interim
            $variabel['Absolute_Diff'] = $absDiff;
            $variabel['Average_adb'] = $averageAdb;
            $variabel['DB_Result'] = $dbValue;
            
            $hasilUji->data_mentah = $variabel;
            $hasilUji->saveQuietly();

            if (!$duplo['pass']) {
                return [
                    'status' => 'gagal_duplo',
                    'nilai' => $dbValue,
                    'message' => "Gagal Validasi Duplo! Selisih CV ({$absDiff}) melebihi toleransi ({$toleransi})."
                ];
            }

            return ['status' => 'success', 'nilai' => $dbValue];
        }

        if ($parameter->rumus_kalkulasi === 'CUSTOM_AFT') {
            $required = [
                'Atmosphere', 
                'IDT_D1', 'ST_D1', 'HT_D1', 'FT_D1',
                'IDT_D2', 'ST_D2', 'HT_D2', 'FT_D2'
            ];
            foreach ($required as $req) {
                if (!isset($variabel[$req]) || trim($variabel[$req]) === '') {
                    return ['status' => 'error', 'message' => "Data {$req} tidak valid atau belum diisi."];
                }
            }

            $idt1 = (float)$variabel['IDT_D1']; $idt2 = (float)$variabel['IDT_D2'];
            $st1  = (float)$variabel['ST_D1'];  $st2  = (float)$variabel['ST_D2'];
            $ht1  = (float)$variabel['HT_D1'];  $ht2  = (float)$variabel['HT_D2'];
            $ft1  = (float)$variabel['FT_D1'];  $ft2  = (float)$variabel['FT_D2'];

            $toleransi = $parameter->toleransi_duplo ?? 50.0;
            $duploIDT = $this->validateDuplo($idt1, $idt2, $toleransi);
            $duploST  = $this->validateDuplo($st1, $st2, $toleransi);
            $duploHT  = $this->validateDuplo($ht1, $ht2, $toleransi);
            $duploFT  = $this->validateDuplo($ft1, $ft2, $toleransi);

            $variabel['Abs_IDT'] = $duploIDT['diff']; $variabel['Avg_IDT'] = $duploIDT['avg'];
            $variabel['Abs_ST']  = $duploST['diff'];  $variabel['Avg_ST']  = $duploST['avg'];
            $variabel['Abs_HT']  = $duploHT['diff'];  $variabel['Avg_HT']  = $duploHT['avg'];
            $variabel['Abs_FT']  = $duploFT['diff'];  $variabel['Avg_FT']  = $duploFT['avg'];

            $hasilUji->data_mentah = $variabel;
            $hasilUji->saveQuietly();

            if (!$duploIDT['pass'] || !$duploST['pass'] || !$duploHT['pass'] || !$duploFT['pass']) {
                return [
                    'status' => 'gagal_duplo',
                    'nilai' => 0,
                    'message' => "Gagal Validasi Duplo! Ada selisih suhu yang melebihi toleransi ({$toleransi} ℃)."
                ];
            }

            return ['status' => 'success', 'nilai' => 0]; // AFT uses data_mentah for multi-values
        }

        if ($parameter->rumus_kalkulasi === 'CUSTOM_CHN') {
            $required = [
                'Weight_D1', 'C_D1', 'H_D1', 'N_D1',
                'Weight_D2', 'C_D2', 'H_D2', 'N_D2'
            ];
            foreach ($required as $req) {
                if (!isset($variabel[$req]) || trim($variabel[$req]) === '') {
                    return ['status' => 'error', 'message' => "Data {$req} tidak valid atau belum diisi."];
                }
            }

            $c1 = (float)$variabel['C_D1']; $c2 = (float)$variabel['C_D2'];
            $h1 = (float)$variabel['H_D1']; $h2 = (float)$variabel['H_D2'];
            $n1 = (float)$variabel['N_D1']; $n2 = (float)$variabel['N_D2'];

            $toleransi = $parameter->toleransi_duplo ?? 0.5;
            $duploC = $this->validateDuplo($c1, $c2, $toleransi);
            $duploH = $this->validateDuplo($h1, $h2, $toleransi);
            $duploN = $this->validateDuplo($n1, $n2, $toleransi);

            $variabel['Abs_C'] = $duploC['diff']; $variabel['Avg_C'] = $duploC['avg'];
            $variabel['Abs_H'] = $duploH['diff']; $variabel['Avg_H'] = $duploH['avg'];
            $variabel['Abs_N'] = $duploN['diff']; $variabel['Avg_N'] = $duploN['avg'];

            $hasilUji->data_mentah = $variabel;
            $hasilUji->saveQuietly();

            if (!$duploC['pass'] || !$duploH['pass'] || !$duploN['pass']) {
                return [
                    'status' => 'gagal_duplo',
                    'nilai' => 0,
                    'message' => "Gagal Validasi Duplo! Ada selisih parameter CHN yang melebihi toleransi ({$toleransi}%)."
                ];
            }

            return ['status' => 'success', 'nilai' => 0];
        }

        // Mengecek Dependensi (Cross-Parameter)
        $dependensi = $parameter->dependensi_parameter ?? [];
        $missingDependencies = [];

        foreach ($dependensi as $depName) {
            // Cari Parameter dependensi berdasarkan nama_parameter
            $depParam = ParameterUji::where('nama_parameter', $depName)->first();
            
            if (!$depParam) {
                Log::warning("Parameter dependensi '{$depName}' tidak ditemukan di database.");
                $missingDependencies[] = $depName;
                continue;
            }

            // Cari apakah di kegiatan yang sama sudah ada Hasil Uji untuk parameter tersebut yang TIDAK pending
            $depHasil = HasilUji::where('kegiatan_id', $hasilUji->kegiatan_id)
                ->where('parameter_uji_id', $depParam->parameter_uji_id)
                ->whereNotNull('nilai_hasil')
                ->where('status_berketerimaan', '!=', 'pending')
                ->first();

            if (!$depHasil) {
                $missingDependencies[] = $depName;
            } else {
                // Suntikkan nilai hasil dependensi ke dalam variabel kalkulasi
                $varName = $depName; // Nama variabel di rumus harus persis sama dengan nama_parameter di database (e.g., 'IM', 'TS')
                $variabel[$varName] = $depHasil->nilai_hasil;
            }
        }

        if (count($missingDependencies) > 0) {
            return [
                'status' => 'pending',
                'message' => 'Menunggu Data: ' . implode(', ', $missingDependencies)
            ];
        }

        // Semua dependensi terpenuhi, lakukan kalkulasi
        try {
            // Cek apakah ada data Duplo (D1 & D2)
            $isDuplo = false;
            foreach ($variabel as $k => $v) {
                if (str_ends_with($k, '_D1')) {
                    $isDuplo = true;
                    break;
                }
            }

            if ($isDuplo) {
                // Ekstrak var D1 dan D2
                $varD1 = [];
                $varD2 = [];
                foreach ($variabel as $k => $v) {
                    if (str_ends_with($k, '_D1')) {
                        $varName = substr($k, 0, -3);
                        $varD1[$varName] = $v;
                    } elseif (str_ends_with($k, '_D2')) {
                        $varName = substr($k, 0, -3);
                        $varD2[$varName] = $v;
                    } else {
                        // Variabel umum (seperti dependensi IM, TS, atau Atm)
                        $varD1[$k] = $v;
                        $varD2[$k] = $v;
                    }
                }

                // Kalkulasi nilai D1 dan D2
                $nilai1 = (float) $this->expressionLanguage->evaluate($parameter->rumus_kalkulasi, $varD1);
                $nilai2 = (float) $this->expressionLanguage->evaluate($parameter->rumus_kalkulasi, $varD2);
                
                $average = ($nilai1 + $nilai2) / 2;
                $absDiff = abs($nilai1 - $nilai2);

                // Tambahkan hasil ke data mentah
                $variabel['Nilai_D1'] = $nilai1;
                $variabel['Nilai_D2'] = $nilai2;
                $variabel['Average_Result'] = $average; // Untuk rumus toleransi dinamis jika butuh
                $variabel['Absolute_Diff'] = $absDiff;
                $hasilUji->data_mentah = $variabel;
                $hasilUji->saveQuietly();

                // Validasi Duplo
                if (!empty($parameter->toleransi_duplo)) {
                    // Evaluasi toleransi_duplo menggunakan ExpressionLanguage karena bisa dinamis misal `0.09 + (0.1 * Average_Result)`
                    $toleransiFormula = $parameter->toleransi_duplo;
                    try {
                        $toleransi = (float) $this->expressionLanguage->evaluate($toleransiFormula, $variabel);
                    } catch (\Throwable $e) {
                        // Jika gagal parse (mungkin angka statis), fallback
                        $toleransi = (float) $toleransiFormula;
                    }

                    if ($absDiff >= $toleransi) {
                        return [
                            'status' => 'gagal_duplo',
                            'nilai' => $average,
                            'message' => "Gagal Validasi Duplo! Selisih (" . number_format($absDiff, 4) . ") melampaui batas toleransi (" . number_format($toleransi, 4) . ")."
                        ];
                    }
                }

                return [
                    'status' => 'success',
                    'nilai' => $average
                ];
            } else {
                // Single test
                $nilaiHasil = (float) $this->expressionLanguage->evaluate($parameter->rumus_kalkulasi, $variabel);
                return [
                    'status' => 'success',
                    'nilai' => $nilaiHasil
                ];
            }
        } catch (\Throwable $e) {
            Log::error("Gagal mengkalkulasi rumus QC untuk HasilUji ID {$hasilUji->hasil_uji_id}: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Gagal mengkalkulasi: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Helper untuk mengambil dependensi (contoh: IM, Total Sulfur (TS))
     * Mengembalikan true jika dependensi ditemukan dan diset, atau array error/pending jika gagal.
     */
    private function resolveDependency(HasilUji $hasilUji, array &$variabel, string $depName)
    {
        if (!isset($variabel[$depName]) || !is_numeric($variabel[$depName])) {
            $depParam = ParameterUji::where('nama_parameter', $depName)->first();
            if ($depParam) {
                $depHasil = HasilUji::where('kegiatan_id', $hasilUji->kegiatan_id)
                    ->where('parameter_uji_id', $depParam->parameter_uji_id)
                    ->whereNotNull('nilai_hasil')
                    ->where('status_berketerimaan', '!=', 'pending')
                    ->where('status_berketerimaan', '!=', 'gagal_duplo')
                    ->first();
                    
                if (!$depHasil) {
                    return [
                        'status' => 'pending',
                        'message' => 'Menunggu Data: ' . $depName
                    ];
                }
                $variabel[$depName] = $depHasil->nilai_hasil;
            } else {
                return ['status' => 'error', 'message' => "Parameter {$depName} tidak ditemukan di sistem."];
            }
        }
        return true;
    }

    /**
     * Helper untuk validasi duplo (selisih dan rata-rata)
     */
    private function validateDuplo(float $dish1, float $dish2, float $tolerance): array
    {
        $diff = abs($dish1 - $dish2);
        $avg = ($dish1 + $dish2) / 2;
        $pass = $diff <= $tolerance;
        return compact('diff', 'avg', 'pass');
    }

    /**
     * Menyapu semua HasilUji yang berstatus pending pada suatu kegiatan,
     * dan mencoba mengkalkulasikannya ulang karena mungkin dependensinya sudah masuk.
     */
    public function resolvePendingResults(int $kegiatanId): int
    {
        $pendingResults = HasilUji::where('kegiatan_id', $kegiatanId)
            ->where('status_berketerimaan', 'pending')
            ->get();

        $resolvedCount = 0;

        foreach ($pendingResults as $pending) {
            $calcResult = $this->calculate($pending);
            
            if ($calcResult['status'] === 'success') {
                $pending->nilai_hasil = $calcResult['nilai'];
                
                // Lakukan evaluasi Westgard karena nilai akhirnya sudah ketemu
                $evalResult = $this->westgardService->evaluate($pending->nilai_hasil, $pending->parameterUji);
                
                $pending->status_berketerimaan = $evalResult['status'];
                $pending->kode_aturan_dilanggar = $evalResult['kode'] ?? null;
                $pending->z_score = $evalResult['z_score'];
                
                $pending->save();
                
                // Jika setelah kalkulasi ternyata Outlier, kirim notifikasi dan buat riwayat tindak lanjut.
                if ($evalResult['status'] === 'outlier') {
                    $this->handleOutlier($pending, $evalResult);
                }

                $resolvedCount++;
            }
        }

        return $resolvedCount;
    }

    /**
     * Memproses outlier (membuat tindak lanjut & notifikasi)
     * Sama seperti yang ada di HasilUjiController, ditarik ke sini agar bisa di-trigger background.
     */
    protected function handleOutlier(HasilUji $hasilUji, array $evalResult)
    {
        $kode = $evalResult['kode'] ?? '';
        $pesanAturan = $kode ? "[{$kode}] " : "";
        $pesanAturan .= "Nilai " . number_format($hasilUji->nilai_hasil, 4) . " menyimpang. " . ($evalResult['pesan'] ?? '');

        \App\Models\RiwayatTindakLanjut::create([
            'hasil_uji_id' => $hasilUji->hasil_uji_id,
            'status_tindak_lanjut' => 'belum_ditindaklanjuti',
            'catatan_investigasi' => "Otomatis dibuat oleh sistem (Auto-Resolved). " . $pesanAturan,
            'ditindaklanjuti_oleh' => $hasilUji->diinput_oleh, // Set awal ke pembuat
        ]);

        // Broadcast Notifikasi Massal
        $kegiatan = $hasilUji->kegiatan;
        $parameter = $hasilUji->parameterUji;
        $usersToNotify = \App\Models\User::whereHas('role', function ($q) {
            $q->whereIn('nama_role', [
                \App\Enums\PeranPengguna::ANALIS->value,
                \App\Enums\PeranPengguna::KOORDINATOR_LAB->value
            ]);
        })->get();

        $pesanNotif = "⚠️ Out-of-Control {$pesanAturan} pada kegiatan {$kegiatan->kode_sampel}, parameter {$parameter->nama_parameter}. Tindak lanjut telah dibuat.";

        $notifData = [];
        foreach ($usersToNotify as $user) {
            $notifData[] = [
                'users_id' => $user->users_id,
                'jenis_notifikasi' => 'qc',
                'pesan' => $pesanNotif,
                'is_read' => false,
                'created_at' => now(),
            ];
        }

        if (count($notifData) > 0) {
            \Illuminate\Support\Facades\DB::table('notifikasi')->insert($notifData);
        }
    }

    /**
     * Lookup t-critical value (two-tailed, α=0.05) by degrees of freedom.
     */
    protected function getTCritical(int $df): float
    {
        // Standard t-distribution table (two-tailed, α = 0.05)
        $tTable = [
            1 => 12.706, 2 => 4.303, 3 => 3.182, 4 => 2.776, 5 => 2.571,
            6 => 2.447, 7 => 2.365, 8 => 2.306, 9 => 2.262, 10 => 2.228,
            11 => 2.201, 12 => 2.179, 13 => 2.160, 14 => 2.145, 15 => 2.131,
            16 => 2.120, 17 => 2.110, 18 => 2.101, 19 => 2.093, 20 => 2.086,
            25 => 2.060, 30 => 2.042, 40 => 2.021, 60 => 2.000, 120 => 1.980,
        ];

        if (isset($tTable[$df])) return $tTable[$df];

        // Interpolasi sederhana untuk df yang tidak ada di tabel
        $keys = array_keys($tTable);
        $lower = 1; $upper = 120;
        foreach ($keys as $k) {
            if ($k <= $df) $lower = $k;
            if ($k >= $df) { $upper = $k; break; }
        }
        if ($lower === $upper) return $tTable[$lower];
        
        $ratio = ($df - $lower) / ($upper - $lower);
        return $tTable[$lower] + $ratio * ($tTable[$upper] - $tTable[$lower]);
    }
}
