<?php

namespace App\Models;

use CodeIgniter\Model;

class P2mSuratModel extends Model
{
    protected $table = "p2m_surat";
    protected $primaryKey = "id_surat";
    protected $returnType = "object";
    protected $useTimestamps = false;
    protected $allowedFields = [
        'id_surat', 'id_dosen', 'id_tendik', 'id_eksternal', 'id_mahasiswa', 'jenis_surat', 'nama_file', 'tanggal_mulai', 'tanggal_selesai', 'tempat'
    ];
}
