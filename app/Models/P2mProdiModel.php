<?php

namespace App\Models;

use CodeIgniter\Model;

class P2mProdiModel extends Model
{
    protected $table = "p2m_prodi";
    protected $primaryKey = "prodi_id";
    protected $returnType = "object";
    protected $useTimestamps = false;
    protected $allowedFields = [
        'prodi_id', 'Name', 'head', 'secretary', 'members'
    ];
}
