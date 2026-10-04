<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Http\Request;

// Mock request
$request = Request::create('/test', 'POST', [
    'params' => [
        87 => [
            'lab_value' => '',
            'target_vendor' => '25.92',
            'sdpa' => '0.41'
        ]
    ]
]);

$controller = new App\Http\Controllers\QcUjiBandingController();
try {
    $controller->evaluasiStore($request, 3);
    echo "SUCCESS\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

$p = App\Models\QcUjiBandingParameter::find(87);
echo "QC 3 TM TV: " . $p->target_vendor . "\n";
