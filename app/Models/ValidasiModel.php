<?php

namespace App\Models;

use CodeIgniter\Model;

class ValidasiModel extends Model
{
    protected $table = "validasi";
    protected $primaryKey = "id";
    protected $returnType = "object";
    protected $useTimestamps = true;
    protected $allowedFields = ['id', 'pdf'];
}