<?php

namespace App\Models;

use CodeIgniter\Model;

class PICUGMModel extends Model
{
    protected $table = "mou_pic_ugm";
    protected $primaryKey = "id_pic_ugm";
    protected $returnType = "object";
    protected $useTimestamps = true;
    protected $allowedFields = [
        "id_pic_ugm", 'id_user_pic','id_mou', 'nama_pic_ugm', 'jabatan_pic_ugm', 'alamat_pic_ugm', 'no_telp_pic_ugm', 'email_pic_ugm', 'departemen_ugm'
    ];
}
