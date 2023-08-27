<?php

namespace App\Models;

use CodeIgniter\Model;

class PerjanjianModel extends Model
{
    protected $table = "mou";
    protected $primaryKey = "id_mou";
    protected $returnType = "object";
    protected $useTimestamps = true;
    protected $allowedFields = [
        'id_mou', 'id_pic_ugm', 'id_pic_mitra', 'id_departemen', 'id_instansi_mitra', 'negara', 'tipe_dokumen', 'judul_kerjasama', 'no_dokumen_ugm', 'no_dokumen_mitra',
        'bidang_kerjasama', 'detail_layanan', 'nominal_kerjasama', 'dpi', 'id_dpi_dokumen', 'program_studi_terlibat', 'pejabat_penandatanganan_ugm',
        'tanggal_pengajuan','pejabat_penandatanganan_mitra', 'tanggal_penandatanganan', 'tanggal_mulai_kerjasama', 'tanggal_akhir_kerjasama', 'status_mou',
        'id_gdrive_dokumen','url_dokumen','created_at', 'updated_at', 'currency','jangka_waktu','keterangan_dpi'
    ];
    public $created_at;
    public $program_studi_terlibat;
    public $bidang_kerjasama;
    public $custom_option;
    public $jangka_waktu;
}

