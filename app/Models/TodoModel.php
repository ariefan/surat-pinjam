<?php

namespace App\Models;

use CodeIgniter\Model;

class TodoModel extends Model
{
    protected $table = "todo";
    protected $primaryKey = "id";
    protected $returnType = "object";
    protected $useTimestamps = true;
    protected $allowedFields = [
        'tugas', 'deadline', 'status_tugas', 'pemberi_tugas_user_id', 'user_id', 'link',
    ];
}
