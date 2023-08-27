<?php

namespace App\Controllers;

use App\Models\P2mDosenModel;
use App\Models\P2mPublikasiModel;
use App\Models\P2mPenelitianModel;
use App\Models\P2mPengabdianModel;

class InterfaceP2m extends BaseController
{
    private $dosen;
    private $publikasi;
    private $penelitian;
    private $pengabdian;

    function __construct()
    {
        $this->dosen = new P2mDosenModel();
        $this->publikasi = new P2mPublikasiModel();
        $this->penelitian = new P2mPenelitianModel();
        $this->pengabdian = new P2mPengabdianModel();
    }

    public function interface_dosen()
    {
        $db = \Config\Database::connect();
        $request = \Config\Services::request();

        helper('number'); //memanggil function dari helper_number.php

        $data = $this->request->getGet();
        $q = $data['q'] ?? '';
        $start_date = $this->request->getGet('start_date');
        $end_date = $this->request->getGet('end_date');

        $jenis_user = session('jenis_user');
        $id = session('id');
        $pegawai_id = session('pegawai_id');
        $this->dosen->select('p2m_dosen.*');
        $rows = $this->dosen->orderBy('p2m_dosen.name', 'ASC')->get()->getResult();

        $lecturerID = $request->getGet('lecturer_id');

        $namaDosen = $this->dosen->select('p2m_dosen.*')->where('p2m_dosen.dosenID', $lecturerID)->get()->getResult();
        $jmlPublikasi = $this->dosen->join('p2m_publikasi_authorship', 'p2m_dosen.dosenID = p2m_publikasi_authorship.lecturer_id')->where('p2m_publikasi_authorship.lecturer_id =', $lecturerID)->countAllResults();
        $jmlPenelitian = $this->dosen->join('p2m_penelitian', 'p2m_dosen.name = p2m_penelitian.team_leader')->where('p2m_dosen.dosenID =', $lecturerID)->countAllResults();
        $jmlPengabdian = $this->dosen->join('p2m_pengabdian', 'p2m_dosen.name = p2m_pengabdian.team_leader')->where('p2m_dosen.dosenID =', $lecturerID)->countAllResults();
        $sintaScore = $this->dosen->select('p2m_scoring.tahun, 
                                    CASE
                                        WHEN p2m_scoring.sinta_q4 IS NOT NULL THEN p2m_scoring.sinta_q4
                                        WHEN p2m_scoring.sinta_q3 IS NOT NULL THEN p2m_scoring.sinta_q3
                                        WHEN p2m_scoring.sinta_q2 IS NOT NULL THEN p2m_scoring.sinta_q2
                                        ELSE p2m_scoring.sinta_q1
                                    END AS sinta_score', FALSE)
            ->join('p2m_scoring', 'p2m_dosen.dosenID = p2m_scoring.dosen_id')->where('p2m_dosen.dosenID', $lecturerID)->get()->getResult();
        $hIndex = $this->dosen->select('p2m_scoring.tahun, 
                                    CASE
                                        WHEN p2m_scoring.h_index_q1 IS NOT NULL THEN p2m_scoring.h_index_q1
                                        WHEN p2m_scoring.h_index_q3 IS NOT NULL THEN p2m_scoring.h_index_q3
                                        WHEN p2m_scoring.h_index_q2 IS NOT NULL THEN p2m_scoring.h_index_q2
                                        ELSE p2m_scoring.h_index_q1
                                    END AS h_index', FALSE)
                                    ->join('p2m_scoring', 'p2m_dosen.dosenID = p2m_scoring.dosen_id')->where('p2m_dosen.dosenID', $lecturerID)->get()->getResult();

        $sinta = array();
        $h_index = array();
        $tahun = array();

        foreach ($sintaScore as $row) {
            $sinta[] = $row->sinta_score;
            $tahun[] = $row->tahun;
        }

        foreach ($hIndex as $row) {
            $h_index[] = $row->h_index;
        }

        $data = [
            'rows' => $rows,
            'jenis_user' => $jenis_user,
            'user_id' => $id,
            'pegawai_id' => $pegawai_id,
            'namaDosen' => $namaDosen,
            'jmlPublikasi' => $jmlPublikasi,
            'jmlPenelitian' => $jmlPenelitian,
            'jmlPengabdian' => $jmlPengabdian,
            'sintaScore' => $sintaScore,
            'sinta' => $sinta,
            'h_index' =>$h_index,
            'tahun' => $tahun
        ];
        return view('p2m/interface_dosen', $data);
    }

    public function interface_dosen_ajax()
    {
        $db = \Config\Database::connect();
        $request = \Config\Services::request();

        helper('number'); //memanggil function dari helper_number.php

        $data = $this->request->getGet();

        $jenis_user = session('jenis_user');
        $id = session('id');
        $pegawai_id = session('pegawai_id');
        $this->dosen->select('p2m_dosen.*');
        $rows = $this->dosen->orderBy('p2m_dosen.name', 'ASC')->get()->getResult();

        $lecturerID = $request->getGet('lecturer_id');

        $jmlPublikasi = $this->dosen->join('p2m_publikasi_authorship', 'p2m_dosen.dosenID = p2m_publikasi_authorship.lecturer_id')->where('p2m_publikasi_authorship.lecturer_id =', $lecturerID)->countAllResults();
        $jmlPenelitian = $this->dosen->join('p2m_penelitian', 'p2m_dosen.name = p2m_penelitian.team_leader')->where('p2m_dosen.dosenID =', $lecturerID)->countAllResults();
        $jmlPengabdian = $this->dosen->join('p2m_pengabdian', 'p2m_dosen.name = p2m_pengabdian.team_leader')->where('p2m_dosen.dosenID =', $lecturerID)->countAllResults();
        $sintaScore = $this->dosen->select('p2m_scoring.tahun, CAST(p2m_scoring.sinta_q1 AS DECIMAL) AS sinta_q1')->join('p2m_scoring', 'p2m_dosen.dosenID = p2m_scoring.dosen_id')->where('p2m_dosen.dosenID', $lecturerID)->get()->getResult();
        $jmlSinta = $this->dosen->select('p2m_scoring.tahun, CAST(p2m_scoring.sinta_q1 AS DECIMAL) AS sinta_q1')->join('p2m_scoring', 'p2m_dosen.dosenID = p2m_scoring.dosen_id')->where('p2m_dosen.dosenID =', $lecturerID)->countAllResults();

        $sintaQ1 = null;
        $tahun = null;

        foreach ($sintaScore as $row) {
            $sintaQ1 = (int)$row->sinta_q1;
            $tahun = (int)$row->tahun;
        }

        $data = [
            'rows' => $rows,
            'jenis_user' => $jenis_user,
            'user_id' => $id,
            'pegawai_id' => $pegawai_id,
            'jmlPublikasi' => $jmlPublikasi,
            'jmlPenelitian' => $jmlPenelitian,
            'jmlPengabdian' => $jmlPengabdian,
            'sintaScore' => $sintaScore,
            'jmlSinta' => $jmlSinta,
            'sintaQ1' => $sintaQ1,
            'tahun' => $tahun
        ];
        echo json_encode($data);
    }

    public function interface_publikasi()
    {
        $db = \Config\Database::connect();
        $request = \Config\Services::request();

        helper('number'); //memanggil function dari helper_number.php

        $data = $this->request->getGet();
        $q = $data['q'] ?? '';
        $start_date = $this->request->getGet('start_date');
        $end_date = $this->request->getGet('end_date');

        $jenis_user = session('jenis_user');
        $id = session('id');
        $pegawai_id = session('pegawai_id');

        if (!empty($start_date) && !empty($end_date)) {
            $query = $this->publikasi
                ->where('tanggal_publikasi >=', $start_date)
                ->where('tanggal_publikasi <=', $end_date);
        } else {
            $query = $this->publikasi;
        }

        $rows = $query->get()->getResult();

        if (!empty($start_date) && !empty($end_date)) {
            $countAll = $query->where('tanggal_publikasi >=', $start_date)->where('tanggal_publikasi <=', $end_date)->countAllResults();
            $jurnalQ = $query->where('tanggal_publikasi >=', $start_date)->where('tanggal_publikasi <=', $end_date)->join('p2m_jurnal', 'p2m_publikasi.id_jurnal = p2m_jurnal.jurnal_id')->where('CONCAT(p2m_jurnal.levels) LIKE "international reputable"')->countAllResults();
            $jurnalS = $query->where('tanggal_publikasi >=', $start_date)->where('tanggal_publikasi <=', $end_date)->join('p2m_jurnal', 'p2m_publikasi.id_jurnal = p2m_jurnal.jurnal_id')->where('CONCAT(p2m_jurnal.levels) LIKE "national accredited"')->countAllResults();
            $publikasiNasional = $query->where('tanggal_publikasi >=', $start_date)->where('tanggal_publikasi <=', $end_date)->join('p2m_jurnal', 'p2m_publikasi.id_jurnal = p2m_jurnal.jurnal_id')->where('CONCAT(p2m_jurnal.levels) LIKE "national accredited" OR CONCAT(p2m_jurnal.levels) LIKE "national"')->countAllResults();
            $jurnalInternasional = $query->where('tanggal_publikasi >=', $start_date)->where('tanggal_publikasi <=', $end_date)->join('p2m_jurnal', 'p2m_publikasi.id_jurnal = p2m_jurnal.jurnal_id')->where('CONCAT(p2m_jurnal.levels) LIKE "international"')->countAllResults();
            $jurnalNasional = $query->where('tanggal_publikasi >=', $start_date)->where('tanggal_publikasi <=', $end_date)->join('p2m_jurnal', 'p2m_publikasi.id_jurnal = p2m_jurnal.jurnal_id')->where('CONCAT(p2m_jurnal.levels) LIKE "national accredited"')->countAllResults();
            $publikasiInternasional = $query->where('tanggal_publikasi >=', $start_date)->where('tanggal_publikasi <=', $end_date)->join('p2m_jurnal', 'p2m_publikasi.id_jurnal = p2m_jurnal.jurnal_id')->where('CONCAT(p2m_jurnal.levels) LIKE "international" OR CONCAT(p2m_jurnal.levels) LIKE "international reputable"')->countAllResults();
            $publikasiConference = $query->where('tanggal_publikasi >=', $start_date)->where('tanggal_publikasi <=', $end_date)->where('is_conference', 'checked')->countAllResults();
            $pubMitra = $query->where('tanggal_publikasi >=', $start_date)->where('tanggal_publikasi <=', $end_date)->table('p2m_publikasi')
                ->distinct()
                ->select('p2m_publikasi.id_publikasi')
                ->join('p2m_publikasi_authorship', 'p2m_publikasi.id_publikasi = p2m_publikasi_authorship.id_pub')
                ->where('p2m_publikasi_authorship.external_id IS NOT NULL')
                ->where('p2m_publikasi_authorship.id_pub IS NOT NULL')
                ->countAllResults();

        } else {
            $countAll = $query->countAllResults();
            $jurnalQ = $query->join('p2m_jurnal', 'p2m_publikasi.id_jurnal = p2m_jurnal.jurnal_id')->where('CONCAT(p2m_jurnal.levels) LIKE "international reputable"')->countAllResults();
            $jurnalS = $query->join('p2m_jurnal', 'p2m_publikasi.id_jurnal = p2m_jurnal.jurnal_id')->where('CONCAT(p2m_jurnal.levels) LIKE "national accredited"')->countAllResults();
            $publikasiNasional = $query->join('p2m_jurnal', 'p2m_publikasi.id_jurnal = p2m_jurnal.jurnal_id')->where('CONCAT(p2m_jurnal.levels) LIKE "national accredited" OR CONCAT(p2m_jurnal.levels) LIKE "national"')->countAllResults();
            $jurnalInternasional = $query->join('p2m_jurnal', 'p2m_publikasi.id_jurnal = p2m_jurnal.jurnal_id')->where('CONCAT(p2m_jurnal.levels) LIKE "international"')->countAllResults();
            $jurnalNasional = $query->join('p2m_jurnal', 'p2m_publikasi.id_jurnal = p2m_jurnal.jurnal_id')->where('CONCAT(p2m_jurnal.levels) LIKE "national accredited"')->countAllResults();
            $publikasiInternasional = $query->join('p2m_jurnal', 'p2m_publikasi.id_jurnal = p2m_jurnal.jurnal_id')->where('CONCAT(p2m_jurnal.levels) LIKE "international" OR CONCAT(p2m_jurnal.levels) LIKE "international reputable"')->countAllResults();
            $publikasiConference = $query->where('is_conference', 'checked')->countAllResults();
            $pubMitra = $query->table('p2m_publikasi')
                ->distinct()
                ->select('p2m_publikasi.id_publikasi')
                ->join('p2m_publikasi_authorship', 'p2m_publikasi.id_publikasi = p2m_publikasi_authorship.id_pub')
                ->where('p2m_publikasi_authorship.external_id IS NOT NULL')
                ->where('p2m_publikasi_authorship.id_pub IS NOT NULL')
                ->countAllResults();

        }

        // dump($pubMitra);
        $data = [
            'rows' => $rows,
            'jenis_user' => $jenis_user,
            'user_id' => $id,
            'pegawai_id' => $pegawai_id,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'countAll' => $countAll,
            'jurnalQ' => $jurnalQ,
            'jurnalS' => $jurnalS,
            'publikasiNasional' => $publikasiNasional,
            'jurnalInternasional' => $jurnalInternasional,
            'jurnalNasional' => $jurnalNasional,
            'publikasiInternasional' => $publikasiInternasional,
            'publikasiConference' => $publikasiConference,
            'pubMitra' => $pubMitra
        ];
        return view('p2m/interface_publikasi', $data);
    }

    public function interface_pengabdian()
    {
        $db = \Config\Database::connect();

        helper('number'); //memanggil function dari helper_number.php

        $data = $this->request->getGet();
        $q = $data['q'] ?? '';
        $start_date = $this->request->getGet('start_date');
        $end_date = $this->request->getGet('end_date');

        $jenis_user = session('jenis_user');
        $id = session('id');
        $pegawai_id = session('pegawai_id');
        if (!empty($start_date) && !empty($end_date)) {
            $query = $this->pengabdian
                ->where('time_start >=', $start_date)
                ->where('time_start <=', $end_date);
        } else {
            $query = $this->pengabdian;
        }

        $rows = $query->get()->getResult();

        if (!empty($start_date) && !empty($end_date)) {
            $countAll = $query->where('time_start >=', $start_date)->where('time_start <=', $end_date)->countAllResults();
            $countIptek = $query->where('time_start >=', $start_date)->where('time_start <=', $end_date)->where("CONCAT(title) LIKE '%Pengembangan Teknologi%' OR CONCAT(title) LIKE '%produksi alat%' OR CONCAT(title) LIKE '%metode%'")->countAllResults();
            $countCity = $query->where('time_start >=', $start_date)->where('time_start <=', $end_date)->distinct()->select('p2m_pengabdian.kota')->countAllResults();
            $countBinaan = $query->where('time_start >=', $start_date)->where('time_start <=', $end_date)->where("CONCAT(title) LIKE '%desa%' OR CONCAT(title) LIKE '%komunitas%' OR CONCAT(title) LIKE '%community%'")->countAllResults();
            $countP2m = $query->where('time_start >=', $start_date)->where('time_start <=', $end_date)->where("CONCAT(title) LIKE '%diseminasi%'")->countAllResults();
            $countUmkm = $query->where('time_start >=', $start_date)->where('time_start <=', $end_date)->where("CONCAT(title) LIKE '%umkm%' OR CONCAT(title) LIKE '%usaha mikro kecil menengah%' OR CONCAT(title) LIKE '%(umkm)%'")->countAllResults();
            $countDana = $query->where('time_start >=', $start_date)->where('time_start <=', $end_date)->selectSum("fund_proposed")->get()->getRow();
        } else {
            $countAll = $query->countAllResults();
            $countIptek = $query->where("CONCAT(title) LIKE '%Pengembangan Teknologi%' OR CONCAT(title) LIKE '%produksi alat%' OR CONCAT(title) LIKE '%metode%'")->countAllResults();
            $countCity = $query->distinct()->select('p2m_pengabdian.kota')->countAllResults();
            $countBinaan = $query->where("CONCAT(title) LIKE '%desa%' OR CONCAT(title) LIKE '%komunitas%' OR CONCAT(title) LIKE '%community%'")->countAllResults();
            $countP2m = $query->where("CONCAT(title) LIKE '%diseminasi%'")->countAllResults();
            $countUmkm = $query->where("CONCAT(title) LIKE '%umkm%' OR CONCAT(title) LIKE '%usaha mikro kecil menengah%' OR CONCAT(title) LIKE '%(umkm)%'")->countAllResults();
            $countDana = $query->selectSum("fund_proposed")->get()->getRow();
        }
        $sum = $countDana->fund_proposed;

        if ($sum === null) {
            $sum = 0;
        } else {
            $sum = number_to_currency($sum, 'IDR', 'id_DE', 2);
        }
        // dump($countIptek);

        $search_terms = [
            'fisika' => 'fisika',
            'ike' => 'Ilmu Komputer dan Elektronika',
            'kimia' => 'kimia',
            'mat' => 'matematika',
            'fak' => 'fakultas'
        ];

        $search_terms1 = [
            'MANDAT2022' => 'MANDAT2022',
            'LINTAS2022' => 'LINTAS2022',
            'BINAAN2022' => 'BINAAN2022',
            'LABKIM2022' => 'LABKIM2022',
            'PKMDM' => 'PKMDM',
            'SWADAYA2021' => 'SWADAYA2021',
            'SWADAYA2022' => 'SWADAYA2022',
            'Luar Negeri (UGM' => 'Luar Negeri (UGM-UNUD-MFRI-MPO)'
        ];

        $search_terms2 = [
            'OTHER' => 'OTHER',
            'SIMPEL' => 'SIMPEL',
            'SURAT TUGAS' => 'SURAT TUGAS'
        ];

        $counts = [];
        $sum_departemen = [];
        $pkm_dana = [];
        $sum_dana = [];
        $pkm_data = [];
        $dana_data = [];
        $pkm_fisdana = [];

        if (!empty($start_date) && !empty($end_date)) {
            foreach ($search_terms1 as $key => $value) {
                $pkm_fisdana[$key] = $this->pengabdian->where('time_start >=', $start_date)->where('time_start <=', $end_date)->like('funding_scheme_short', $value)->like('department', 'fisika')->countAllResults();
            }

            foreach ($search_terms as $key => $value) {
                $counts[$key] = $this->pengabdian->where('time_start >=', $start_date)->where('time_start <=', $end_date)->like('department', $value)->countAllResults();
            }

            foreach ($search_terms as $key => $value) {
                $sum_departemen[$key] = $this->pengabdian->where('time_start >=', $start_date)->where('time_start <=', $end_date)->like('department', $value)->selectSum("fund_proposed")->get()->getRow()->fund_proposed;
            }

            foreach ($search_terms1 as $key => $value) {
                $pkm_dana[$key] = $this->pengabdian->where('time_start >=', $start_date)->where('time_start <=', $end_date)->like('funding_scheme_short', $value)->countAllResults();
            }

            foreach ($search_terms1 as $key => $value) {
                $sum_dana[$key] = $this->pengabdian->where('time_start >=', $start_date)->where('time_start <=', $end_date)->like('funding_scheme_short', $value)->selectSum("fund_proposed")->get()->getRow()->fund_proposed;
            }

            foreach ($search_terms2 as $key => $value) {
                $pkm_data[$key] = $this->pengabdian->where('time_start >=', $start_date)->where('time_start <=', $end_date)->like('data_source', $value)->countAllResults();
            }

            foreach ($search_terms2 as $key => $value) {
                $dana_data[$key] = $this->pengabdian->where('time_start >=', $start_date)->where('time_start <=', $end_date)->like('data_source', $value)->selectSum("fund_proposed")->get()->getRow()->fund_proposed;
            }
        } else {
            foreach ($search_terms1 as $key => $value) {
                $pkm_fisdana[$key] = $this->pengabdian->like('funding_scheme_short', $value)->like('department', 'fisika')->countAllResults();
            }

            foreach ($search_terms as $key => $value) {
                $counts[$key] = $this->pengabdian->like('department', $value)->countAllResults();
            }

            foreach ($search_terms as $key => $value) {
                $sum_departemen[$key] = $this->pengabdian->like('department', $value)->selectSum("fund_proposed")->get()->getRow()->fund_proposed;
            }

            foreach ($search_terms1 as $key => $value) {
                $pkm_dana[$key] = $this->pengabdian->like('funding_scheme_short', $value)->countAllResults();
            }

            foreach ($search_terms1 as $key => $value) {
                $sum_dana[$key] = $this->pengabdian->like('funding_scheme_short', $value)->selectSum("fund_proposed")->get()->getRow()->fund_proposed;
            }

            foreach ($search_terms2 as $key => $value) {
                $pkm_data[$key] = $this->pengabdian->like('data_source', $value)->countAllResults();
            }

            foreach ($search_terms2 as $key => $value) {
                $dana_data[$key] = $this->pengabdian->like('data_source', $value)->selectSum("fund_proposed")->get()->getRow()->fund_proposed;
            }
        }

        $data = [
            'rows' => $rows,
            'jenis_user' => $jenis_user,
            'user_id' => $id,
            'pegawai_id' => $pegawai_id,
            'q' => $q,
            'countAll' => $countAll,
            'countIptek' => $countIptek,
            'countCity' => $countCity,
            'countBinaan' => $countBinaan,
            'countP2m' => $countP2m,
            'countUmkm' => $countUmkm,
            'countDana' => $countDana,
            'sum' => $sum,
            'counts' => $counts,
            'sum_departemen' => $sum_departemen,
            'pkm_dana' => $pkm_dana,
            'sum_dana' => $sum_dana,
            'pkm_data' => $pkm_data,
            'dana_data' => $dana_data,
            'pkm_fisdana' => $pkm_fisdana,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'label' => ['FISIKA', 'IKE', 'KIMIA', 'MATEMATIKA', 'FAKLULTAS'],
            'label_pie' => ['MANDAT2022', 'LINTAS2022', 'BINAAN2022', 'LABKIM2022', 'PKMDM', 'SWADAYA2021', 'SWADAYA2022', 'Luar Negeri (UGM-UNUD-MFRI-MPO)'],
            'label_pie2' => ['OTHER', 'SIMPEL', 'SURAT TUGAS'],
        ];
        return view('p2m/interface_pengabdian', $data);
    }

    public function interface_penelitian()
    {
        $db = \Config\Database::connect();

        helper('number'); //memanggil function dari helper_number.php

        $data = $this->request->getGet();
        $q = $data['q'] ?? '';
        $start_date = $this->request->getGet('start_date');
        $end_date = $this->request->getGet('end_date');

        $last = $this->penelitian
            ->select('years as tahun')
            ->orderBy('tahun', 'DESC')
            ->first();

        $periodes = $this->penelitian
            ->select('years as tahun')
            ->groupBy('tahun')
            ->orderBy('tahun', 'DESC')
            ->get()
            ->getResult();

        $q_tahun = empty($this->request->getGet('q_tahun')) ? $last->tahun : $this->request->getGet('q_tahun');

        $jenis_user = session('jenis_user');
        $id = session('id');
        $pegawai_id = session('pegawai_id');
        if (!empty($start_date) && !empty($end_date)) {
            $query = $this->penelitian->select('p2m_penelitian.*, p2m_pengabdian.fund_proposed, ')
                ->join('p2m_pengabdian', 'p2m_penelitian.team_leader = p2m_pengabdian.team_leader')
                ->where('years >=', $start_date)
                ->where('years <=', $end_date);
        } else {
            $query = $this->penelitian->select('p2m_penelitian.*, p2m_pengabdian.fund_proposed, ')
                ->join('p2m_pengabdian', 'p2m_penelitian.team_leader = p2m_pengabdian.team_leader');
        }

        $rows = $query->get()->getResultArray();

        if (!empty($start_date) && !empty($end_date)) {
            $countAll = $this->penelitian->where('years >=', $start_date)->where('years <=', $end_date)->countAllResults();
            $countDana = $this->pengabdian->where('time_start >=', $start_date)->where('time_start <=', $end_date)->selectSum("fund_proposed")->get()->getRow();
            $penelitian_kim = $this->penelitian->where('years >=', $start_date)->where('years <=', $end_date)->where("CONCAT(department) LIKE '%kimia%'")->countAllResults();
            $penelitian_fis = $this->penelitian->where('years >=', $start_date)->where('years <=', $end_date)->where("CONCAT(department) LIKE '%fisika%'")->countAllResults();
            $penelitian_mat = $this->penelitian->where('years >=', $start_date)->where('years <=', $end_date)->where("CONCAT(department) LIKE '%matematika%'")->countAllResults();
            $penelitian_ike = $this->penelitian->where('years >=', $start_date)->where('years <=', $end_date)->where("CONCAT(department) LIKE '%Ilmu Komputer dan Elektronika%'")->countAllResults();
            $penelitian_tu = $this->penelitian->where('years >=', $start_date)->where('years <=', $end_date)->where("CONCAT(department) LIKE '%Kantor Pusat Tata Usaha%'")->countAllResults();
        } else {
            $countAll = $this->penelitian->countAllResults();
            $countDana = $this->pengabdian->selectSum("fund_proposed")->get()->getRow();
            $penelitian_kim = $this->penelitian->where("CONCAT(department) LIKE '%kimia%'")->countAllResults();
            $penelitian_fis = $this->penelitian->where("CONCAT(department) LIKE '%fisika%'")->countAllResults();
            $penelitian_mat = $this->penelitian->where("CONCAT(department) LIKE '%matematika%'")->countAllResults();
            $penelitian_ike = $this->penelitian->where("CONCAT(department) LIKE '%Ilmu Komputer dan Elektronika%'")->countAllResults();
            $penelitian_tu = $this->penelitian->where("CONCAT(department) LIKE '%Kantor Pusat Tata Usaha%'")->countAllResults();
        }

        $sum = $countDana !== null ? $countDana->fund_proposed : 0;
        $total = $countDana !== null ? $countDana->fund_proposed : 0;
        $totals = intval($total);

        if ($totals === 0) {
            $percentage = 0;
        } else {
            $sum = number_to_currency($sum, 'IDR', 'id_DE', 2);
        }
        // $laporan = $this->penelitian->where("CONCAT(report) LIKE '%checked%' OR CONCAT(fund_type) LIKE '%damas%'")->countAllResults();

        $search_terms = [
            'fisika' => 'fisika',
            'ike' => 'Ilmu Komputer dan Elektronika',
            'kimia' => 'kimia',
            'mat' => 'matematika',
            'tu' => 'kantor pusat tata usaha'
        ];

        $counts = [];
        $sum_departemen = [];
        $percentages = [];
        $dana_data = [];

        if (!empty($start_date) && !empty($end_date)) {
            foreach ($search_terms as $key => $value) {
                $counts[$key] = $this->penelitian->where('years >=', $start_date)->where('years <=', $end_date)->like('department', $value)->countAllResults();
            }

            foreach ($search_terms as $key => $value) {
                $countDana = $this->pengabdian->where('time_start >=', $start_date)->where('time_start <=', $end_date)->like('department', $value)->selectSum("fund_proposed")->get()->getRow();
                $sum_departemen = $countDana !== null ? $countDana->fund_proposed : 0;
                $percentage = ($totals !== 0) ? ($sum_departemen / $totals) * 100 : 0;
                $percentages[$value] = number_format($percentage, 2);
            }

            foreach ($search_terms as $key => $value) {
                $dana_data[$key] = $this->pengabdian->where('time_start >=', $start_date)->where('time_start <=', $end_date)->like('department', $value)->selectSum("fund_proposed")->get()->getRow()->fund_proposed;
            }
        } else {
            foreach ($search_terms as $key => $value) {
                $counts[$key] = $this->penelitian->like('department', $value)->countAllResults();
            }

            foreach ($search_terms as $key => $value) {
                $countDana = $this->pengabdian->like('department', $value)->selectSum("fund_proposed")->get()->getRow();
                $sum_departemen = $countDana !== null ? $countDana->fund_proposed : 0;
                $percentage = ($totals !== 0) ? ($sum_departemen / $totals) * 100 : 0;
                $percentages[$value] = number_format($percentage, 2);
            }

            foreach ($search_terms as $key => $value) {
                $dana_data[$key] = $this->pengabdian->like('department', $value)->selectSum("fund_proposed")->get()->getRow()->fund_proposed;
            }
        }

        // dump($);

        $data = [
            'rows' => $rows,
            'jenis_user' => $jenis_user,
            'user_id' => $id,
            'pegawai_id' => $pegawai_id,
            'q' => $q,
            'last' => $last,
            'periodes' => $periodes,
            'q_tahun' => $q_tahun,
            'countAll' => $countAll,
            'sum' => $sum,
            'penelitian_kim' => $penelitian_kim,
            'penelitian_fis' => $penelitian_fis,
            'penelitian_mat' => $penelitian_mat,
            'penelitian_ike' => $penelitian_ike,
            'penelitian_tu' => $penelitian_tu,
            // 'laporan' => $laporan
            'counts' => $counts,
            'percentages' => $percentages,
            'key' => $key,
            'dana_data' => $dana_data,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'label' => ['FISIKA', 'ILMU KOMPUTER DAN ELEKTRONIKA', 'KIMIA', 'MATEMATIKA', 'KANTOR PUSAT TATA USAHA'],
        ];

        return view('p2m/interface_penelitian', $data);
    }
}
