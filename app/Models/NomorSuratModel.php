<?php

namespace App\Models;

use CodeIgniter\Model;

class NomorSuratModel extends Model
{
    protected $table = "no_surat";
    protected $primaryKey = "id";
    protected $returnType = "object";
    protected $useTimestamps = true;
    protected $allowedFields = ['kode_klasifikasi', 'ket_klasifikasi', 'nomor'];
}
