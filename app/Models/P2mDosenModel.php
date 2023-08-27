<?php

namespace App\Models;

use CodeIgniter\Model;

class P2mDosenModel extends Model
{
    protected $table = "p2m_dosen";
    protected $primaryKey = "dosenID";
    protected $returnType = "object";
    protected $useTimestamps = true;
    protected $allowedFields = [
        'name', 'is_prof', 'department', 'degree', 'is_active', 'active_start', 'active_end', 'nidn', 'sinta_id', 'sinta_score_2023_01', 'Google_Scholar_ID', 'Scopus_ID', 'H_index_2023_01', 'WoS_ID', 'publons_id', 'orcid_id', 'laboratorium', 'study_programmes', 'expertise_group', 'acad_staff', 'dosenID'
    ];

    public function getDosen()
    {
        return $this->findAll();
    }
}
