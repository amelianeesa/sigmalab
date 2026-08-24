<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
foreach(\App\Models\ParameterUji::whereIn('nama_parameter', ['IM', 'ASH', 'VM'])->get() as $p) {
    echo $p->nama_parameter . ': Mean=' . $p->mean . ', SD=' . $p->sd . ', Tol=' . $p->toleransi_duplo . PHP_EOL;
}
