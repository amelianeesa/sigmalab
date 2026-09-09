<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LibraryDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'library_documents';

    protected $fillable = [
        'category_id',
        'judul',
        'nomor_dokumen',
        'kode_dokumen',
        'penerbit_dokumen',
        'deskripsi',
        'file_path',
        'file_name',
        'file_extension',
        'file_size',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function category()
    {
        return $this->belongsTo(LibraryCategory::class, 'category_id');
    }

    public function versions()
    {
        return $this->hasMany(LibraryDocumentVersion::class, 'document_id');
    }

    public function logs()
    {
        return $this->hasMany(LibraryLog::class, 'document_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'users_id');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by', 'users_id');
    }
}
