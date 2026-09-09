<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibraryLog extends Model
{
    use HasFactory;

    protected $table = 'library_logs';

    protected $fillable = [
        'document_id',
        'user_id',
        'action',
        'keterangan',
        'old_data',
        'new_data',
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
    ];

    public function document()
    {
        return $this->belongsTo(LibraryDocument::class, 'document_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'users_id');
    }
}
