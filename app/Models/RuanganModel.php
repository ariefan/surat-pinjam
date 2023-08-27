<?php

namespace App\Models;

use CodeIgniter\Model;

class RuanganModel extends Model
{
  protected $table = 'ruangan';
  protected $primaryKey = "id";
  protected $returnType = "object";
  protected $useTimestamps = true;
  protected $allowedFields = ['nama', 'lokasi', 'akses'];
}
