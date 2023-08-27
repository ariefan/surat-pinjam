<?php

namespace App\Models;

use CodeIgniter\Model;

class P2mMedmassModel extends Model
{
    protected $table = "p2m_medmass";
    protected $primaryKey = "medmass_id";
    protected $returnType = "object";
    protected $useTimestamps = false;
    protected $allowedFields = [
        'medmass_id', 'Name', 'url', 'level'
    ];
}
