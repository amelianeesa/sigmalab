<?php namespace App\Models;

use CodeIgniter\Model;

class PersonilModel extends Model
{
    protected $table          = 'personil';
    protected $primaryKey     = 'personil_id';
    protected $allowedFields  = ['nama', 'jabatan', 'unit_kerja', 'no_induk', 'file_cv', 'status_aktif'];
    protected $useTimestamps  = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';
}