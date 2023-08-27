<?php

namespace App\Models;

use CodeIgniter\Model;

class UndanganModel extends Model
{
  protected $table = 'undangan';
  protected $primaryKey = "id";
  protected $returnType = "object";
  protected $useTimestamps = true;
  protected $allowedFields = ['tanggal_undangan', 'hal', 'lampiran', 'pengundang', 'sehubungan_dengan', 'hari', 'tanggal', 'pukul', 'tempat', 'acara', 'agenda', 'penerima', 'absen', 'notulen'];
}
