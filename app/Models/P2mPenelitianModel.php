<?php

namespace App\Models;

use CodeIgniter\Model;

class P2mPenelitianModel extends Model
{
    protected $table = "p2m_penelitian";
    protected $returnType = "object";
    protected $useTimestamps = true;
    protected $allowedFields = [
        'title', 'location', 'team_leader', 'team_leader_academic_staff', 'department', 'referenced_in_thesis', 'referenced_in_publication', 'years', 'report', 'keyword'
    ];

    public function getPenelitian()
    {
        return $this->findAll();
    }
}
