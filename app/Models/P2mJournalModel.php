<?php

namespace App\Models;

use CodeIgniter\Model;

class P2mJournalModel extends Model
{
    protected $table = "p2m_jurnal";
    protected $primaryKey = "jurnal_id";
    protected $returnType = "object";
    protected $useTimestamps = false;
    protected $allowedFields = [
        'jurnal_id', 'name', 'url', 'levels', 'indexer', 'index_rank', 'publication_citations', 'internal_journal'
    ];
}
