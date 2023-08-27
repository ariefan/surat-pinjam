<?php

namespace App\Models;

use CodeIgniter\Model;

class YudisiumModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'yudisium';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $insertID = 0;
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'id',
        'user_id',
        'no_surat',
        'nama_surat',
        'sertifikat_ppsmb',
        'sertifikat_ppsmb_status',
        'sertifikat_pengurus_ukm',
        'sertifikat_pengurus_ukm_status',
        'surat_bebas_pinjam_perpus_ugm',
        'surat_bebas_pinjam_perpus_ugm_status',
        'dokumen_skripsi_final',
        'dokumen_skripsi_final_status',
        'lembar_pengesahan_skripsi',
        'lembar_pengesahan_skripsi_status',
        // 'transkrip_nilai',
        // 'khs_lengkap',
        'profil_mahasiswa',
        'status_pengajuan_surat_bebas_pinjam_lab',
        // 'status_pengajuan_surat_bebas_pinjam_perpus',
        'pengajuan_penghapusan_matkul',
        'pengajuan_penghapusan_matkul_status',
        'komentar',
        'status',
        'created_at',
        'updated_at',
        'pernah_internasional_exposure',
        'pernah_internasional_exposure_status',
        'list_matkul',
        'tanggal_yudisium',
        'lama_studi',
        'prodi_id'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];
}