<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';          
    protected $primaryKey = 'roles_id';   // Sesuaikan dengan primary key di database

    protected $fillable = [
        'nama_role',
    ];
}