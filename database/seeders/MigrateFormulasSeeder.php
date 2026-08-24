<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ParameterUji;

class MigrateFormulasSeeder extends Seeder
{
    public function run()
    {
        // 1. IM
        $im = ParameterUji::where('nama_parameter', 'IM')->first();
        if ($im) {
            $im->update([
                'toleransi_duplo' => '0.09 + (0.1 * ((M_D1_Result + M_D2_Result) / 2))',
                'langkah_kalkulasi' => [
                    ['var' => 'M2_D1_Result', 'rumus' => 'M1_D1 + A_D1'],
                    ['var' => 'M_D1_Result', 'rumus' => '((A_D1 - (M3_D1 - M1_D1)) / A_D1) * 100'],
                    ['var' => 'M2_D2_Result', 'rumus' => 'M1_D2 + A_D2'],
                    ['var' => 'M_D2_Result', 'rumus' => '((A_D2 - (M3_D2 - M1_D2)) / A_D2) * 100'],
                    ['var' => 'Absolute_Diff', 'rumus' => 'abs(M_D1_Result - M_D2_Result)'],
                    ['var' => 'Nilai_Akhir', 'rumus' => '(M_D1_Result + M_D2_Result) / 2']
                ]
            ]);
        }

        // 2. ASH
        $ash = ParameterUji::where('nama_parameter', 'ASH')->first();
        if ($ash) {
            $ash->update([
                'toleransi_duplo' => '0.20',
                'langkah_kalkulasi' => [
                    ['var' => 'M2_D1_Result', 'rumus' => 'M1_D1 + M2M1_D1'],
                    ['var' => 'M3M1_D1_Result', 'rumus' => 'M3_D1 - M1_D1'],
                    ['var' => 'ASH_D1_Result', 'rumus' => '(M3M1_D1_Result / M2M1_D1) * 100'],
                    ['var' => 'DB_D1_Result', 'rumus' => 'ASH_D1_Result * (100 / (100 - IM))'],
                    ['var' => 'M2_D2_Result', 'rumus' => 'M1_D2 + M2M1_D2'],
                    ['var' => 'M3M1_D2_Result', 'rumus' => 'M3_D2 - M1_D2'],
                    ['var' => 'ASH_D2_Result', 'rumus' => '(M3M1_D2_Result / M2M1_D2) * 100'],
                    ['var' => 'DB_D2_Result', 'rumus' => 'ASH_D2_Result * (100 / (100 - IM))'],
                    ['var' => 'Absolute_Diff', 'rumus' => 'abs(ASH_D1_Result - ASH_D2_Result)'],
                    ['var' => 'Average_adb', 'rumus' => '(ASH_D1_Result + ASH_D2_Result) / 2'],
                    ['var' => 'Nilai_Akhir', 'rumus' => '(DB_D1_Result + DB_D2_Result) / 2']
                ]
            ]);
        }

        // 3. VM
        $vm = ParameterUji::where('nama_parameter', 'VM')->first();
        if ($vm) {
            $vm->update([
                'toleransi_duplo' => '1.00',
                'langkah_kalkulasi' => [
                    ['var' => 'M2_D1_Result', 'rumus' => 'M1_D1 + M2M1_D1'],
                    ['var' => 'M2M3_D1_Result', 'rumus' => 'M2_D1_Result - M3_D1'],
                    ['var' => 'Loss_D1_Result', 'rumus' => '(M2M3_D1_Result / M2M1_D1) * 100'],
                    ['var' => 'VM_D1_Result', 'rumus' => 'Loss_D1_Result - IM'],
                    ['var' => 'M2_D2_Result', 'rumus' => 'M1_D2 + M2M1_D2'],
                    ['var' => 'M2M3_D2_Result', 'rumus' => 'M2_D2_Result - M3_D2'],
                    ['var' => 'Loss_D2_Result', 'rumus' => '(M2M3_D2_Result / M2M1_D2) * 100'],
                    ['var' => 'VM_D2_Result', 'rumus' => 'Loss_D2_Result - IM'],
                    ['var' => 'Absolute_Diff', 'rumus' => 'abs(VM_D1_Result - VM_D2_Result)'],
                    ['var' => 'Average_adb', 'rumus' => '(VM_D1_Result + VM_D2_Result) / 2'],
                    ['var' => 'Nilai_Akhir', 'rumus' => 'Average_adb * (100 / (100 - IM))']
                ]
            ]);
        }
    }
}
