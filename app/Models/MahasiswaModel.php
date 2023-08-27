<?php

namespace App\Models;

use CodeIgniter\Model;

class MahasiswaModel extends Model
{
    protected $table = "mahasiswa";
    protected $primaryKey = "id";
    protected $returnType = "object";
    protected $useTimestamps = true;
    protected $allowedFields = ['nama', 'user_id', 'nim', 'prodi', 'tanggal_daftar', 'aktif'];
}
