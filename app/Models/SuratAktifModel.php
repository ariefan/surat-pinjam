<?php

namespace App\Models;

use CodeIgniter\Model;

class SuratAktifModel extends Model
{
    protected $table = "surat_aktif";
    protected $primaryKey = "id";
    protected $returnType = "object";
    protected $useTimestamps = true;
    protected $allowedFields = [
        'id', 'user_id', 'no_surat', 'tanggal_pengajuan', 'nama_mhs', 'nim', 'prog_studi', 'departemen', 'program', 'angkatan', 'keperluan', 'nama_ortu', 'nip_ortu', 'pangkat_ortu', 'instansi_ortu',
        'status',
        'departemen_pegawai_id', 'penandatangan_pegawai_id', 'tembusan', 'komentar', 'verifikasi_verifikator', 'semester', 'tahun_ajaran',
        'shares', 'created_at', 'updated_at'
    ];
}
