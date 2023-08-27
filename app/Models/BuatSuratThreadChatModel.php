<?php

namespace App\Models;

use CodeIgniter\Model;

class BuatSuratThreadChatModel extends Model
{
    protected $table = "buat_surat_thread_chat";
    protected $primaryKey = "thread_id";
    protected $returnType = "object";
    protected $useTimestamps = true;
    protected $allowedFields = [
        'buat_surat_id', 'user_id', 'isi_chat', 'tanggal_kirim',
    ];
}
