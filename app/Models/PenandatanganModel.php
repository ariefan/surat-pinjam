<?php

namespace App\Models;

use CodeIgniter\Model;

class PenandatanganModel extends Model
{
    protected $table = "penandatangan";
    protected $primaryKey = "id";
    protected $returnType = "object";
    protected $useTimestamps = true;
    protected $allowedFields = ['nama_penandatangan', 'kode', 'kepala_pegawai_id'];
}
