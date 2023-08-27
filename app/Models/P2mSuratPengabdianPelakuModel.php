<?php

namespace App\Models;

use CodeIgniter\Model;

class P2mSuratPengabdianPelakuModel extends Model
{
    protected $table = "p2m_pelaku_pengabdian";
    protected $primaryKey = "id_pelaku_pengabdian";
    protected $returnType = "object";
    protected $useTimestamps = false;
    protected $allowedFields = [
        'id_pelaku_pengabdian', 'id_pengabdian', 'id_dosen', 'id_tendik', 'id_mahasiswa', 'departemen_dosen', 'departemen_tendik', 'prodi_dosen'
    ];
}
