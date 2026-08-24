<?php

namespace App\Models;

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
        'status_aktif',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'status_aktif' => 'boolean',
        ];
    }

    public function personil()
    {
        return $this->belongsTo(Personil::class, 'personil_id', 'personil_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'roles_id');
    }

    public function hasModulAccess(string $kodeModul, string $minLevel = 'lihat'): bool
    {
        return app(\App\Services\PermissionService::class)->userHasAccess($this, $kodeModul, $minLevel);
    }
}

