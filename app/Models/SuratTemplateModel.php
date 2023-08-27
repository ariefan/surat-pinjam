<?php

namespace App\Models;

use CodeIgniter\Model;

class SuratTemplateModel extends Model
{
    protected $table = "surat_template";
    protected $primaryKey = "id";
    protected $returnType = "object";
    protected $useTimestamps = true;
    protected $allowedFields = [
        'id', 'user_id', 'no_surat', 'nama_surat', 'lokasi_kegiatan', 'tanggal_pengajuan', 'tanggal_kegiatan_mulai', 'tanggal_kegiatan_selesai', 'tanggal_kegiatan',
        'isi_surat', 'isi_lampiran', 'status', 'file_pertanggungjawaban', 'verifikasi_verifikator', 'tabel',
        'departemen_pegawai_id', 'penandatangan_pegawai_id', 'tembusan', 'komentar', 'file_dasar_penerbitan',
        'shares', 'kategori', 'paragraf_baru'
    ];
}
