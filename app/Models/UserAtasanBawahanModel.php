<?php

namespace App\Models;

use CodeIgniter\Model;

class UserAtasanBawahanModel extends Model
{
    protected $table = "user_atasan_bawahan";
    protected $primaryKey = "id";
    protected $returnType = "object";
    protected $useTimestamps = true;
    protected $allowedFields = ['id', 'user_id_atasan', 'user_id_bawahan'];
}
