<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibraryDocumentVersion extends Model
{
    use HasFactory;

    protected $table = 'library_document_versions';

    protected $fillable = [
        'document_id',
        'version_number',
        'revisi_ke',
        'judul',
        'file_path',
        'file_name',
        'catatan_revisi',
        'tanggal_terbit',
        'tanggal_berlaku',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_terbit' => 'date',
            'tanggal_berlaku' => 'date',
        ];
    }

    public function document()
    {
        return $this->belongsTo(LibraryDocument::class, 'document_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'users_id');
    }
}
