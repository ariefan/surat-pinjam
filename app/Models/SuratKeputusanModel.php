<?php

namespace App\Models;

use CodeIgniter\Model;

class SuratKeputusanModel extends Model
{
    protected $table = "surat_keputusan";
    protected $primaryKey = "id";
    protected $returnType = "object";
    protected $useTimestamps = true;
    protected $allowedFields = [
        'id', 'no_surat', 'nama_surat', 'tanggal_pengajuan', 'isi', 'kategori',
        'menimbang', 'mengingat', 'memperhatikan', 'memutuskan', 
        'tembusan', 'lampiran', 'departemen_pegawai_id', 'penandatangan_pegawai_id', 'komentar', 'shares', 'status','user_id', 
    ];
}
