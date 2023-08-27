<?php

namespace App\Models;

use CodeIgniter\Model;

class PegawaiModel extends Model
{
    protected $table = "pegawais";
    protected $primaryKey = "id";
    protected $returnType = "object";
    protected $useTimestamps = true;
    protected $allowedFields = ['nama_publikasi', 'nip', 'prodi', 'departemen', 'pangkat', 'golongan'];
}