<?php

namespace App\Models;

use CodeIgniter\Model;

class P2mSuratPenelitianPelakuModel extends Model
{
    protected $table = "p2m_pelaku_penelitian";
    protected $primaryKey = "id_pelaku_penelitian";
    protected $returnType = "object";
    protected $useTimestamps = false;
    protected $allowedFields = [
        'id_pelaku_penelitian', 'id_penelitian', 'id_dosen', 'id_tendik', 'departemen_dosen', 'departemen_tendik', 'prodi_dosen'
    ];
}
