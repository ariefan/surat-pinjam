<?php

namespace App\Models;

use CodeIgniter\Model;

class P2mPublikasiModel extends Model
{
    protected $table = "p2m_publikasi";
    protected $primaryKey = "id_publikasi";
    protected $returnType = "object";
    protected $useTimestamps = true;
    protected $allowedFields = [
        'id_publikasi', 'judul_publikasi', 'tanggal_publikasi', 'doi', 'link_scopus', 'link_wos', 'link_garuda', 'link_scholar', 'volume', 'issue', 'halaman', 'deskripsi', 'sitasi_per_tahun', 'is_journal', 'is_conference', 'is_mass_media',
        'id_jurnal', 'id_conference', 'id_penerbit', 'id_media_massa', 'laporan_akhir',
        'status', 'created_at', ' updated_at'
    ];

}
