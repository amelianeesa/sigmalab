<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ParameterUji;

$im = ParameterUji::where('nama_parameter', 'IM')->first();
if($im) {
    $im->rumus_kalkulasi = '((m2 - m3) / m2) * 100';
    $im->toleransi_duplo = '0.09 + (0.1 * AVG)';
    $im->langkah_kalkulasi = [
        ['var' => 'm2', 'rumus' => 'm1 + a'],
        ['var' => 'b', 'rumus' => 'm2 - m3']
    ];
    $im->save();
}

$ash = ParameterUji::where('nama_parameter', 'ASH')->first();
if($ash) {
    $ash->rumus_kalkulasi = '(m3m1 / m2m1) * 100';
    $ash->toleransi_duplo = '0.09 + (0.1 * AVG)';
    $ash->langkah_kalkulasi = [
        ['var' => 'm2m1', 'rumus' => 'm2 - m1'],
        ['var' => 'm3m1', 'rumus' => 'm3 - m1']
    ];
    $ash->save();
}

$vm = ParameterUji::where('nama_parameter', 'VM')->first();
if($vm) {
    $vm->rumus_kalkulasi = 'loss - im';
    $vm->toleransi_duplo = '0.09 + (0.1 * AVG)';
    $vm->langkah_kalkulasi = [
        ['var' => 'm2m1', 'rumus' => 'm2 - m1'],
        ['var' => 'm2m3', 'rumus' => 'm2 - m3'],
        ['var' => 'loss', 'rumus' => '(m2m3 / m2m1) * 100']
    ];
    $vm->save();
}

$ts = ParameterUji::where('nama_parameter', 'TS')->first();
if($ts) {
    $ts->rumus_kalkulasi = 'mass';
    $ts->toleransi_duplo = '0.09 + (0.1 * AVG)';
    $ts->langkah_kalkulasi = [];
    $ts->save();
}

$cv = ParameterUji::where('nama_parameter', 'CV')->first();
if($cv) {
    $cv->rumus_kalkulasi = '(pr - (14.3 * 0.0699 * vt) - (2.3 * lf) - (13.2 * ts * sm)) / sm';
    $cv->toleransi_duplo = '0.09 + (0.1 * AVG)';
    $cv->langkah_kalkulasi = [];
    $cv->save();
}
echo "Done";
