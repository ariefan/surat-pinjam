<?php

namespace App\Models;

use CodeIgniter\Model;

class P2mPengabdianModel extends Model
{
    protected $table = "p2m_pengabdian";
    protected $primaryKey = "pengabdianID";
    protected $returnType = "object";
    protected $useTimestamps = true;
    protected $allowedFields = [
        'pengabdianID', 'department', 'team_leader', 'member_lecturer', 'member_academic_staff', 'member_student', 'title', 'funding_scheme_long', 'funding_scheme_short', 'jUmlah_dana', 'sumber_dana', 'delivery', 'kota', 'provinsi', 'time_start', 'time_end', 'data_source', 'proposal_date', 'fund_proposed', 'fund_accepted', 'acceptance_status', 'No_surat_tugas', 'supporting_document', 'Last_modified_by'
    ];

    public function getPengabdianDataByValue($value)
    {
        $builder = $this->builder();

        if (!empty($value)) {
            $builder->where('kota', $value);
        }

        $builder->select('department, team_leader, member_lecturer, member_academic_staff, member_student, title, funding_scheme_long, funding_scheme_short, jUmlah_dana, sumber_dana, delivery, kota, provinsi, time_start, time_end, data_source, proposal_date, fund_proposed, fund_accepted, acceptance_status, No_surat_tugas');

        return $builder->get()->getResultArray();
    }

    public function getPengabdian()
    {
        return $this->findAll();
    }
}
