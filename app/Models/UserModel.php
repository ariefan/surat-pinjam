<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = "users";
    protected $primaryKey = "id";
    protected $returnType = "object";
    protected $useTimestamps = true;
    protected $allowedFields = ['id', 'username', 'password', 'nomor', 'nama', 'pangkat', 'tanggal_lahir', 'jenis_user','gol_pic_mou', 'chat_secret', 'last_seen', 'online_status', 'created_by'];
}