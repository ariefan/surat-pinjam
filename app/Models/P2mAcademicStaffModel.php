<?php

namespace App\Models;

use CodeIgniter\Model;

class P2mAcademicStaffModel extends Model
{
    protected $table = "p2m_tenaga_kependidikan";
    protected $primaryKey = "tendik_id";
    protected $returnType = "object";
    protected $useTimestamps = false;
    protected $allowedFields = [
        'tendik_id', 'name', 'department', 'laboratory', 'researches ', 'community_services', 'researches_2', 'publications'
    ];
}
