<?php

namespace App\Models;

use CodeIgniter\Model;

class SuratRekomendasiModel extends Model
{
    protected $table = "surat_rekomendasi";
    protected $primaryKey = "id";
    protected $returnType = "object";
    protected $useTimestamps = true;
    protected $allowedFields = [
        'id', 'user_id', 'reference_number', 'nama_surat', 	'tanggal_pengajuan',
        'tanggal_kegiatan_mulai', 'tanggal_kegiatan_selesai', 'kepada_surat',
        'lokasi_kegiatan', 'nama_kegiatan', 'peserta', 'no_hp', 'status',
        'verifikasi_kaprodi', 'departemen_pegawai_id', 'penandatangan_pegawai_id',
        'komentar', 'shares', 'created_at', 'updated_at'
    ];
}
