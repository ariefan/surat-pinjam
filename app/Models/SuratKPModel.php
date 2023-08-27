<?php

namespace App\Models;

use CodeIgniter\Model;

class SuratKPModel extends Model
{
    protected $table = "surat_kp";
    protected $primaryKey = "id";
    protected $returnType = "object";
    protected $useTimestamps = true;
    protected $allowedFields = [
        'id', 'user_id', 'no_surat', 'nama_surat', 'kepada_surat', 'lokasi_kegiatan', 'alamat_kegiatan', 'alamat_tambahan_satu', 'alamat_tambahan_dua',
        'tanggal_pengajuan', 'tanggal_kegiatan_mulai', 'tanggal_kegiatan_selesai', 'tanggal_kegiatan',
        'status', 'verifikasi_verifikator', 'verifikasi_departemen',
        'departemen_pegawai_id', 'penandatangan_pegawai_id', 'tembusan', 'komentar',
        'shares', 'kategori', 'peserta', 'created_at', 'updated_at', 'no_hp', 'jenis_kegiatan'
    ];
}
