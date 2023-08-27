<?php

namespace App\Models;

use CodeIgniter\Model;

class P2mExternalModel extends Model
{
    protected $table = "p2m_eksternal";
    protected $primaryKey = "eksternal_id";
    protected $returnType = "object";
    protected $useTimestamps = false;
    protected $allowedFields = [
        'eksternal_id', 'name', 'affiliations', 'country', 'publications_2_co_author ', 'publications_3_main_author'
    ];
}
