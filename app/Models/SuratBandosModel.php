<?php

namespace App\Models;

use CodeIgniter\Model;

class SuratBandosModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'surat_bandos';
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
        'tipe_surat',
        'nama_surat',
        'fakultas_surat_tindaklanjut',
        'jabatan_surat_tindaklanjut',
        'no_surat_tindaklanjut',
        'tanggal_surat_tindaklanjut',
        'departemen_pembuat',
        'tanggal_pengajuan',
        'tanggal_deadline',
        'paragraf_baru',
        'status',
        'verifikasi_verifikator',
        'verifikasi_departemen',
        'tabel',
        'departemen_pegawai_id',
        'penandatangan_pegawai_id',
        'tembusan',
        'komentar',
        'shares',
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