<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class HakAkses extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'hak_akses';

    protected $fillable = [
        'role_id',
        'modul_id',
        'level_akses',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::saved(function ($hakAkses) {
            Cache::forget('hak_akses_matrix');
        });

        static::deleted(function ($hakAkses) {
            Cache::forget('hak_akses_matrix');
        });
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'roles_id');
    }

    public function modul()
    {
        return $this->belongsTo(Modul::class, 'modul_id', 'modul_id');
    }
}

