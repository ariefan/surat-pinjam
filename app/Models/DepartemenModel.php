<?php

namespace App\Models;

use CodeIgniter\Model;

class DepartemenModel extends Model
{
    protected $table = "departemen";
    protected $primaryKey = "id";
    protected $returnType = "object";
    protected $useTimestamps = true;
    protected $allowedFields = ['nama_departemen', 'kepala_pegawai_id'];
}
