<?php

namespace App\Models;

use CodeIgniter\Model;

class PerjanjianMonevModel extends Model
{
    protected $table = "mou_monev";
    protected $primaryKey = "id_monev";
    protected $returnType = "object";
    protected $useTimestamps = true;
    protected $allowedFields = [
        'id_monev', 'id_mou', 'evaluator',
        'status_kegiatan', 'aktifitas_sudah', 'aktifitas_belum', 'kendala', 'solusi',
        'periode', 'semester', 'perpanjangan','deleted_at', 'created_at', 'updated_at'
    ];
}
