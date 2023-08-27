<?php

namespace App\Models;

use CodeIgniter\Model;

class P2mDraftModel extends Model
{
    protected $table = "draft_publikasi";
    protected $primaryKey = "id";
    protected $returnType = "object";
    protected $useTimestamps = true;
    protected $allowedFields = [
        'id', 'name', 'title', 'first_author', 'co_author', 'link_scopus', 'link_wos', 'link_garuda', 'link_scholar', 'date', 'journal', 'doi', 'rank', 'volume', 'issue', 'pages', 'publisher', 'description', 'citation', 'created_at', 'updated_at'
    ];
}
