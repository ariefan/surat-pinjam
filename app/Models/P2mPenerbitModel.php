<?php

namespace App\Models;

use CodeIgniter\Model;

class P2mPenerbitModel extends Model
{
    protected $table = "p2m_penerbit";
    protected $primaryKey = "penerbit_id";
    protected $returnType = "object";
    protected $useTimestamps = false;
    protected $allowedFields = [
        'penerbit_id', 'Publisher', 'url', 'levels', 'journal ', 'conference'
    ];
}
