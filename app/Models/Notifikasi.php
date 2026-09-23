<?php

namespace App\Models;

class Notifikasi extends BaseModel
{
    const UPDATED_AT = null;
    protected $table = 'notifikasi';

    protected $fillable = [
        'users_id',
        'jenis_notifikasi',
        'pesan',
        'url',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'users_id');
    }
}
