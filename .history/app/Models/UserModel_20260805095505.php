<?php namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table          = 'users';
    protected $primaryKey     = 'users_id';
    protected $allowedFields  = ['personil_id', 'username', 'email', 'password', 'role_id', 'status_aktif'];
    protected $useTimestamps  = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';

    public function getUserWithRole($username)
    {
        return $this->select('users.*, roles.nama_role')
                    ->join('roles', 'roles.roles_id = users.role_id')
                    ->where('users.username', $username)
                    ->orWhere('users.email', $username)
                    ->first();
    }
}