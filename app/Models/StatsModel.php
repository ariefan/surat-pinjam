<?php

namespace App\Models;

use CodeIgniter\Model;

class StatsModel extends Model
{
    protected $table = "stats";
    protected $primaryKey = "id";
    protected $returnType = "object";
    protected $useTimestamps = true;
    protected $allowedFields = [
        'no',
        'indikator',
        'satuan',
        'tahun',
        'bulan',
        'periode',
        'target',
        'jumlah_prodi',
        'capaian',
        'capaian_ike',
        'capaian_fis',
        'capaian_mat',
        'capaian_kim',
        'sumber_data',
        'keterangan'
    ];
}