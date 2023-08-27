<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailNomorSuratModel extends Model
{
    protected $table = "detail_no_surat";
    protected $primaryKey = "id";
    protected $returnType = "object";
    protected $useTimestamps = true;
    protected $allowedFields = ['id', 'no_surat', 'perihal', 'penandatangan', 'pengolah', 'kode_perihal', 'klasifikasi_surat', 'tanggal_surat', 'tujuan_surat', 'sifat_surat', 'user_id'];
}
