<?php

namespace App\Models;

use CodeIgniter\Model;

class P2mDepartemenModel extends Model
{
    protected $table = "p2m_departemen";
    protected $primaryKey = "departemen_id";
    protected $returnType = "object";
    protected $useTimestamps = false;
    protected $allowedFields = [
        'departemen_id', 'name', 'head', 'secretary', 'daftar_dosen', 'academic_staff'
    ];
}
