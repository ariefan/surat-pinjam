<?php

namespace App\Models;

use CodeIgniter\Model;

class P2mScoreModel extends Model
{
    protected $table = "p2m_scoring";
    protected $primaryKey = "scoring_id";
    protected $returnType = "object";
    protected $useTimestamps = false;
    protected $allowedFields = [
        'scoring_id', 'dosen_id', 'tahun', 'sinta_q1', 'sinta_q2', 'sinta_q3', 'sinta_q4', 'h_index_q1', 'h_index_q2', 'h_index_q3', 'h_index_q4'
    ];
}
