<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
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

    public function personil()
    {
        return $this->belongsTo(Personil::class, 'personil_id', 'personil_id');
    }

    protected function casts(): array
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'roles_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'roles_id');
    }
}
