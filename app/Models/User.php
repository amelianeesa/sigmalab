<?php

namespace App\Models;

use App\Enums\PeranPengguna;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use SoftDeletes;
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'users_id';

    protected $fillable = [
        'personil_id',
        'username',
        'email',
        'password',
        'role_id',
        'workspace',
        'status_aktif',
        'must_change_password',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'status_aktif' => 'boolean',
            'must_change_password' => 'boolean',
        ];
    }

    public function personil()
    {
        return $this->belongsTo(Personil::class, 'personil_id', 'personil_id')->withoutGlobalScopes();
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'roles_id');
    }

    public function hasModulAccess(string $kodeModul, string $minLevel = 'lihat'): bool
    {
        if ($this->role && in_array($this->role->nama_role, ['HR', 'GA'])) {
            return true;
        }
        return app(\App\Services\PermissionService::class)->userHasAccess($this, $kodeModul, $minLevel);
    }

    /** Workspace milik akun: LAB | NON_LAB | ALL. Null (kolom belum ada) dianggap LAB. */
    public function workspaceAkun(): string
    {
        return $this->workspace ?: 'LAB';
    }

    public function bolehWorkspace(string $ws): bool
    {
        return in_array($this->workspaceAkun(), ['ALL', $ws], true);
    }

    /** Workspace akun ditentukan role: role lintas = ALL, selain itu sesuai pilihan (LAB / NON_LAB). */
    public static function workspaceUntukRole(int $roleId, ?string $pilihan = null): string
    {
        $nama = Role::where('roles_id', $roleId)->value('nama_role');

        if (in_array($nama, PeranPengguna::lintasWorkspace(), true)) {
            return 'ALL';
        }

        return in_array($pilihan, ['LAB', 'NON_LAB'], true) ? $pilihan : 'LAB';
    }

    public function bisaKelolaSdm(): bool
    {
        return in_array($this->role?->nama_role, PeranPengguna::pengelolaSdm(), true);
    }

    public function bisaBuatAkun(): bool
    {
        return in_array($this->role?->nama_role, PeranPengguna::pembuatAkun(), true);
    }
}