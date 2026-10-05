<?php 
require 'vendor/autoload.php'; 
$app = require_once 'bootstrap/app.php'; 
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap(); 
$params = App\Models\QcUjiBandingParameter::orderBy('updated_at', 'desc')->take(10)->get(); 
foreach($params as $p) { 
    echo $p->parameterUji->nama_parameter . ' | TV: ' . $p->target_vendor . ' | SDPA: ' . $p->sdpa . ' | Z: ' . $p->z_score . PHP_EOL; 
}
