<?php

namespace App\Models;

use CodeIgniter\Model;

class P2mJurnalModel extends Model
{
    protected $table = "p2m_jurnal_fmipa";
    protected $returnType = "object";
    protected $useTimestamps = true;
    protected $allowedFields = [
        'Title', 'Volume', 'years', 'Page', 'doi','first_author_lecturer','first_author_student','first_author_external','corresponding_author_lecturer', 'corresponding_author_student', 'corresponding_author_external', 'other_lecturer', 'other_student', 'other_external', 'affiliation_regency','affiliation_country', 'affiliation_institute','journal_name', 'keyword', 'Last_Modified_By','Georeference_Affiliation', 'Last_modified_time', 'jurnal_fmipa_id',
    ];

}
