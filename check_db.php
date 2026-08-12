<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$role = DB::table('roles')->where('nama_role', 'HR & GA')->first();
$modul = DB::table('modul')->where('kode_modul', 'pengadaan')->first();
$akses = DB::table('hak_akses')->where('role_id', $role->roles_id)->where('modul_id', $modul->modul_id)->first();
var_dump($akses);

$allAccess = DB::table('hak_akses')->where('role_id', $role->roles_id)->get();
echo "\nAll access for HR & GA:\n";
foreach($allAccess as $a) {
    $m = DB::table('modul')->where('modul_id', $a->modul_id)->first();
    echo $m->kode_modul . " => " . $a->level_akses . "\n";
}
