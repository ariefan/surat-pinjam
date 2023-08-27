<?php

namespace App\Models;

use CodeIgniter\Model;

class PerjanjianLuaranModel extends Model
{
    protected $table = "mou_luaran";
    protected $primaryKey = "id_luaran";
    protected $returnType = "object";
    protected $useTimestamps = true;
    protected $allowedFields = [
        'id_luaran', 'id_mou', 'nama_luaran', 'deskripsi_luaran', 'bentuk_kegiatan',
        'jumlah_luaran', 'satuan', 'deleted', 'created_at', 'updated_at'
    ];
}
