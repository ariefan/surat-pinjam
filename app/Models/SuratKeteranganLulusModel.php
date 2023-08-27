<?php

namespace App\Models;

use CodeIgniter\Model;

class SuratKeteranganLulusModel extends Model
{
    protected $table = "surat_keterangan_lulus";
    protected $primaryKey = "id";
    protected $returnType = "object";
    protected $useTimestamps = true;
    protected $allowedFields = [
        'tanggal_pengajuan',
        'id',
        'nama_mhs',
        'user_id',
        'nim',
        'prodi_pengaju',
        'departemen_pengaju',
        'no_surat',
        'tanggal_yudisium',
        'periode_wisuda',
        'bulan_wisuda',
        'sks_pengaju',
        'ipk_pengaju',
        'predikat_pengaju',
        'gelar',
        'sebutan_gelar',
        'status',
        'file_pertanggungjawaban',
        'file_dasar_penerbitan',
        'verifikasi_verifikator',
        'verifikasi_departemen',
        'tabel',
        'departemen_pegawai_id',
        'penandatangan_pegawai_id',
        'tembusan',
        'komentar',
        'shares',
    ];
}
