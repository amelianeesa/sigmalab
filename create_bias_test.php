<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ParameterUji;

$paramName = 'Bias Test VM (Pt)';
$exists = ParameterUji::where('nama_parameter', $paramName)->exists();

if (!$exists) {
    // Generate the array for 20 reps
    $varInput = ["reference_value", "IM_Average"];
    for ($i = 1; $i <= 20; $i++) {
        $varInput[] = "M1_R{$i}";
        $varInput[] = "M2M1_R{$i}";
        $varInput[] = "M3_R{$i}";
    }

    ParameterUji::create([
        'nama_parameter' => $paramName,
        'kategori_parameter' => 'Verifikasi Metode',
        'satuan' => '%',
        'variabel_input' => $varInput,
        'rumus_kalkulasi' => 'CUSTOM_BIAS_VM',
        'dependensi_parameter' => ['IM'],
        'toleransi_duplo' => 1.00,
        'nilai_acuan' => 0,
        'batas_bawah' => 0,
        'batas_atas' => 0,
        'lcl' => null,
        'uwl_bawah' => null,
        'mean' => null,
        'uwl_atas' => null,
        'ucl' => null,
        'sd' => null
    ]);
    
    echo "Parameter '$paramName' created successfully.\n";
} else {
    // Update it if it exists to make sure it has the new CUSTOM_BIAS_VM formula
    $param = ParameterUji::where('nama_parameter', $paramName)->first();
    
    $varInput = ["reference_value", "IM_Average"];
    for ($i = 1; $i <= 20; $i++) {
        $varInput[] = "M1_R{$i}";
        $varInput[] = "M2M1_R{$i}";
        $varInput[] = "M3_R{$i}";
    }
    
    $param->update([
        'variabel_input' => $varInput,
        'rumus_kalkulasi' => 'CUSTOM_BIAS_VM',
        'dependensi_parameter' => ['IM'],
    ]);
    echo "Parameter '$paramName' updated successfully.\n";
}
