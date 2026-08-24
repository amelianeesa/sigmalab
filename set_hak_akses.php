<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$modul = App\Models\Modul::where('kode_modul', 'audit_log')->first();
if (!$modul) {
    $modul = App\Models\Modul::create(['kode_modul' => 'audit_log', 'nama_modul' => 'Audit Log']);
}

App\Models\HakAkses::where('modul_id', $modul->modul_id)->delete();

$roles = App\Models\Role::whereIn('nama_role', [
    'Koordinator Laboratorium', 
    'Kabid Dukungan Bisnis', 
    'Kabid Inspeksi dan Solusi Perdagangan',
    'Admin Aplikasi'
])->get();

foreach($roles as $role) {
    App\Models\HakAkses::create([
        'role_id' => $role->roles_id, 
        'modul_id' => $modul->modul_id, 
        'level_akses' => 'lihat'
    ]);
}

echo 'Berhasil set hak akses';
