<?php

namespace App\Models;

use CodeIgniter\Model;

class PeraturanModel extends Model
{
    protected $table = "peraturans";
    protected $primaryKey = "id";
    protected $returnType = "object";
    protected $useTimestamps = true;
    protected $allowedFields = ['tanggal_berlaku', 'peraturan', 'aktif'];
}