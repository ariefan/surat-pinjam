<?php

namespace App\Models;

use CodeIgniter\Model;

class PICMitraModel extends Model
{
    protected $table = "mou_pic_mitra";
    protected $primaryKey = "id_pic_mitra";
    protected $returnType = "object";
    protected $useTimestamps = true;
    protected $allowedFields = [
        'id_pic_mitra','id_mou', 'nama_pic_mitra', 'jabatan_pic_mitra', 'alamat_pic_mitra', 'no_telp_pic_mitra', 'email_pic_mitra'
    ];
}
