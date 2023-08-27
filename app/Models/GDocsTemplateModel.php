<?php

namespace App\Models;

use CodeIgniter\Model;

class GDocsTemplateModel extends Model
{
    protected $table = "template_gdocs";
    protected $primaryKey = "id";
    protected $returnType = "object";
    protected $useTimestamps = true;
    protected $allowedFields = [
        'id', 'gdocs_id', 'title'
    ];
}
