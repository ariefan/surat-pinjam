<?php

namespace App\Models;

use CodeIgniter\Model;

class SuratAkademikModel extends Model
{
    protected $table = "surat_akademik";
    protected $primaryKey = "id";
    protected $returnType = "object";
    protected $useTimestamps = true;
    protected $allowedFields = [
        'id', 'user_id', 'no_surat', 'nama_surat', 'dosen_pengampu', 'mata_kuliah', 'semester', 'surat_permintaan_nilai_ke', 'tanggal_pengajuan', 'tanggal_terlewat', 'tanggal_batas_akhir', 
        'status', 'verifikasi_verifikator', 'verifikasi_departemen', 'departemen_pegawai_id', 'penandatangan_pegawai_id',
        'tembusan', 'komentar', 'shares', 'kategori', 'paragraf_baru'
    ];
}
