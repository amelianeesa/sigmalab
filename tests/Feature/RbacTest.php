<?php

namespace Tests\Feature;

use App\Models\Modul;
use App\Models\Role;
use App\Models\User;
use App\Models\ParameterUji;
use App\Models\HakAkses;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class RbacTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    public function test_full_level_passes_lihat_check()
    {
        $role = Role::create(['nama_role' => 'Admin Lab']);
        $modul = Modul::create(['kode_modul' => 'alat', 'nama_modul' => 'Alat']);
        
        HakAkses::create([
            'role_id' => $role->roles_id,
            'modul_id' => $modul->modul_id,
            'level_akses' => 'full'
        ]);

        $user = User::factory()->create(['role_id' => $role->roles_id]);
        $service = app(\App\Services\PermissionService::class);

        $this->assertTrue($service->userHasAccess($user, 'alat', 'lihat'));
        $this->assertTrue($service->userHasAccess($user, 'alat', 'tambah_ubah'));
        $this->assertTrue($service->userHasAccess($user, 'alat', 'full'));
    }

    public function test_lihat_level_fails_tambah_ubah_check()
    {
        $role = Role::create(['nama_role' => 'Koordinator Laboratorium']);
        $modul = Modul::create(['kode_modul' => 'alat', 'nama_modul' => 'Alat']);
        
        HakAkses::create([
            'role_id' => $role->roles_id,
            'modul_id' => $modul->modul_id,
            'level_akses' => 'lihat'
        ]);

        $user = User::factory()->create(['role_id' => $role->roles_id]);
        $service = app(\App\Services\PermissionService::class);

        $this->assertTrue($service->userHasAccess($user, 'alat', 'lihat'));
        $this->assertFalse($service->userHasAccess($user, 'alat', 'tambah_ubah'));
        $this->assertFalse($service->userHasAccess($user, 'alat', 'full'));
    }

    public function test_user_without_access_row_fails_all_checks()
    {
        $role = Role::create(['nama_role' => 'Koordinator Laboratorium']);
        // No HakAkses record created for this role and module
        
        $user = User::factory()->create(['role_id' => $role->roles_id]);
        $service = app(\App\Services\PermissionService::class);

        $this->assertFalse($service->userHasAccess($user, 'qc', 'lihat'));
        $this->assertFalse($service->userHasAccess($user, 'qc', 'tambah_ubah'));
        $this->assertFalse($service->userHasAccess($user, 'qc', 'full'));
    }

    public function test_parameter_uji_policy_authorization()
    {
        $roleAnalis = Role::create(['nama_role' => 'Analis']);
        $roleKoor = Role::create(['nama_role' => 'Koordinator Laboratorium']);
        $modulParamUji = Modul::create(['kode_modul' => 'parameter_uji', 'nama_modul' => 'Parameter Uji']);
        
        HakAkses::create([
            'role_id' => $roleAnalis->roles_id,
            'modul_id' => $modulParamUji->modul_id,
            'level_akses' => 'tambah_ubah'
        ]);

        HakAkses::create([
            'role_id' => $roleKoor->roles_id,
            'modul_id' => $modulParamUji->modul_id,
            'level_akses' => 'full'
        ]);

        $analis = User::factory()->create(['role_id' => $roleAnalis->roles_id]);
        $koor = User::factory()->create(['role_id' => $roleKoor->roles_id]);

        $policy = new \App\Policies\ParameterUjiPolicy();

        // Analis should fail (needs 'full' for Parameter Uji creation, but Analis only has 'tambah_ubah' for parameter_uji)
        $this->assertFalse($policy->create($analis));

        // Koordinator should pass (has 'full' for parameter_uji)
        $this->assertTrue($policy->create($koor));
    }
}
