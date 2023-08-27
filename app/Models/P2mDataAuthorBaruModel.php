<?php

namespace App\Models;

use CodeIgniter\Model;

class P2mDataAuthorBaruModel extends Model
{
    protected $table = "p2m_publikasi_authorship";
    protected $primaryKey = "id_authorship";
    protected $returnType = "object";
    protected $useTimestamps = false;
    protected $allowedFields = [
        'id_author', 'id_pub', 'lecturer_id', 'mahasiswa_id', 'acad_id', 'external_id', 'is_main_author', 'is_lecturer_author', 'is_academic_staff_author', 'is_student_author', 'is_external_author'
    ];
}
