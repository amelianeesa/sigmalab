<?php

namespace App\Models;

class Notifikasi extends BaseModel
{
    protected $table = 'notifikasi';

    protected $fillable = [
        'users_id',
        'judul',
        'pesan',
        'link',
        'is_read',
        'tipe'
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'users_id');
    }
}
