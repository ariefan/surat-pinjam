<?php

namespace App\Models;

use CodeIgniter\Model;

class SuratPeminjamanJadwalModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'surat_pinjam';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = \App\Entities\SuratPinjamEntity::class;
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'surat_peminjaman_id',
        'ruang_id',
        'tanggal_mulai_pinjam',
        'tanggal_selesai_pinjam',
        'jam_mulai_pinjam',
        'jam_selesai_pinjam',
        'hari_pinjam',
    ];

    // Dates
    protected $useTimestamps = false;
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