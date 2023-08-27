<?php

namespace App\Models;

use CodeIgniter\Model;

class ListPICModel extends Model
{
    protected $table = "list_pic_ugm";
    protected $primaryKey = "id_list_pic";
    protected $returnType = "object";
    protected $useTimestamps = true;
    protected $allowedFields = [
        'id_list_pic','id_user_pic', 'nama_ugm','departemen_ugm', 'alamat_ugm', 'no_telp_ugm','tipe_pic'
    ];
}
