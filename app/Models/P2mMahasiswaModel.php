<?php

namespace App\Models;

use CodeIgniter\Model;

class P2mMahasiswaModel extends Model
{
    protected $table = "p2m_mahasiswa";
    protected $primaryKey = "mahasiswa_id";
    protected $returnType = "object";
    protected $useTimestamps = false;
    protected $allowedFields = [
        'mahasiswa_id', 'name', 'degree', 'NIM', 'researches ', 'community_services', 'thesis', 'publications'
    ];
}
