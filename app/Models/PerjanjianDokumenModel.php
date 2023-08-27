<?php

namespace App\Models;

use CodeIgniter\Model;

class PerjanjianDokumenModel extends Model
{
    protected $table = "mou_dokumen_revisi";
    protected $primaryKey = "id_gdrive_dokumen";
    protected $returnType = "object";
    protected $useTimestamps = true;
    protected $allowedFields = [
        'id_revisi_mou', 'id_mou', 'keterangan', 'id_gdrive_dokumen','id_gdrive_dokumen_ori', 'id_gdrive_folder', 'status_revisi',
        'url_view_dokumen','url_view_dokumen_ori','tanggal_revisi','created_at', 'updated_at'
    ];
}
