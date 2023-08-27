<?php

namespace App\Models;

use CodeIgniter\Model;

class BuatSuratModel extends Model
{
    protected $table = "buat_surat";
    protected $primaryKey = "id";
    protected $returnType = "object";
    protected $useTimestamps = true;
    protected $allowedFields = [
        'id', 'user_id', 'no_surat', 'nama_surat', 'lokasi_kegiatan', 'tanggal_pengajuan', 'tanggal_kegiatan_mulai', 'tanggal_kegiatan_selesai', 'tanggal_kegiatan', 'status', 'nama_template',
        'isi_surat', 'isi_lampiran', 'file_pertanggungjawaban', 'verifikasi_verifikator', 'verifikasi_departemen', 'tabel',
        'departemen_pegawai_id', 'penandatangan_pegawai_id', 'tembusan', 'komentar', 'file_dasar_penerbitan',
        'shares', 'kategori'
    ];
}
