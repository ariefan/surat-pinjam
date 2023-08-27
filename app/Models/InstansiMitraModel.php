<?php

namespace App\Models;

use CodeIgniter\Model;

class InstansiMitraModel extends Model
{
    protected $table = "mou_instansi_mitra";
    protected $primaryKey = "id_instansi_mitra";
    protected $returnType = "object";
    protected $useTimestamps = true;
    protected $allowedFields = [
        'id_instansi_mitra', 'nama_mitra', 'alamat_mitra', 'no_telp_mitra', 'email_mitra'
    ];
}
