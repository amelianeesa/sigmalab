<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$vm = \App\Models\ParameterUji::where('nama_parameter', 'VM')->first();
if (!$vm) {
    $vm = new \App\Models\ParameterUji();
    $vm->nama_parameter = 'VM';
    $vm->satuan = '%';
    $vm->nilai_acuan = '45.00';
    $vm->batas_bawah = 0;
    $vm->batas_atas = 100;
}

$vm->status_aktif = true;
$vm->mean = 45.0000;
$vm->sd = 0.5000;
$vm->rumus_kalkulasi = 'CUSTOM_VM';
$vm->toleransi_duplo = 1.00;
$vm->lcl = 45.0000 - 1.5;
$vm->uwl_bawah = 45.0000 - 1.0;
$vm->uwl_atas = 45.0000 + 1.0;
$vm->ucl = 45.0000 + 1.5;

$vm->save();

echo "VM Parameter Created/Updated Successfully.";
