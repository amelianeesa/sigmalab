<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
foreach(\App\Models\ParameterUji::all() as $p) {
    echo "ID: " . $p->parameter_uji_id . " | Name: " . $p->nama_parameter . " | Mean: " . $p->mean . " | SD: " . $p->sd . PHP_EOL;
}
