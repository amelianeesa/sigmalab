<?php namespace App\Models;

use CodeIgniter\Model;

class KompetensiModel extends Model
{
    protected $table          = 'kompetensi_personil';
    protected $primaryKey     = 'kompetensi_personil_id';
    protected $allowedFields  = ['personil_id', 'jenis_sertifikasi', 'no_sertifikasi', 'file_sertifikat', 'tanggal_terbit', 'tanggal_berakhir'];
    protected $useTimestamps  = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';

    public function getKompetensiWithPersonil()
    {
        return $this->select('kompetensi_personil.*, personil.nama, personil.no_induk')
                    ->join('personil', 'personil.personil_id = kompetensi_personil.personil_id')
                    ->findAll();
    }
}