<?php

namespace App\Models;

use CodeIgniter\Model;

class P2mKonferensiModel extends Model
{
    protected $table = "p2m_konferensi";
    protected $primaryKey = "konferensi_id";
    protected $returnType = "object";
    protected $useTimestamps = false;
    protected $allowedFields = [
        'konferensi_id', 'Name', 'organizer', 'location_regency', 'location_country ', 'date_start', 'date_end', 'url', 'levels', 'publication_citations'
    ];
}
