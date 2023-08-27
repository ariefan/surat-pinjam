<?php

namespace App\Controllers;

use App\Models\P2mDosenModel;
use App\Models\P2mPublikasiModel;
use App\Models\P2mPenelitianModel;
use App\Models\P2mPengabdianModel;
use App\Models\P2mDraftModel;
use App\Models\P2mDataAuthorBaruModel;
use App\Models\P2mJurnalModel;
use App\Models\P2mJournalModel;
use App\Models\P2mKonferensiModel;
use App\Models\P2mPenerbitModel;
use App\Models\P2mMedmassModel;
use App\Models\P2mMahasiswaModel;
use App\Models\P2mAcademicStaffModel;
use App\Models\P2mExternalModel;
use App\Models\P2mScoreModel;
use App\Models\P2mSuratModel;
use App\Models\P2mProdiModel;
use App\Models\P2mDepartemenModel;
use App\Models\P2mSuratPenelitianPelakuModel;
use App\Models\P2mSuratPengabdianPelakuModel;
use Curl\Curl;
use Google\Service\AnalyticsData\OrderBy;
use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Ramsey\Collection\Set;
use Smalot\PdfParser\Parser;

class P2m extends BaseController
{
    private $dosen;
    private $publikasi;
    private $penelitian;
    private $pengabdian;
    private $draft;
    private $author;
    private $journal;
    private $jurnal;
    private $konferensi;
    private $penerbit;
    private $mass_media;
    private $mahasiswa;
    private $acad;
    private $external;
    private $score;
    private $surat;
    private $prodi;
    private $departemen;
    private $pelaku_penelitian;
    private $pelaku_pengabdian;

    function __construct()
    {
        $this->dosen = new P2mDosenModel();
        $this->publikasi = new P2mPublikasiModel();
        $this->penelitian = new P2mPenelitianModel();
        $this->pengabdian = new P2mPengabdianModel();
        $this->jurnal = new P2mJurnalModel();
        $this->draft = new P2mDraftModel();
        $this->author = new P2mDataAuthorBaruModel();
        $this->journal = new P2mJournalModel();
        $this->konferensi = new P2mKonferensiModel();
        $this->penerbit = new P2mPenerbitModel();
        $this->mass_media = new P2mMedmassModel();
        $this->mahasiswa = new P2mMahasiswaModel();
        $this->acad = new P2mAcademicStaffModel();
        $this->external = new P2mExternalModel();
        $this->score = new P2mScoreModel();
        $spreadsheet = new Spreadsheet();
        $this->surat = new P2mSuratModel();
        $this->prodi = new P2mProdiModel();
        $this->departemen = new P2mDepartemenModel();
        $this->pelaku_penelitian = new P2mSuratPenelitianPelakuModel();
        $this->pelaku_pengabdian = new P2mSuratPengabdianPelakuModel();
    }

    public function index()
    {
        return view('p2m/index');
    }

    public function p2m_dosen()
    {
        $db = \Config\Database::connect();

        $data = $this->request->getGet();
        $q = $data['q'] ?? '';
        $status = $data['status'] ?? '';
        $sort_column = $data['sort_column'] ?? 'created_at';
        $sort_order = $data['sort_order'] ?? 'desc';

        $jenis_user = session('jenis_user');
        $id = session('id');
        $pegawai_id = session('pegawai_id');
        $this->dosen->select('p2m_dosen.*')
            ->orderBy($sort_column, $sort_order)
            ->where("CONCAT(name) LIKE '%$q%' OR CONCAT(department) LIKE '%$q%' OR CONCAT(nidn) LIKE '%$q%'");
        $rows = $this->dosen->paginate(10);

        $data = [
            'rows' => $rows,
            'pager' => $this->dosen->pager,
            'jenis_user' => $jenis_user,
            'user_id' => $id,
            'pegawai_id' => $pegawai_id,
            'q' => $q,
            'sort_column' => $sort_column,
            'sort_order' => $sort_order,
        ];
        return view('p2m/p2m_dosen', $data);
    }

    public function edit($dosenID)
    {
        $db = \Config\Database::connect();
        $row = (new P2mDosenModel)->where('dosenID', $dosenID)->first();
        $data = [
            'action' => 'update',
            'row' => $row,
        ];
        return view('p2m/form_dosen', $data);
    }

    public function update($dosenID)
    {
        $data = $this->request->getPost(['name', 'is_prof', 'degree', 'is_active', 'active_end', 'laboratorium']);
        $this->dosen->update($dosenID, $data);

        return $this->response->redirect(site_url('p2m/p2m_dosen'));
    }

    public function create_dosen()
    {
        $row = new P2mDosenModel();
        $row = $this->dosen->select('p2m_dosen.dosenID, p2m_dosen.name, p2m_dosen.department, p2m_dosen.degree, p2m_dosen.is_active, p2m_dosen.active_start, p2m_dosen.active_end, p2m_dosen.nidn, p2m_dosen.sinta_id, p2m_dosen.sinta_score_2023_01, p2m_dosen.Google_Scholar_ID, p2m_dosen.Scopus_ID, p2m_dosen.H_index_2023_01, p2m_dosen.WoS_ID, p2m_dosen.publons_id, p2m_dosen.orcid_id, p2m_dosen.laboratorium, p2m_dosen.study_programmes, p2m_dosen.expertise_group, p2m_dosen.acad_staff')
            ->findAll();
        // dump($row);
        $data = [
            'action' => 'store_dosen',
            'row' => $row,
        ];
        return view('p2m/store_dosen', $data);
    }

    public function store_dosen()
    {
        if ($this->request->getMethod() === 'post') {
            $data = $this->request->getPost([
                'name', 'department', 'degree', 'is_prof', 'is_active', 'active_start', 'active_end', 'nidn', 'sinta_id', 'sinta_score_2023_01', 'Google_Scholar_ID', 'Scopus_ID', 'H_index_2023_01', 'WoS_ID', 'publons_id', 'orcid_id', 'study_programmes', 'laboratorium', 'expertise_group', 'acad_staff'
            ]);

            $this->dosen->insert($data);
            return redirect()->to(site_url('p2m/p2m_dosen'));
        }

        return view('p2m/form_dosen');
    }

    public function delete($dosenID)
    {
        $db = \Config\Database::connect();
        $row = $this->dosen->select('p2m_dosen.*');

        $row->where('dosenID', $dosenID);
        $row->delete();

        return redirect()->to('p2m/p2m_dosen');
    }

    public function p2m_jurnal()
    {
        $db = \Config\Database::connect();

        $data = $this->request->getGet();
        $q = $data['q'] ?? '';
        $status = $data['status'] ?? '';
        $sort_column = $data['sort_column'] ?? 'created_at';
        $sort_order = $data['sort_order'] ?? 'desc';

        $jenis_user = session('jenis_user');
        $id = session('id');
        $pegawai_id = session('pegawai_id');
        $this->jurnal->select('p2m_jurnal_fmipa.*')
            ->orderBy($sort_column, $sort_order)
            ->where("CONCAT(Title) LIKE '%$q%' OR CONCAT(first_author_lecturer) LIKE '%$q%' OR CONCAT(first_author_student) LIKE '%$q%' OR CONCAT(first_author_external) LIKE '%$q%'  OR CONCAT(corresponding_author_lecturer) LIKE '%$q%' OR CONCAT(corresponding_author_student) LIKE '%$q%' OR CONCAT(corresponding_author_external) LIKE '%$q%' OR CONCAT(other_lecturer) LIKE '%$q%' OR CONCAT(other_student) LIKE '%$q%' OR CONCAT(other_external) LIKE '%$q%' OR CONCAT(affiliation_regency) LIKE '%$q%' OR CONCAT(affiliation_country) LIKE '%$q%' OR CONCAT(affiliation_institute) LIKE '%$q%' OR CONCAT(journal_name) LIKE '%$q%' OR CONCAT(keyword) LIKE '%$q%'");
        $rows = $this->jurnal->paginate(10);

        foreach ($rows as $row) {
            $row->other_external = json_decode($row->other_external);
        }

        $data = [
            'rows' => $rows,
            'pager' => $this->jurnal->pager,
            'jenis_user' => $jenis_user,
            'user_id' => $id,
            'pegawai_id' => $pegawai_id,
            'q' => $q,
            'sort_column' => $sort_column,
            'sort_order' => $sort_order,
        ];
        return view('p2m/p2m_jurnal', $data);
    }

    public function p2m_publikasi()
    {
        $db = \Config\Database::connect();

        $data = $this->request->getGet();
        $q = $data['q'] ?? '';
        $status = $data['status'] ?? '';
        $sort_column = $data['sort_column'] ?? 'p2m_publikasi.created_at';
        $sort_order = $data['sort_order'] ?? 'desc';

        $jenis_user = session('jenis_user');
        $id = session('id');
        $pegawai_id = session('pegawai_id');
        $row = $this->publikasi->select('p2m_publikasi.*, p2m_publikasi_authorship.*, p2m_dosen.*, p2m_eksternal.*, p2m_tenaga_kependidikan.*, p2m_mahasiswa.*, p2m_dosen.name AS dosen_name, p2m_dosen.department AS dosen_department')
            ->join('p2m_publikasi_authorship', 'p2m_publikasi_authorship.id_pub = p2m_publikasi.id_publikasi', 'left')
            ->join('p2m_dosen', 'p2m_dosen.dosenID = p2m_publikasi_authorship.lecturer_id', 'left')
            ->join('p2m_eksternal', 'p2m_eksternal.eksternal_id = p2m_publikasi_authorship.id_authorship', 'left')
            ->join('p2m_tenaga_kependidikan', 'p2m_tenaga_kependidikan.tendik_id = p2m_publikasi_authorship.id_authorship', 'left')
            ->join('p2m_mahasiswa', 'p2m_mahasiswa.id_mahasiswa = p2m_publikasi_authorship.id_authorship', 'left')
            ->orderBy($sort_column === 'name' ? 'p2m_dosen.name' : ($sort_column === 'department' ? 'p2m_dosen.department' : $sort_column), $sort_order)
            ->where("CONCAT(judul_publikasi) LIKE '%$q%' OR CONCAT(p2m_dosen.department) LIKE '%$q%'");
        $rows = $row->paginate(10);

        // foreach($rows as $row){
        //     $row->copy=json_decode($row->copy);
        //     $row->co_author_lecturer=json_decode($row->co_author_lecturer);
        //     $row->co_author_academic_staff=json_decode($row->co_author_academic_staff);
        //     $row->co_author_student=json_decode($row->co_author_student);
        //     $row->co_authors_external=json_decode($row->co_authors_external);
        //     $row->country_co_authors_external=json_decode($row->country_co_authors_external);
        //     $row->publisher=json_decode($row->publisher);
        // }

        $data = [
            'rows' => $rows,
            'pager' => $this->publikasi->pager,
            'jenis_user' => $jenis_user,
            'user_id' => $id,
            'pegawai_id' => $pegawai_id,
            'q' => $q,
            'sort_column' => $sort_column,
            'sort_order' => $sort_order,
        ];
        return view('p2m/p2m_publikasi', $data);
    }

    public function p2m_penelitian()
    {
        $db = \Config\Database::connect();

        $data = $this->request->getGet();
        $q = $data['q'] ?? '';
        $status = $data['status'] ?? '';
        $sort_column = $data['sort_column'] ?? 'created_at';
        $sort_order = $data['sort_order'] ?? 'desc';

        $jenis_user = session('jenis_user');
        $id = session('id');
        $pegawai_id = session('pegawai_id');
        $this->penelitian->select('p2m_penelitian.*')
            ->orderBy($sort_column, $sort_order)
            ->where("CONCAT(title) LIKE '%$q%' OR CONCAT(location) LIKE '%$q%' OR CONCAT(team_leader) LIKE '%$q%' OR CONCAT(team_leader_academic_staff) LIKE '%$q%' OR CONCAT(department) LIKE '%$q%' OR CONCAT(years) LIKE '%$q%' OR CONCAT(keyword) LIKE '%$q%'");
        $rows = $this->penelitian->paginate(10);

        $data = [
            'rows' => $rows,
            'pager' => $this->penelitian->pager,
            'jenis_user' => $jenis_user,
            'user_id' => $id,
            'pegawai_id' => $pegawai_id,
            'q' => $q,
            'sort_column' => $sort_column,
            'sort_order' => $sort_order,
        ];
        return view('p2m/p2m_penelitian', $data);
    }

    public function p2m_pengabdian()
    {
        $db = \Config\Database::connect();

        $data = $this->request->getGet();
        $q = $data['q'] ?? '';
        $status = $data['status'] ?? '';
        $sort_column = $data['sort_column'] ?? 'created_at';
        $sort_order = $data['sort_order'] ?? 'desc';
        $start_date = $this->request->getVar('start_date');
        $end_date = $this->request->getVar('end_date');

        $jenis_user = session('jenis_user');
        $id = session('id');
        $pegawai_id = session('pegawai_id');

        $row = $this->pengabdian->select('p2m_pengabdian.*')
            ->where("CONCAT(department) LIKE '%$q%' OR CONCAT(team_leader) LIKE '%$q%' OR CONCAT(member_lecturer) LIKE '%$q%' OR CONCAT(member_academic_staff) LIKE '%$q%' OR CONCAT(member_student) LIKE '%$q%' OR CONCAT(title) LIKE '%$q%' OR CONCAT(funding_scheme_long) LIKE '%$q%' OR CONCAT(kota) LIKE '%$q%' OR CONCAT(provinsi) LIKE '%$q%' OR CONCAT(No_surat_tugas) LIKE '%$q%'");

        if (!empty($start_date) && !empty($end_date) && strtotime($start_date) && strtotime($end_date)) {
            $row->where('time_start >=', $start_date);
            $row->where('time_start <=', $end_date);
        }

        $row->orderBy($sort_column, $sort_order);
        $rows = $row->paginate(10);

        foreach ($rows as $row) {
            $row->department = json_decode($row->department);
            $row->member_lecturer = json_decode($row->member_lecturer);
            $row->member_academic_staff = json_decode($row->member_academic_staff);
            $row->member_student = json_decode($row->member_student);
        }

        helper('number');

        foreach ($rows as $row) {
            $row->jUmlah_dana = number_to_currency($row->jUmlah_dana, 'IDR', 'id_DE', 2);
            $row->fund_proposed = number_to_currency($row->fund_proposed, 'IDR', 'id_DE', 2);
            $row->fund_accepted = number_to_currency($row->fund_accepted, 'IDR', 'id_DE', 2);
        }

        $data = [
            'rows' => $rows,
            'pager' => $this->pengabdian->pager,
            'jenis_user' => $jenis_user,
            'user_id' => $id,
            'pegawai_id' => $pegawai_id,
            'q' => $q,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'sort_column' => $sort_column,
            'sort_order' => $sort_order,
        ];

        return view('p2m/p2m_pengabdian', $data);
    }


    public function edit_pengabdian($pengabdianID)
    {
        $db = \Config\Database::connect();
        $row = (new P2mPengabdianModel)->where('pengabdianID', $pengabdianID)->first();
        $data = [
            'action' => 'update_pengabdian',
            'row' => $row,
        ];
        return view('p2m/form_pengabdian', $data);
    }

    public function update_pengabdian($pengabdianID)
    {
        $data = $this->request->getPost(['department', 'team_leader', 'member_lecturer', 'member_academic_staff', 'member_student', 'title', 'funding_scheme_long', 'funding_scheme_short', 'jUmlah_dana', 'sumber_dana', 'delivery', 'kota', 'provinsi', 'time_start', 'time_end', 'data_source', 'proposal_date', 'fund_proposed', 'fund_accepted', 'acceptance_status', 'No_surat_tugas', 'supporting_document']);
        $this->pengabdian->update($pengabdianID, $data);

        return $this->response->redirect(site_url('p2m/p2m_pengabdian'));
    }

    public function create_pengabdian()
    {
        $row = new P2mPengabdianModel();
        $data = [
            'action' => 'store_pengabdian',
            'row' => $row,
        ];
        return view('p2m/form_pengabdian', $data);
    }

    public function store_pengabdian()
    {
        if ($this->request->getMethod() === 'post') {
            $data = $this->request->getPost([
                'team_leader', 'member_lecturer', 'member_academic_staff', 'member_student', 'title', 'funding_scheme_long', 'funding_scheme_short', 'jUmlah_dana', 'sumber_dana', 'delivery', 'kota', 'provinsi', 'time_start', 'time_end', 'data_source', 'proposal_date', 'fund_proposed', 'fund_accepted', 'acceptance_status', 'No_surat_tugas', 'supporting_document'
            ]);
            $data['department'] = json_encode($this->request->getPost('department'));

            $this->pengabdian->insert($data);
            return redirect()->to(site_url('p2m/p2m_pengabdian'));
        }

        return view('p2m/form_pengabdian');
    }

    public function delete_pengabdian($pengabdianID)
    {
        $db      = \Config\Database::connect();
        $row = $this->pengabdian->select('p2m_pengabdian.*');

        $row->where('pengabdianID', $pengabdianID);
        $row->delete();

        return redirect()->to('p2m/p2m_pengabdian');
    }

    public function export_pengabdian()
    {
        // Mendapatkan data pengabdian dari database (sesuai dengan kebutuhan Anda)

        // Membuat objek Spreadsheet
        $spreadsheet = new Spreadsheet();

        // Mengisi data ke dalam Spreadsheet (sesuaikan dengan struktur data dan kolom yang ingin di-export)

        // Menyimpan file Excel
        $writer = new Xlsx($spreadsheet);
        $writer->save('./file.xlsx');

        // Mendownload file Excel yang dihasilkan
        $filePath = './file.xlsx';
        $fileName = 'pengabdian.xlsx';
        $mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';

        header('Content-Type: ' . $mimeType);
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        readfile($filePath);
        exit;
    }

    public function p2m_external()
    {
        $db = \Config\Database::connect();

        $data = $this->request->getGet();
        $q = $data['q'] ?? '';
        $status = $data['status'] ?? '';
        $sort_column = $data['sort_column'] ?? 'created_at';
        $sort_order = $data['sort_order'] ?? 'desc';

        $jenis_user = session('jenis_user');
        $id = session('id');
        $pegawai_id = session('pegawai_id');

        $this->external->select('p2m_eksternal.*')
            ->orderBy($sort_column, $sort_order);
        $rows = $this->external->paginate(10);

        $data = [
            'rows' => $rows,
            'pager' => $this->external->pager,
            'jenis_user' => $jenis_user,
            'user_id' => $id,
            'pegawai_id' => $pegawai_id,
            'q' => $q,
            'sort_column' => $sort_column,
            'sort_order' => $sort_order,
        ];
        return view('p2m/p2m_external', $data);
    }

    public function edit_eksternal($eksternal_id)
    {
        $db = \Config\Database::connect();
        $row = (new P2mExternalModel)->where('eksternal_id', $eksternal_id)->first();
        $data = [
            'action' => 'update_eksternal',
            'row' => $row,
        ];
        return view('p2m/form_eksternal', $data);
    }

    public function update_eksternal($eksternal_id)
    {
        $data = $this->request->getPost(['name', 'affiliations', 'country', 'publications_2_co_author ', 'publications_3_main_author']);
        $this->external->update($eksternal_id, $data);

        return $this->response->redirect(site_url('p2m/p2m_eksternal'));
    }

    public function create_eksternal()
    {
        $row = new P2mExternalModel();
        $data = [
            'action' => 'store_eksternal',
            'row' => $row,
        ];
        return view('p2m/form_eksternal', $data);
    }

    public function store_eksternal()
    {
        if ($this->request->getMethod() === 'post') {
            $data = $this->request->getPost([
                'name', 'affiliations', 'country', 'publications_2_co_author ', 'publications_3_main_author'
            ]);
            $data['department'] = json_encode($this->request->getPost('department'));

            $this->external->insert($data);
            return redirect()->to(site_url('p2m/p2m_eksternal'));
        }

        return view('p2m/form_eksternal');
    }

    public function delete_eksternal($eksternal_id)
    {
        $db      = \Config\Database::connect();
        $row = $this->external->select('p2m_eksternal.*');

        $row->where('eksternal_id', $eksternal_id);
        $row->delete();

        return redirect()->to('p2m/p2m_eksternal');
    }

    public function p2m_konferensi()
    {
        $db = \Config\Database::connect();

        $data = $this->request->getGet();
        $q = $data['q'] ?? '';
        $status = $data['status'] ?? '';
        $sort_column = $data['sort_column'] ?? 'created_at';
        $sort_order = $data['sort_order'] ?? 'desc';

        $jenis_user = session('jenis_user');
        $id = session('id');
        $pegawai_id = session('pegawai_id');

        $this->konferensi->select('p2m_konferensi.*')
            ->orderBy($sort_column, $sort_order);
        $rows = $this->konferensi->paginate(10);

        $data = [
            'rows' => $rows,
            'pager' => $this->konferensi->pager,
            'jenis_user' => $jenis_user,
            'user_id' => $id,
            'pegawai_id' => $pegawai_id,
            'q' => $q,
            'sort_column' => $sort_column,
            'sort_order' => $sort_order,
        ];
        return view('p2m/p2m_konferensi', $data);
    }

    public function update_konferensi($konferensi_id)
    {
        $data = $this->request->getPost(['Name', 'organizer', 'location_regency', 'location_country ', 'date_start', 'date_end', 'url', 'levels', 'publication_citations']);
        $this->konferensi->update($konferensi_id, $data);

        return $this->response->redirect(site_url('p2m/p2m_konferensi'));
    }

    public function create_konferensi()
    {
        $row = new P2mKonferensiModel();
        $data = [
            'action' => 'store_konferensi',
            'row' => $row,
        ];
        return view('p2m/form_konferensi', $data);
    }

    public function store_konferensi()
    {
        if ($this->request->getMethod() === 'post') {
            $data = $this->request->getPost([
                'Name', 'organizer', 'location_regency', 'location_country ', 'date_start', 'date_end', 'url', 'levels', 'publication_citations'
            ]);
            $data['department'] = json_encode($this->request->getPost('department'));

            $this->konferensi->insert($data);
            return redirect()->to(site_url('p2m/p2m_konferensi'));
        }

        return view('p2m/form_konferensi');
    }

    public function delete_konferensi($konferensi_id)
    {
        $db      = \Config\Database::connect();
        $row = $this->konferensi->select('p2m_konferensi.*');

        $row->where('konferensi_id', $konferensi_id);
        $row->delete();

        return redirect()->to('p2m/p2m_konferensi');
    }

    public function p2m_acadStaf()
    {
        $db = \Config\Database::connect();

        $data = $this->request->getGet();
        $q = $data['q'] ?? '';
        $status = $data['status'] ?? '';
        $sort_column = $data['sort_column'] ?? 'created_at';
        $sort_order = $data['sort_order'] ?? 'desc';

        $jenis_user = session('jenis_user');
        $id = session('id');
        $pegawai_id = session('pegawai_id');

        $this->acad->select('p2m_tenaga_kependidikan.*')
            ->orderBy($sort_column, $sort_order);
        $rows = $this->acad->paginate(10);

        $data = [
            'rows' => $rows,
            'pager' => $this->acad->pager,
            'jenis_user' => $jenis_user,
            'user_id' => $id,
            'pegawai_id' => $pegawai_id,
            'q' => $q,
            'sort_column' => $sort_column,
            'sort_order' => $sort_order,
        ];
        return view('p2m/p2m_acad', $data);
    }

    public function update_acadStaf($tendik_id)
    {
        $data = $this->request->getPost(['name', 'department', 'laboratory', 'researches ', 'community_services', 'researches_2', 'publications']);
        $this->acad->update($tendik_id, $data);

        return $this->response->redirect(site_url('p2m/p2m_acad'));
    }

    public function create_acadStaf()
    {
        $row = new P2mAcademicStaffModel();
        $data = [
            'action' => 'store_acadStaf',
            'row' => $row,
        ];
        return view('p2m/form_acad', $data);
    }

    public function store_acadStaf()
    {
        if ($this->request->getMethod() === 'post') {
            $data = $this->request->getPost([
                'name', 'department', 'laboratory', 'researches ', 'community_services', 'researches_2', 'publications'
            ]);
            $data['department'] = json_encode($this->request->getPost('department'));

            $this->acad->insert($data);
            return redirect()->to(site_url('p2m/p2m_acad'));
        }

        return view('p2m/form_acad');
    }

    public function delete_acadStaf($tendik_id)
    {
        $db      = \Config\Database::connect();
        $row = $this->acad->select('p2m_tenaga_kependidikan.*');

        $row->where('tendik_id', $tendik_id);
        $row->delete();

        return redirect()->to('p2m/p2m_acad');
    }

    public function p2m_mahasiswa()
    {
        $db = \Config\Database::connect();

        $data = $this->request->getGet();
        $q = $data['q'] ?? '';
        $status = $data['status'] ?? '';
        $sort_column = $data['sort_column'] ?? 'created_at';
        $sort_order = $data['sort_order'] ?? 'desc';

        $jenis_user = session('jenis_user');
        $id = session('id');
        $pegawai_id = session('pegawai_id');

        $this->mahasiswa->select('p2m_mahasiswa.*')
            ->orderBy($sort_column, $sort_order);
        $rows = $this->mahasiswa->paginate(10);

        $data = [
            'rows' => $rows,
            'pager' => $this->mahasiswa->pager,
            'jenis_user' => $jenis_user,
            'user_id' => $id,
            'pegawai_id' => $pegawai_id,
            'q' => $q,
            'sort_column' => $sort_column,
            'sort_order' => $sort_order,
        ];
        return view('p2m/p2m_mahasiswa', $data);
    }

    public function update_mahasiswa($id_mahasiswa)
    {
        $data = $this->request->getPost(['name', 'degree', 'NIM', 'researches ', 'community_services', 'thesis', 'publications']);
        $this->mahasiswa->update($id_mahasiswa, $data);

        return $this->response->redirect(site_url('p2m/p2m_mahasiswa'));
    }

    public function create_mahasiswa()
    {
        $row = new P2mMahasiswaModel();
        $data = [
            'action' => 'store_mahasiswa',
            'row' => $row,
        ];
        return view('p2m/form_mahasiswa', $data);
    }

    public function store_mahasiswa()
    {
        if ($this->request->getMethod() === 'post') {
            $data = $this->request->getPost([
                'name', 'degree', 'NIM', 'researches ', 'community_services', 'thesis', 'publications'
            ]);
            $data['department'] = json_encode($this->request->getPost('department'));

            $this->mahasiswa->insert($data);
            return redirect()->to(site_url('p2m/p2m_mahasiswa'));
        }

        return view('p2m/form_mahasiswa');
    }

    public function delete_mahasiswa($id_mahasiswa)
    {
        $db      = \Config\Database::connect();
        $row = $this->mahasiswa->select('p2m_tenaga_kependidikan.*');

        $row->where('id_mahasiswa', $id_mahasiswa);
        $row->delete();

        return redirect()->to('p2m/p2m_mahasiswa');
    }

    public function p2m_medmass()
    {
        $db = \Config\Database::connect();

        $data = $this->request->getGet();
        $q = $data['q'] ?? '';
        $status = $data['status'] ?? '';
        $sort_column = $data['sort_column'] ?? 'created_at';
        $sort_order = $data['sort_order'] ?? 'desc';

        $jenis_user = session('jenis_user');
        $id = session('id');
        $pegawai_id = session('pegawai_id');

        $this->mass_media->select('p2m_medmass.*')
            ->orderBy($sort_column, $sort_order);
        $rows = $this->mass_media->paginate(10);

        $data = [
            'rows' => $rows,
            'pager' => $this->mass_media->pager,
            'jenis_user' => $jenis_user,
            'user_id' => $id,
            'pegawai_id' => $pegawai_id,
            'q' => $q,
            'sort_column' => $sort_column,
            'sort_order' => $sort_order,
        ];
        return view('p2m/p2m_medmass', $data);
    }

    public function update_medmass($medmass_id)
    {
        $data = $this->request->getPost(['Name', 'url', 'level']);
        $this->mass_media->update($medmass_id, $data);

        return $this->response->redirect(site_url('p2m/p2m_medmass'));
    }

    public function create_medmass()
    {
        $row = new P2mMedmassModel();
        $data = [
            'action' => 'store_medmass',
            'row' => $row,
        ];
        return view('p2m/form_medmass', $data);
    }

    public function store_medmass()
    {
        if ($this->request->getMethod() === 'post') {
            $data = $this->request->getPost([
                'Name', 'url', 'level'
            ]);
            $data['department'] = json_encode($this->request->getPost('department'));

            $this->mass_media->insert($data);
            return redirect()->to(site_url('p2m/p2m_medmass'));
        }

        return view('p2m/form_medmass');
    }

    public function delete_medmass($medmass_id)
    {
        $db      = \Config\Database::connect();
        $row = $this->mass_media->select('p2m_medmass.*');

        $row->where('medmass_id', $medmass_id);
        $row->delete();

        return redirect()->to('p2m/p2m_medmass');
    }

    public function p2m_penerbit()
    {
        $db = \Config\Database::connect();

        $data = $this->request->getGet();
        $q = $data['q'] ?? '';
        $status = $data['status'] ?? '';
        $sort_column = $data['sort_column'] ?? 'created_at';
        $sort_order = $data['sort_order'] ?? 'desc';

        $jenis_user = session('jenis_user');
        $id = session('id');
        $pegawai_id = session('pegawai_id');

        $this->penerbit->select('p2m_penerbit.*')
            ->orderBy($sort_column, $sort_order);
        $rows = $this->penerbit->paginate(10);

        $data = [
            'rows' => $rows,
            'pager' => $this->penerbit->pager,
            'jenis_user' => $jenis_user,
            'user_id' => $id,
            'pegawai_id' => $pegawai_id,
            'q' => $q,
            'sort_column' => $sort_column,
            'sort_order' => $sort_order,
        ];
        return view('p2m/p2m_penerbit', $data);
    }

    public function update_penerbit($penerbit_id)
    {
        $data = $this->request->getPost(['Publisher', 'url', 'levels', 'journal ', 'conference']);
        $this->penerbit->update($penerbit_id, $data);

        return $this->response->redirect(site_url('p2m/p2m_penerbit'));
    }

    public function create_penerbit()
    {
        $row = new P2mPenerbitModel();
        $data = [
            'action' => 'store_penerbit',
            'row' => $row,
        ];
        return view('p2m/form_penerbit', $data);
    }

    public function store_penerbit()
    {
        if ($this->request->getMethod() === 'post') {
            $data = $this->request->getPost([
                'Publisher', 'url', 'levels', 'journal ', 'conference'
            ]);
            $data['department'] = json_encode($this->request->getPost('department'));

            $this->penerbit->insert($data);
            return redirect()->to(site_url('p2m/p2m_penerbit'));
        }

        return view('p2m/form_penerbit');
    }

    public function delete_penerbit($penerbit_id)
    {
        $db      = \Config\Database::connect();
        $row = $this->penerbit->select('p2m_penerbit.*');

        $row->where('penerbit_id', $penerbit_id);
        $row->delete();

        return redirect()->to('p2m/p2m_penerbit');
    }

    public function exportToExcel()
    {
        $db = \Config\Database::connect();

        // Mendapatkan nilai yang dimasukkan oleh pengguna pada website
        $value = $this->request->getPost('value');

        // Mengambil data pengabdian dari database menggunakan model atau metode yang sesuai
        $pengabdianModel = new P2mPengabdianModel();
        $data = $pengabdianModel->getPengabdianDataByValue($value); // Mengganti method dengan metode yang sesuai pada model Anda

        // Jika data kosong, tampilkan pesan
        if (empty($data)) {
            echo 'Tidak ada data yang sesuai dengan nilai yang dimasukkan.';
            return;
        }

        // Membuat objek Spreadsheet
        $spreadsheet = new Spreadsheet();

        // Mendapatkan sheet aktif
        $sheet = $spreadsheet->getActiveSheet();

        // Menulis header kolom
        $columns = ['department', 'team_leader', 'member_lecturer', 'member_academic_staff', 'member_student', 'title', 'funding_scheme_long', 'funding_scheme_short', 'jUmlah_dana', 'sumber_dana', 'delivery', 'kota', 'provinsi', 'time_start', 'time_end', 'data_source', 'proposal_date', 'fund_proposed', 'fund_accepted', 'acceptance_status', 'No_surat_tugas'];
        $columnIndex = 1;
        foreach ($columns as $column) {
            $sheet->setCellValueByColumnAndRow($columnIndex, 1, $column);
            $columnIndex++;
        }

        // Menulis data
        $rowIndex = 2;
        foreach ($data as $row) {
            $columnIndex = 1;
            foreach ($row as $cell) {
                // Mengganti tanda [""] dengan koma
                $cell = str_replace('","', ', ', $cell);
                $cell = trim($cell, '[""]');

                $sheet->setCellValueByColumnAndRow($columnIndex, $rowIndex, $cell);
                $columnIndex++;
            }
            $rowIndex++;
        }

        // Menyimpan file Excel
        $writer = new Xlsx($spreadsheet);
        $fileName = 'Pengabdian.xlsx'; // Nama file yang dihasilkan
        $filePath = WRITEPATH . 'uploads/' . $fileName; // Path untuk menyimpan file
        $writer->save($filePath);

        // Set header respons HTTP agar file dapat diunduh
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        readfile($filePath); // Mengirimkan file ke pengguna
        exit();
    }


    public function grafik_dosen()
    {
    }

    public function p2m_map()
    {
        $db = \Config\Database::connect();

        $data = $this->request->getGet();
        $q = $data['q'] ?? '';
        $status = $data['status'] ?? '';
        $sort_column = $data['sort_column'] ?? 'created_at';
        $sort_order = $data['sort_order'] ?? 'desc';

        $jenis_user = session('jenis_user');
        $id = session('id');
        $pegawai_id = session('pegawai_id');
        $pengabdian = $this->pengabdian->select('p2m_pengabdian.*')->get()->getResult();

        $row = $this->publikasi->select('p2m_publikasi.*, p2m_publikasi_authorship.*, p2m_eksternal.*, p2m_eksternal.country AS external_country')
            ->join('p2m_publikasi_authorship', 'p2m_publikasi_authorship.id_pub = p2m_publikasi.id_publikasi', 'left')
            ->join('p2m_eksternal', 'p2m_eksternal.eksternal_id = p2m_publikasi_authorship.id_authorship', 'left');
        $publikasi = $row->get()->getResult();


        $data = [
            'jenis_user' => $jenis_user,
            'user_id' => $id,
            'pegawai_id' => $pegawai_id,
            'q' => $q,
            'sort_column' => $sort_column,
            'sort_order' => $sort_order,
            'pengabdian' => $pengabdian,
            'publikasi' => $publikasi,
        ];
        return view('p2m/p2m_map', $data);
    }

    public function mapData()
    {
        // Load the model
        $pengabdianModel = new P2mPengabdianModel();

        // Get the address from the query string parameter
        $alamat = $this->request->getVar('alamat');

        // Extract the kota and provinsi from the alamat
        $addressParts = explode(',', $alamat);
        $kota = trim($addressParts[0]);
        $provinsi = trim($addressParts[1]);

        // Query the database to retrieve the desired data based on the kota and provinsi
        $query = $pengabdianModel->where('kota', $kota)
            ->where('provinsi', $provinsi)
            ->findAll();

        $numRows = count($query);

        if (!empty($query)) {
            $dataInfo = $query[0]->title;

            // Prepare the response data
            $response = [
                'dataInfo' => $dataInfo,
                'totalPengabdian' => $numRows
            ];

            // Return the response data as JSON
            return $this->response->setJSON($response);
        } else {
            // Return an error response if no data is found
            $response = [
                'error' => 'No data found'
            ];

            // Return the error response as JSON
            return $this->response->setJSON($response)->setStatusCode(404);
        }
    }


    public function p2m_update()
    {
        return view('p2m/p2m_update');
    }

    public function cmp_pub($a, $b)
    {
        return strcmp($a["title"], $b["title"]);
    }

    public function p2m_html()
    {
        $row = $this->dosen->select('p2m_dosen.dosenID, p2m_dosen.name')
            ->orderBy('p2m_dosen.name', 'ASC')
            ->findAll();
        $link_scholar = $this->publikasi->select('p2m_publikasi.link_scholar')
            ->findAll();
        $data = [
            'row' => $row,
            'link_scholar' => $link_scholar,
        ];
        if ($this->request->isAJAX()) {
            echo json_encode($data);
        } else {
            return view('p2m/p2m_html', $data);
        }
    }

    public function p2m_html_get_json()
    {
        $dutu = $this->request->getPost('selectedId');
        $row = $this->dosen->select('p2m_dosen.dosenID, p2m_dosen.name')
            ->orderBy('p2m_dosen.name', 'ASC')
            ->findAll();
        $link_scholar = $this->publikasi->select('p2m_publikasi.link_scholar, p2m_publikasi.judul_publikasi')
            ->join('p2m_publikasi_authorship', 'p2m_publikasi_authorship.id_pub = p2m_publikasi.id_publikasi')
            ->where('p2m_publikasi_authorship.lecturer_id', $dutu)
            ->findAll();
        $data = [
            'row' => $row,
            'link_scholar' => $link_scholar,
        ];
        echo json_encode($data);
    }

    public function p2m_pdf()
    {
        return view('p2m/p2m_pdf');
    }

    public function p2m_view_surat()
    {
        $data = $this->request->getGet();
        $q = $data['q'] ?? '';

        $jenis_user = session('jenis_user');
        $id = session('id');
        $pegawai_id = session('pegawai_id');

        $row = $this->surat->select('*')
            ->join('p2m_dosen', 'p2m_dosen.dosenID = p2m_surat.id_dosen', 'left')
            ->join('p2m_tenaga_kependidikan', 'p2m_tenaga_kependidikan.tendik_id = p2m_surat.id_tendik', 'left')
            ->join('p2m_mahasiswa', 'p2m_mahasiswa.id_mahasiswa = p2m_surat.id_mahasiswa', 'left')
            ->join('p2m_eksternal', 'p2m_eksternal.eksternal_id = p2m_surat.id_eksternal', 'left')
            ->where("CONCAT(p2m_dosen.name) LIKE '%$q%' OR CONCAT(p2m_tenaga_kependidikan.name) LIKE '%$q%' OR CONCAT(p2m_surat.jenis_surat) LIKE '%$q%'")
            ->paginate(10);
        // dump($row);
        $data = [
            'row' => $row,
            'pager' => $this->surat->pager,
            'q' => $q,
            'jenis_user' => $jenis_user,
            'user_id' => $id,
            'pegawai_id' => $pegawai_id,
        ];

        return view('p2m/p2m_view_surat', $data);
    }

    public function download_pdf_surat($id)
    {
        $data = $this->surat->select('jenis_surat, nama_file')->where('id_surat', $id)->first();
        $jenis_surat = $data->jenis_surat;
        $nama_surat = $data->nama_file;
        if ($jenis_surat == 'bukan keduanya') {
            $path = 'upload/p2m_surat_bukan_keduanya/' . $nama_surat;
        } else if ($jenis_surat == 'penelitian') {
            $path = 'upload/p2m_surat_penelitian/' . $nama_surat;
        } else if ($jenis_surat == 'pengabdian') {
            $path = 'upload/p2m_surat_pengabdian/' . $nama_surat;
        }

        if (isset($path) && is_file($path)) {
            return $this->response->download($path, null);
        } else {
            // Handle file not found
            return session()->setFlashdata('error', 'File tidak ditemukan');
        }
    }

    public function edit_surat($surat_id)
    {
        $row = (new P2mSuratModel)->where('id_surat', $surat_id)->first();
        $data = [
            'action' => 'update_surat',
            'row' => $row,
        ];
        return view('p2m/form_surat', $data);
    }

    public function update_surat($surat_id)
    {
        $data = $this->request->getPost(['jenis_surat', 'nama_file', 'tanggal_mulai', 'tanggal_selesai', 'tempat']);
        $this->surat->update($surat_id, $data);

        return $this->response->redirect(site_url('p2m/p2m_view_surat'));
    }


    public function p2m_scraping()
    {
        set_time_limit(300);
        $data = $this->request->getPost();
        // dump($data);
        $id_dosen = $data['dosen'];
        $html = $data['html'];
        $nama_dosen = $this->dosen->select('name')
            ->where('dosenID', $id_dosen)
            ->first()->name;
        $sinta_id = $this->dosen->select('sinta_id')
            ->where('dosenID', $id_dosen)
            ->first()->sinta_id;
        $scopus_id = $this->dosen->select('Scopus_ID')
            ->where('dosenID', $id_dosen)
            ->first()->Scopus_ID;
        $scholar_id = $this->dosen->select('Google_Scholar_ID')
            ->where('dosenID', $id_dosen)
            ->first()->Google_Scholar_ID;
        $sinta = $this->sinta($sinta_id);
        $scopus = $this->scopus($scopus_id);
        $scholar = $this->scholar($html, $nama_dosen, $scholar_id);
        $compare = json_decode($this->compare($scholar, $sinta, $scopus), true);
        // dump($compare);
        foreach ($compare['publications'] as $publication) {
            // Mengubah format tanggal eg: 2020/1/1 menjadi 2020-01-01
            $timestamp = strtotime($publication['date']);
            $date = date('Y-m-d', $timestamp);
            $data = [
                'name' => $compare['name'],
                'title' => $publication['title'] ?? null,
                'first_author' => $publication['first_author'] ?? null,
                'co_author' => $publication['co_author'] ?? null,
                'link_scopus' => $publication['link_scopus'] ?? null,
                'link_wos' => $publication['link_wos'] ?? null,
                'link_garuda' => $publication['link_garuda'] ?? null,
                'link_scholar' => $publication['link_scholar'] ?? null,
                'date' => $date ?? null,
                'journal' => $publication['journal'] ?? null,
                'doi' => $publication['doi'] ?? null,
                'rank' => $publication['rank'] ?? null,
                'volume' => $publication['volume'] ?? null,
                'issue' => $publication['issue'] ?? null,
                'pages' => $publication['pages'] ?? null,
                'conference' => $publication['conference'] ?? null,
                'publisher' => $publication['publisher'] ?? null,
                'description' => $publication['description'] ?? null,
                'citation' => json_encode($publication['citation_per_year']) ?? null
            ];
            $publications[] = $data;
        }

        // // Insert atau update ke dalam database 
        // if (!empty($publications)) {
        $compare_publikasi = $this->publikasi->select("*")
            ->orderBy('judul_publikasi', 'ASC')
            ->paginate(10000000);
        $updateData = [];
        $insertData = [];
        usort($publications, array($this, 'cmp_pub'));
        // dump($publications);
        $i = 0;
        $j = 0;
        while ($i < count($publications) && $j < count($compare_publikasi)) {
            if ($publications[$i]['title'] == $compare_publikasi[$j]->judul_publikasi) {
                if ($publications[$i]['citation'] != $compare_publikasi[$j]->sitasi_per_tahun) {
                    $updateData[] = [
                        'id_publikasi' => $compare_publikasi[$j]->id_publikasi,
                        'sitasi_per_tahun' => $publications[$i]['citation']
                    ];
                } elseif ($publications[$i]['description'] == $compare_publikasi[$j]->deskripsi && $publications[$i]['description']) {
                    $updateDraft[] = [
                        'description' => $publications[$i]['description'],
                        'citation' => $publications[$i]['citation'],
                        'status' => 0
                    ];
                }
                $i++;
                $j++;
            } elseif ($publications[$i]['title'] < $compare_publikasi[$j]->judul_publikasi) {
                $id_jurnal = $this->journal->select('p2m_jurnal.jurnal_id')
                    ->where('name', $publications[$i]['journal'])
                    ->first();
                $id_penerbit = $this->penerbit->select('p2m_penerbit.penerbit_id')
                    ->where('Publisher', $publications[$i]['publisher'])
                    ->first();
                if ($id_jurnal == NULL) {
                    if ($publications[$i]['rank'] != null) {
                        if (substr($publications[$i]['rank'], 0, 1) == 'Q') {
                            $index_rank = substr($publications[$i]['rank'], 0, 2) != 'No' ? substr($publications[$i]['rank'], 0, 2) : NULL;
                            $indexer = 'SCIMAGOJR';
                            $level = 'international reputable';
                        } elseif (substr($publications[$i]['rank'], 0, 1) == 'S') {
                            $index_rank = substr($publications[$i]['rank'], 0, 2) != 'No' ? substr($publications[$i]['rank'], 0, 2) : NULL;
                            $indexer = 'SINTA';
                            $level = 'national reputable';
                        }
                    } else {
                        $index_rank = NULL;
                        $indexer = NULL;
                        $level = NULL;
                    }

                    $data_jurnal = [
                        'name' => $publications[$i]['journal'] ?? NULL,
                        'levels' => $level ?? NULL,
                        'indexer' => $indexer ?? NULL,
                        'index_rank' => $index_rank ?? NULL,
                    ];
                    if ($publications[$i]['journal'] != NULL) {
                        $this->journal->insert($data_jurnal);
                        $id_jurnal = $this->journal->select('p2m_jurnal.jurnal_id')
                            ->where('name', $publications[$i]['journal'])
                            ->first();
                    }
                }
                if ($id_penerbit == NULL) {
                    $data_penerbit = [
                        'Publisher' => $publications[$i]['publisher'] ?? NULL,
                    ];
                    if ($publications[$i]['publisher'] != NULL) {
                        $this->penerbit->insert($data_penerbit);
                        $id_penerbit = $this->penerbit->select('p2m_penerbit.penerbit_id')
                            ->where('Publisher', $publications[$i]['publisher'])
                            ->first();
                    }
                }

                $insertData[] = [
                    'judul_publikasi' => $publications[$i]['title'],
                    'tanggal_publikasi' => $publications[$i]['date'],
                    'doi' => $publications[$i]['doi'],
                    'link_scopus' => $publications[$i]['link_scopus'],
                    'link_wos' => $publications[$i]['link_wos'],
                    'link_garuda' => $publications[$i]['link_garuda'],
                    'link_scholar' => $publications[$i]['link_scholar'],
                    'volume' => $publications[$i]['volume'],
                    'issue' => $publications[$i]['issue'],
                    'halaman' => $publications[$i]['pages'],
                    'deskripsi' => $publications[$i]['description'],
                    'sitas_per_tahun' => $publications[$i]['citation'],
                    'is_journal' => $publications[$i]['journal'] != NULL ? 'checked' : NULL,
                    'is_conference' => $publications[$i]['conference'] != NULL ? 'checked' : NULL,
                    'is_mass_media' => NULL,
                    'id_jurnal' => $id_jurnal->jurnal_id ?? NULL,
                    'id_conference' => NULL,
                    'id_penerbit' => $id_penerbit->penerbit_id ?? NULL,
                    'id_mass_media' => NULL
                ];
                $this->publikasi->insert($insertData[$i]);

                // ambil id publikasi yang baru saja diinsert
                $id_publikasi = $this->publikasi->select('p2m_publikasi.id_publikasi')
                    ->where('judul_publikasi', $publications[$i]['title'])
                    ->first();

                // Insert data ke publikasi_author
                if (!empty($publications[$i]['first_author'])) {
                    // compare to all table whether the author is student, lecturer, external, or academic
                    // asumsi seluruh data sudah mencakup seluruh dosen, mahasiswa, dan tenaga kependidikan di FMIPA
                    $author_id = $this->p2m_get_author_id($publications[$i]['first_author']);

                    $data_publikasi_first_author = [
                        'id_pub' => $id_publikasi->id_publikasi ?? NULL,
                        'lecturer_id' => $author_id['lecturer_id'] ?? NULL,
                        'mahasiswa_id' => $author_id['id_mahasiswa'] ?? NULL,
                        'acad_id' => $author_id['tendik_id'] ?? NULL,
                        'external_id' => $author_id['eksternal_id'] ?? NULL,
                        'is_main_author' => 'checked',
                        'is_lecturer_author' => $author_id['lecturer_id'] != NULL ? 'checked' : NULL,
                        'is_academic_staff_author' => $author_id['id_mahasiswa'] != NULL ? 'checked' : NULL,
                        'is_student_author' => $author_id['tendik_id'] != NULL ? 'checked' : NULL,
                        'is_external_author' => $author_id['eksternal_id'] != NULL ? 'checked' : NULL
                    ];

                    $this->author->insert($data_publikasi_first_author);
                }

                if (!empty($publications[$i]['co_author'])) {
                    if (strpos($publications[$i]['co_author'], ', ') !== false) {
                        $co_authors = explode(', ', $publications[$i]['co_author']);
                        foreach ($co_authors as $co_author) {
                            $author_id = $this->p2m_get_author_id($co_author);
                            $data_publikasi_co_author = [
                                'id_pub' => $id_publikasi->id_publikasi ?? NULL,
                                'lecturer_id' => $author_id['lecturer_id'] ?? NULL,
                                'mahasiswa_id' => $author_id['id_mahasiswa'] ?? NULL,
                                'acad_id' => $author_id['tendik_id'] ?? NULL,
                                'external_id' => $author_id['eksternal_id'] ?? NULL,
                                'is_main_author' => NULL,
                                'is_lecturer_author' => $author_id['lecturer_id'] != NULL ? 'checked' : NULL,
                                'is_academic_staff_author' => $author_id['id_mahasiswa'] != NULL ? 'checked' : NULL,
                                'is_student_author' => $author_id['tendik_id'] != NULL ? 'checked' : NULL,
                                'is_external_author' => $author_id['eksternal_id'] != NULL ? 'checked' : NULL
                            ];
                            $this->author->insert($data_publikasi_co_author);
                        }
                    } else {
                        $author_id = $this->p2m_get_author_id($publications[$i]['co_author']);
                        $data_publikasi_co_author = [
                            'id_pub' => $id_publikasi->id_publikasi ?? NULL,
                            'lecturer_id' => $author_id['lecturer_id'] ?? NULL,
                            'mahasiswa_id' => $author_id['id_mahasiswa'] ?? NULL,
                            'acad_id' => $author_id['tendik_id'] ?? NULL,
                            'external_id' => $author_id['eksternal_id'] ?? NULL,
                            'is_main_author' => NULL,
                            'is_lecturer_author' => $author_id['lecturer_id'] != NULL ? 'checked' : NULL,
                            'is_academic_staff_author' => $author_id['id_mahasiswa'] != NULL ? 'checked' : NULL,
                            'is_student_author' => $author_id['tendik_id'] != NULL ? 'checked' : NULL,
                            'is_external_author' => $author_id['eksternal_id'] != NULL ? 'checked' : NULL
                        ];
                        $this->author->insert($data_publikasi_co_author);
                    }
                }
                $i++;
            } elseif ($publications[$i]['title'] > $compare_publikasi[$j]->judul_publikasi) {
                $j++;
            }
        }

        if (!empty($updateData)) {
            $this->publikasi->updateBatch($updateData, 'id_publikasi');
        }

        $id_dosen = $this->dosen->select('dosenID')->where('sinta_id', $compare['sinta_id'])->first();
        $compare_score = $this->score->select("*")
            ->where('dosen_id', $id_dosen->dosenID)->first();

        $data_score = [
            'dosen_id' => $id_dosen->dosenID,
            'tahun' => date('Y'),
            'sinta_q1' => date('n') == (date('n') > 0 && date('n') <= 3) ? $compare['sinta_score'] : ($compare_score->sinta_q1 ?? null),
            'sinta_q2' => date('n') == (date('n') > 3 && date('n') <= 6) ? $compare['sinta_score'] : ($compare_score->sinta_q2 ?? null),
            'sinta_q3' => date('n') == (date('n') > 6 && date('n') <= 9) ? $compare['sinta_score'] : ($compare_score->sinta_q3 ?? null),
            'sinta_q4' => date('n') == (date('n') > 9 && date('n') <= 12) ? $compare['sinta_score'] : ($compare_score->sinta_q4 ?? null),
            'h_index_q1' => date('n') == (date('n') > 0 && date('n') <= 3) ? $compare['scopus_h-index'] : ($compare_score->h_index_q1 ?? null),
            'h_index_q2' => date('n') == (date('n') > 3 && date('n') <= 6) ? $compare['scopus_h-index'] : ($compare_score->h_index_q2 ?? null),
            'h_index_q3' => date('n') == (date('n') > 6 && date('n') <= 9) ? $compare['scopus_h-index'] : ($compare_score->h_index_q3 ?? null),
            'h_index_q4' => date('n') == (date('n') > 9 && date('n') <= 12) ? $compare['scopus_h-index'] : ($compare_score->h_index_q4 ?? null)
        ];

        $score[] = $data_score;

        // asumsi jika sistem digunakan 1 bulan sekali
        if (!empty($score)) {
            if ($compare_score != NULL) {
                $this->score->updateBatch($score, 'dosen_id');
            } else {
                $this->score->insertBatch($score);
            }
        }
        return $this->response->redirect(site_url('p2m/p2m_html'));
    }

    public function p2m_draft()
    {
        $db = \Config\Database::connect();

        $data = $this->request->getGet();
        $q = $data['q'] ?? '';
        $status = $data['status'] ?? '';
        $sort_column = $data['sort_column'] ?? 'created_at';
        $sort_order = $data['sort_order'] ?? 'desc';

        $jenis_user = session('jenis_user');
        $id = session('id');
        $pegawai_id = session('pegawai_id');
        $row = $this->draft->select('p2m_draft_publikasi.*')
            ->where('p2m_draft_publikasi.status', '0')
            // ->where("CONCAT(name) LIKE '%$q%' OR CONCAT(title) LIKE '%$q%' OR CONCAT(first_author) LIKE '%$q%' OR CONCAT(co_author) LIKE '%$q%' OR CONCAT(link_scopus) LIKE '%$q%' OR CONCAT(link_wos) LIKE '%$q%' OR CONCAT(link_garuda) LIKE '%$q%' OR CONCAT(link_scholar) LIKE '%$q%' OR CONCAT(date) LIKE '%$q%' OR CONCAT(journal) LIKE '%$q% OR CONCAT(doi) LIKE '%$q% OR CONCAT(rank) LIKE '%$q% OR CONCAT(publisher) LIKE '%$q%'")
            ->orderBy($sort_column, $sort_order);
        $rows = $row->paginate(10);
        foreach ($rows as $row) {
            $row->citation = json_decode($row->citation);
            $row->citation = array_reverse($row->citation);
        }
        // dump($rows);
        $data = [
            'rows' => $rows,
            'pager' => $this->draft->pager,
            'jenis_user' => $jenis_user,
            'user_id' => $id,
            'pegawai_id' => $pegawai_id,
            'q' => $q,
            'sort_column' => $sort_column,
            'sort_order' => $sort_order,
        ];

        return $this->response->redirect(site_url('p2m/p2m_draft'));
    }

    public function p2m_approve_data($id)
    {
        $db = \Config\Database::connect();
        $row = $this->draft->select('p2m_draft_publikasi.*')
            ->where('id', $id)
            ->first();
        // dump($row);
        $compare_judul_publikasi = $this->publikasi->select('p2m_publikasi.*')
            ->where('judul_publikasi', $row->title)
            ->first();
        // dump($judul_publikasi);
        $id_jurnal = $this->journal->select('p2m_jurnal.jurnal_id')
            ->where('name', $row->journal)
            ->first();
        $id_penerbit = $this->penerbit->select('p2m_penerbit.penerbit_id')
            ->where('Publisher', $row->publisher)
            ->first();
        if ($compare_judul_publikasi != NULL) {
            echo "sudah ada";
        } else {
            if ($id_jurnal == NULL) {
                if (substr($row->rank, 0, 1) == 'Q') {
                    $index_rank = substr($row->rank, 0, 2) != 'No' ? substr($row->rank, 0, 2) : NULL;
                    $indexer = 'SCIMAGOJR';
                    $level = 'international reputable';
                } elseif (substr($row->rank, 0, 1) == 'S') {
                    $index_rank = substr($row->rank, 0, 2) != 'No' ? substr($row->rank, 0, 2) : NULL;
                    $indexer = 'SINTA';
                    $level = 'national reputable';
                }
                $data_jurnal = [
                    'name' => $row->journal ?? NULL,
                    'levels' => $level ?? NULL,
                    'indexer' => $indexer ?? NULL,
                    'index_rank' => $index_rank ?? NULL,
                ];
                // dump($data_jurnal);
                $this->journal->insert($data_jurnal);
                $id_jurnal = $this->journal->select('p2m_jurnal.jurnal_id')
                    ->where('name', $row->journal)
                    ->first();
                dump($id_jurnal);
            }
            if ($id_penerbit == NULL) {
                $data_penerbit = [
                    'Publisher' => $row->publisher ?? NULL,
                ];
                $this->penerbit->insert($data_penerbit);
                $id_penerbit = $this->penerbit->select('p2m_penerbit.penerbit_id')
                    ->where('Publisher', $row->publisher)
                    ->first();
                // dump($id_penerbit);
            }

            // Mengubah format tanggal eg: 2020/1/1 menjadi 2020-01-01
            $timestamp = strtotime($row->date);
            $date = date('Y-m-d', $timestamp);

            $data_publikasi = [
                'judul_publikasi' => $row->title ?? NULL,
                'tanggal_publikasi' => $date ?? NULL,
                'doi' => $row->doi ?? NULL,
                'link_scopus' => $row->link_scopus ?? NULL,
                'link_wos' => $row->link_wos ?? NULL,
                'link_garuda' => $row->link_garuda ?? NULL,
                'link_scholar' => $row->link_scholar ?? NULL,
                'volume' => $row->volume ?? NULL,
                'issue' => $row->issue ?? NULL,
                'halaman' => $row->pages ?? NULL,
                'deskripsi' => $row->description ?? NULL,
                'sitasi_per_tahun' => $row->citation ?? NULL,
                'is_journal' => $row->journal != NULL ? 'checked' : NULL,
                'is_conference' => NULL,
                'is_mass_media' => NULL,
                'id_jurnal' => $id_jurnal->jurnal_id ?? NULL,
                'id_conference' => NULL,
                'id_penerbit' => $id_penerbit->penerbit_id ?? NULL,
                'id_mass_media' => NULL
            ];
            // dump($data_publikasi);
            $this->publikasi->insert($data_publikasi);

            // ambil id publikasi yang baru saja diinsert
            $id_publikasi = $this->publikasi->select('p2m_publikasi.id_publikasi')
                ->where('judul_publikasi', $row->title)
                ->first();

            // Insert data ke publikasi_author
            if (!empty($row->first_author)) {
                // compare to all table whether the author is student, lecturer, external, or academic
                // asumsi seluruh data sudah mencakup seluruh dosen, mahasiswa, dan tenaga kependidikan di FMIPA
                $author_id = $this->p2m_get_author_id($row->first_author);

                $data_publikasi_first_author = [
                    'id_pub' => $id_publikasi->id_publikasi ?? NULL,
                    'lecturer_id' => $author_id['lecturer_id'] ?? NULL,
                    'mahasiswa_id' => $author_id['id_mahasiswa'] ?? NULL,
                    'acad_id' => $author_id['tendik_id'] ?? NULL,
                    'external_id' => $author_id['eksternal_id'] ?? NULL,
                    'is_main_author' => 'checked',
                    'is_lecturer_author' => $author_id['lecturer_id'] != NULL ? 'checked' : NULL,
                    'is_academic_staff_author' => $author_id['id_mahasiswa'] != NULL ? 'checked' : NULL,
                    'is_student_author' => $author_id['tendik_id'] != NULL ? 'checked' : NULL,
                    'is_external_author' => $author_id['eksternal_id'] != NULL ? 'checked' : NULL
                ];

                $this->author->insert($data_publikasi_first_author);
            }

            if (!empty($row->co_author)) {
                if (strpos($row->co_author, ', ') !== false) {
                    $co_authors = explode(', ', $row->co_author);
                    foreach ($co_authors as $co_author) {
                        $author_id = $this->p2m_get_author_id($co_author);
                        $data_publikasi_co_author = [
                            'id_pub' => $id_publikasi->id_publikasi ?? NULL,
                            'lecturer_id' => $author_id['lecturer_id'] ?? NULL,
                            'mahasiswa_id' => $author_id['id_mahasiswa'] ?? NULL,
                            'acad_id' => $author_id['tendik_id'] ?? NULL,
                            'external_id' => $author_id['eksternal_id'] ?? NULL,
                            'is_main_author' => NULL,
                            'is_lecturer_author' => $author_id['lecturer_id'] != NULL ? 'checked' : NULL,
                            'is_academic_staff_author' => $author_id['id_mahasiswa'] != NULL ? 'checked' : NULL,
                            'is_student_author' => $author_id['tendik_id'] != NULL ? 'checked' : NULL,
                            'is_external_author' => $author_id['eksternal_id'] != NULL ? 'checked' : NULL
                        ];
                        $this->author->insert($data_publikasi_co_author);
                    }
                } else {
                    $author_id = $this->p2m_get_author_id($row->co_author);
                    $data_publikasi_co_author = [
                        'id_pub' => $id_publikasi->id_publikasi ?? NULL,
                        'lecturer_id' => $author_id['lecturer_id'] ?? NULL,
                        'mahasiswa_id' => $author_id['id_mahasiswa'] ?? NULL,
                        'acad_id' => $author_id['tendik_id'] ?? NULL,
                        'external_id' => $author_id['eksternal_id'] ?? NULL,
                        'is_main_author' => NULL,
                        'is_lecturer_author' => $author_id['lecturer_id'] != NULL ? 'checked' : NULL,
                        'is_academic_staff_author' => $author_id['id_mahasiswa'] != NULL ? 'checked' : NULL,
                        'is_student_author' => $author_id['tendik_id'] != NULL ? 'checked' : NULL,
                        'is_external_author' => $author_id['eksternal_id'] != NULL ? 'checked' : NULL
                    ];
                    $this->author->insert($data_publikasi_co_author);
                }

                // dump($data_publikasi_co_author);
            }

            $this->draft->update($id, ['status' => '1']);
        }
        return $this->response->redirect(site_url('p2m/p2m_draft'));
    }

    public function p2m_decline_data($id)
    {
        $this->draft->update($id, ['status' => '-1']);
    }

    public function p2m_get_author_id(string $author_name): array
    {
        $db = \Config\Database::connect();
        $tables = ['p2m_dosen', 'p2m_mahasiswa', 'p2m_tenaga_kependidikan', 'p2m_eksternal'];
        $found_table = null;
        $id_dosen = null;
        $id_mahasiswa = null;
        $id_acad = null;
        $id_external = null;
        $like_author_name = str_replace(' ', '%', $author_name);
        if (strpos(' ', $author_name) == true) {
            $exploded = explode(' ', $author_name);
            // jika nama depan dan nama belakang sama
            if ($exploded[0] == $exploded[1]) {
                $authorName = $exploded[0];
            } else {
                $authorName = implode(' ', $exploded);
            }
        } else {
            $authorName = $author_name;
        }

        if (strpos(',', $authorName) == true) {
            preg_match('/([^,]+)/', $authorName, $matches);
            $authorName = $matches[1];
        }

        foreach ($tables as $table) {
            $sql = 'SELECT `name` FROM ' . $table . ' WHERE `name` LIKE "%' . $like_author_name . '%" OR `name` LIKE "%' . $authorName . '%"';
            $query = $db->query($sql, $authorName);
            $result = $query->getResult();

            if ($result == null) {
                continue;
            } else {
                $found_table = $table;
                break;
            }
        }
        // dump($found_table);
        if ($found_table == 'p2m_dosen') {
            $id_dosen = $this->dosen->select('p2m_dosen.dosenID')
                ->where('name LIKE "%' . $like_author_name . '%"')
                ->first();
        }

        if ($found_table == 'p2m_mahasiswa') {
            $id_mahasiswa = $this->mahasiswa->select('p2m_mahasiswa.id_mahasiswa')
                ->where('name LIKE "%' . $like_author_name . '%"')
                ->first();
        }

        if ($found_table == 'p2m_tenaga_kependidikan') {
            $id_acad = $this->acad->select('p2m_tenaga_kependidikan.tendik_id')
                ->where('name LIKE "%' . $like_author_name . '%"')
                ->first();
        }

        if ($found_table == 'p2m_eksternal') {
            $id_external = $this->external->select('eksternal_id')
                ->where('name LIKE "%' . $like_author_name . '%"')
                ->first();
        }

        if ($found_table == null) {
            // $this->external->insert(['name' => $author_name]);
            $id_external = $this->external->select('p2m_eksternal.eksternal_id')
                ->where('name', $author_name)
                ->first();
        }

        // dump($id_external);
        return [
            'lecturer_id' => $id_dosen->dosenID ?? null,
            'id_mahasiswa' => $id_mahasiswa->id_mahasiswa ?? null,
            'tendik_id' => $id_acad->tendik_id ?? null,
            'eksternal_id' => $id_external->eksternal_id ?? null,
        ];
    }

    public function scholar(array $links_content, $name, $author_id)
    {
        if (!empty($links_content)) {
            foreach ($links_content as $link_content) {
                $crawler = new Crawler($link_content);
                $fields = $crawler->filter('div.gsc_oci_field');
                $values = $crawler->filter('div.gsc_oci_value');
                $link = $crawler->filter('#gsc_vcpb')->attr('data-edit-link');
                $link = str_replace('edit', 'view', $link);
                $link_scholar = 'https://scholar.google.com' . $link;
                $titleNodeList = $crawler->filter('div.gsc_oci_merged_snippet a');
                if ($titleNodeList->count() > 0) {
                    $title = $titleNodeList->first()->text();
                } else {
                    $title_a = $crawler->filter('div#gsc_oci_title a');
                    $title_only = $crawler->filter('div#gsc_oci_title');
                    $title_wrapper = $crawler->filter('div#gsc_oci_title_wrapper');
                    if ($title_a->count() > 0) {
                        $title = $title_a->text();
                    } elseif ($title_only->count() > 0) {
                        $title = $title_only->text();
                    } elseif ($title_wrapper->count() > 0) {
                        $title = $title_wrapper->text();
                    }
                }
                $details = [];
                $fields->each(function (Crawler $field, $i) use ($values, &$details) {
                    $value = $values->eq($i);
                    $details[] = $field->text() . ": " . $value->text();
                });
                foreach ($details as $detail) {
                    if (strpos($detail, "Authors: ") === 0) {
                        $authors = substr($detail, strlen("Authors: "));
                        $authors_array = explode(", ", $authors);
                        $first_author = trim($authors_array[0]);
                        $co_author = array_slice($authors_array, 1);
                    } elseif (strpos($detail, "Publication date: ") === 0) {
                        $pub_date = substr($detail, strlen("Publication date: "));
                    } elseif (strpos($detail, "Journal: ") === 0) {
                        $journal = substr($detail, strlen("Journal: "));
                    } elseif (strpos($detail, "Conference: ") === 0) {
                        $conference = substr($detail, strlen("Conference: "));
                    } elseif (strpos($detail, "Volume: ") === 0) {
                        $volume = substr($detail, strlen("Volume: "));
                    } elseif (strpos($detail, "Issue: ") === 0) {
                        $issue = substr($detail, strlen("Issue: "));
                    } elseif (strpos($detail, "Pages: ") === 0) {
                        $pages = substr($detail, strlen("Pages: "));
                    } elseif (strpos($detail, "Publisher: ") === 0) {
                        $publisher = substr($detail, strlen("Publisher: "));
                    } elseif (strpos($detail, "Description: ") === 0) {
                        $description = substr($detail, strlen("Description: "));
                    }
                }
                $cit_per_year = $crawler->filter('a.gsc_oci_g_a');
                $cit_values = $crawler->filter('span.gsc_oci_g_al');
                $citations = [];
                $cit_per_year->each(function (Crawler $cit_per_year, $i) use ($cit_values, &$citations) {
                    $cit_value = $cit_values->eq($i);
                    $citations[] = substr($cit_per_year->attr('href'), -4) . ": " . $cit_value->text();
                });
                $test_content = [
                    'title' => $title ?? null,
                    'first_author' => $first_author ?? null,
                    'co_author' => implode(", ", $co_author) ?? null,
                    'link_scopus' => null,
                    'link_wos' => null,
                    'link_garuda' => null,
                    'link_scholar' => $link_scholar ?? null,
                    'date' => $pub_date ?? null,
                    'journal' => $journal ?? null,
                    'conference' => $conference ?? null,
                    'doi' => null,
                    'rank' => null,
                    'volume' => $volume ?? null,
                    'issue' => $issue ?? null,
                    'pages' => $pages ?? null,
                    'publisher' => $publisher ?? null,
                    'description' => $description ?? null,
                    'citation_per_year' => $citations ?? ''
                ];
                $full_content[] = $test_content;
            }
        }
        $final_array = [
            'name' => $name,
            'scholar_id' => $author_id,
            'sinta_id' => '',
            'sinta_score' => '',
            'scopus_h-index' => '',
            'scholar_h-index' => '',
            'publications' => $full_content
        ];

        return $final_array;
    }

    public function sinta($author_id)
    {
        $id_dosen = $this->dosen->select('dosenID')->where('sinta_id', $author_id)->first();
        // Create a new Guzzle client instance
        $client = new Client(['cookies' => true]);

        // Set the login form fields
        $fields = [
            'username' => getenv('WEBSITE_USERNAME'),
            'password' => getenv('WEBSITE_PASSWORD')
        ];

        // Send the POST request
        $response = $client->post('https://sinta.kemdikbud.go.id/logins/do_login', [
            'form_params' => $fields
        ]);

        $views = ['scopus', 'wos', 'garuda', 'googlescholar'];
        $total_pages = [];
        $k = 0;
        foreach ($views as $view) {
            $response = $client->get('https://sinta.kemdikbud.go.id/authors/profile/' . $author_id . '?view=' . $view)->getBody()->getContents();
            $div_pagination = '<div class="text-center pagination-text">';
            // Do something with the response
            if (strpos($response, $div_pagination) !== false) {
                $crawler = new Crawler($response);
                $page = $crawler->filter('div.text-center.pagination-text')->text();

                // Check the page number of an author
                $text = strpos($page, 'of ');

                if ($text !== false) {
                    $text += 3;
                    $space = strpos($page, ' ', $text);
                    $total = substr($page, $text, $space - $text);
                }
                $total_pages[$k] = $total;
                $k++;
                continue;
            } else {
                $total_pages[$k] = 1;
                $k++;
                continue;
            }
        }

        // Define the variable for the loop and array to save the response
        $j = 0;
        $b = 0;
        $curls_hasil = [];

        // Looping the page
        foreach ($views as $view) {
            $view_curls = [];
            for ($i = 1; $i <= $total_pages[$b]; $i++) {
                $response = $client->get('https://sinta.kemdikbud.go.id/authors/profile/' . $author_id . '?page=' . $i . '&view=' . $view)->getBody()->getContents();
                $view_curls[$j] = $response;
                $j++;
            }
            $curl_hasil = array(
                'view' => $view,
                'curls' => $view_curls
            );
            $curls_hasil[] = $curl_hasil;
            $b++;
        }

        // file_put_contents('curl_hasil.json', json_encode($curls_hasil, JSON_PRETTY_PRINT));

        $scopus_curl = [];
        $wos_curl = [];
        $garuda_curl = [];
        $scholar_curl = [];

        foreach ($curls_hasil as $curl_hasil) {
            $view = $curl_hasil['view'];
            $curls = $curl_hasil['curls'];

            foreach ($curls as $curl) {
                $crawler = new Crawler($curl);
                $div_publication = '<div class="ar-list-item mb-5">';
                if (strpos($curl, $div_publication) === false) {
                    if ($view == 'scopus') {
                        $scopus_curl = [];
                    } elseif ($view == 'wos') {
                        $wos_curl = [];
                    } elseif ($view == 'garuda') {
                        $garuda_curl = [];
                    } elseif ($view == 'googlescholar') {
                        $scholar_curl = [];
                    }
                    continue;
                } else {
                    $publications = $crawler->filter('div.ar-list-item.mb-5');
                    foreach ($publications as $publication) {
                        $publication_crawler = new Crawler($publication);
                        $title = $publication_crawler->filter('div.ar-title')->text();
                        $link = $publication_crawler->filter('div.ar-title a')->attr('href');
                        if ($view == 'scopus') {
                            $ranking = $publication_crawler->filter('a.ar-quartile');
                            if ($ranking->count() > 0) {
                                $ranking = $ranking->text();
                            } else {
                                $ranking = '';
                            }
                        } elseif ($view == 'wos') {
                            $ranking = $publication_crawler->filter('a.ar-quartile.mr-3');
                            if ($ranking->count() > 0) {
                                $ranking = $ranking->text();
                            } else {
                                $ranking = '';
                            }
                        }
                        $ranking = ($view == 'scopus' || $view == 'wos') ? $ranking : '';
                        if ($view == 'wos') {
                            $journal = $publication_crawler->filter('a.ar-pub[target]')->text();
                        } else {
                            $journal = $publication_crawler->filter('a.ar-pub')->text();
                        }
                        $year = $publication_crawler->filter('a.ar-year')->text();
                        $year = ($year != '0000') ? $year : null;

                        $publication_data = array(
                            'title' => $title ?? null,
                            // 'authors' => '',
                            'link' => $link ?? null,
                            'journal' => $journal ?? null,
                            'rank' => $ranking ?? null,
                            'year' => $year ?? null,
                            'title' => $title ?? null,
                            'first_author' => null,
                            'co_author' => null,
                            'link_scopus' => null,
                            'link_wos' => null,
                            'link_garuda' => null,
                            'link_scholar' => null,
                            'date' => null,
                            'journal' => null,
                            'conference' => null,
                            'doi' => null,
                            'rank' => null,
                            'volume' => null,
                            'issue' => null,
                            'pages' => null,
                            'publisher' => null,
                            'description' => null,
                            'citation_per_year' => null
                        );

                        if ($view == 'scopus') {
                            $scopus_curl[] = $publication_data;
                        } elseif ($view == 'wos') {
                            $wos_curl[] = $publication_data;
                        } elseif ($view == 'garuda') {
                            $garuda_curl[] = $publication_data;
                        } elseif ($view == 'googlescholar') {
                            $scholar_curl[] = $publication_data;
                        }
                    }
                }
            }
        }

        $response = $client->get('https://sinta.kemdikbud.go.id/authors/profile/' . $author_id)->getBody()->getContents();
        $crawler = new Crawler($response);
        $authors_detail = $crawler->filter('div.col-lg.col-md');
        $sinta_score = $crawler->filter('div.pr-num')->text();
        $hindex = $crawler->filter('tr:contains("H-Index")');
        $scopus_hindex = $hindex->filter('td.text-warning')->text();
        $scholar_hindex = $hindex->filter('td.text-success')->text();
        foreach ($authors_detail as $author_detail) {
            $author_detail_crawler = new Crawler($author_detail);
            $author_name = $author_detail_crawler->filter('h3')->text();
        }

        $author_publication = [
            'scopus' => $scopus_curl,
            'wos' => $wos_curl,
            'garuda' => $garuda_curl,
            'scholar' => $scholar_curl
        ];

        $all_curl = array(
            'author_name' => $author_name,
            'sinta_id' => $author_id,
            'sinta_score' => $sinta_score,
            'scopus_h-index' => $scopus_hindex,
            'scholar_h-index' => $scholar_hindex,
            'publication' => $author_publication
        );

        return $all_curl;
    }

    public function scopus($author_id)
    {
        $id_dosen = $this->dosen->select('dosenID')->where('Scopus_ID', $author_id)->first();
        try {
            $client = new Client();
            $response = $client->request(
                'GET',
                'https://api.elsevier.com/content/search/scopus',
                [
                    'query' => [
                        'query' => 'AU-ID(' . $author_id . ')',
                        'start' => 0,
                        // 'count' => 200, // kalo pake wifi ugm
                        'count' => 25, // kalo pake wifi rumah
                        'apiKey' => getenv('API_KEY'),
                        'httpAccept' => 'application/json'
                    ]
                ]
            );

            $responseBody = json_decode((string) $response->getBody());
            if ($responseBody === null) {
                return 'Error: Invalid response format';
            } else {
                return $responseBody;
            }
        } catch (ClientException $e) {
            if ($e->hasResponse()) {
                $responseBody = (string) $e->getResponse()->getBody();
                $statusCode = $e->getResponse()->getStatusCode();
                return "Error: $statusCode - $responseBody";
            } else {
                return 'Error: ' . $e->getMessage();
            }
        }
    }

    public function compare($scholar, $sinta, $scopus)
    {
        $data_scholar = $scholar;

        $data_sinta = $sinta;
        // dump($data_sinta);

        $data_scopus = $scopus;

        if ($data_scholar != null) {
            $data_scholar['sinta_id'] = &$data_sinta['sinta_id'];
            $data_scholar['sinta_score'] = &$data_sinta['sinta_score'];
            $data_scholar['scopus_h-index'] = &$data_sinta['scopus_h-index'];
            $data_scholar['scholar_h-index'] = &$data_sinta['scholar_h-index'];
        } else {
            $data_scholar = [
                'name' => '',
                'scholar_id' => '',
                'sinta_id' => '',
                'sinta_score' => '',
                'scopus_h-index' => '',
                'scholar_h-index' => '',
                'publications' => []
            ];

            $data_scholar['name'] = &$data_sinta['author_name'];
            $data_scholar['sinta_id'] = &$data_sinta['sinta_id'];
            $data_scholar['sinta_score'] = &$data_sinta['sinta_score'];
            $data_scholar['scopus_h-index'] = &$data_sinta['scopus_h-index'];
            $data_scholar['scholar_h-index'] = &$data_sinta['scholar_h-index'];
            $data_scholar['publications'] = &$data_sinta['publication']['scholar'];
        }

        if (isset($data_scholar['publications']) && isset($data_sinta['publication']['scopus'])) {
            foreach ($data_sinta['publication']['scopus'] as $sinta) {
                foreach ($data_scholar['publications'] as $key => $scholar) {
                    if (isset($sinta) && strcasecmp($scholar['title'], $sinta['title']) == 0) {
                        $data_scholar['publications'][$key]['link_scopus'] = $sinta['link'];
                        $data_scholar['publications'][$key]['rank'] = $sinta['rank'];
                    }
                }
            }
        }

        if (isset($data_scholar['publications']) && isset($data_sinta['publication']['wos'])) {
            foreach ($data_sinta['publication']['wos'] as $sinta) {
                foreach ($data_scholar['publications'] as $key => $scholar) {
                    if (isset($sinta) && strcasecmp($scholar['title'], $sinta['title']) == 0) {
                        $data_scholar['publications'][$key]['link_wos'] = $sinta['link'];
                        $data_scholar['publications'][$key]['rank'] = $sinta['rank'];
                    }
                }
            }
        }

        if (isset($data_scholar['publications']) && isset($data_sinta['publication']['garuda'])) {
            foreach ($data_sinta['publication']['garuda'] as $sinta) {
                foreach ($data_scholar['publications'] as $key => $scholar) {
                    if (isset($sinta) && strcasecmp($scholar['title'], $sinta['title']) == 0) {
                        $data_scholar['publications'][$key]['link_garuda'] = $sinta['link'];
                        $data_scholar['publications'][$key]['rank'] = $sinta['rank'];
                    }
                }
            }
        }

        if (isset($data_scholar['publications']) && isset($data_scopus->{'search-results'}->{'entry'})) {
            foreach ($data_scopus->{'search-results'}->{'entry'} as $scopus) {
                foreach ($data_scholar['publications'] as $key => $scholar) {
                    if (isset($scopus->{'dc:title'}) && strcasecmp($scholar['title'], $scopus->{'dc:title'}) == 0) {
                        $data_scholar['publications'][$key]['doi'] = $scopus->{'prism:doi'};
                    }
                }
            }
        }

        return json_encode($data_scholar, JSON_UNESCAPED_SLASHES);
    }

    public function pdf_scraping()
    {
        $researchWords = ['penelitian', 'eksperimen'];
        $communityServiceWords = ['pengabdian', 'masyarakat', 'desa'];
        $kuliahWords = ['rpkps', 'praktikum', 'kuliah', 'akreditasi', 'ujian', 'semester', 'angkatan', 'lomba'];

        if ($this->request->getVar('key_penelitian') != null) {
            $key_penelitian = $this->request->getVar('key_penelitian');
            $keyword = explode(',', $key_penelitian);
            $researchWords = array_merge($researchWords, $keyword);
        }
        if ($this->request->getVar('key_pengabdian') != null) {
            $key_pengabdian = $this->request->getVar('key_pengabdian');
            $keyword = explode(',', $key_pengabdian);
            $communityServiceWords = array_merge($communityServiceWords, $keyword);
        }
        $this->upload_folder();
        $pdf_folder = glob('./upload/p2m_landing_surat/*.pdf');
        $jsonData = $this->main_pdf($pdf_folder, $researchWords, $communityServiceWords, $kuliahWords);
        // dump($jsonData);

        foreach ($jsonData as $data_pdf) {
            end($data_pdf['tanggal']);
            $tanggal_selesai = key($data_pdf['tanggal']);
            $data = [
                'nama_file' => $data_pdf['nama_file'] ?? null,
                'jenis_surat' => $data_pdf['jenis'] ?? null,
                'tanggal_mulai' => $data_pdf['tanggal'][0] ?? null,
                'tanggal_selesai' => $data_pdf['tanggal'][$tanggal_selesai] ?? null,
                'tempat' => $data_pdf['tempat'] ?? null,
            ];
            $all_data = $data;
            if (!empty($data_pdf['pelaku'])) {
                foreach ($data_pdf['pelaku'] as $pelaku) {
                    $pelaku_id = $this->p2m_get_author_id($pelaku);
                    $data_pelaku = [
                        'id_mahasiswa' => $pelaku_id['id_mahasiswa'],
                        'id_dosen' => $pelaku_id['lecturer_id'],
                        'id_tendik' => $pelaku_id['tendik_id'],
                    ];
                }
                $all_data = array_merge($data, $data_pelaku);
            }
            // dump($all_data);
            $nama_file = $this->surat->select('nama_file')->where('nama_file', $data_pdf['nama_file'])->first();
            if ($nama_file == null) {
                $this->surat->insert($all_data);
                if ($data_pdf['jenis'] == 'pengabdian masyarakat') {
                    $this->pengabdian->insert([
                        'nama_file' => $data_pdf['nama_file'],
                        'time_start' => $data_pdf['tanggal_mulai'],
                        'time_end' => $data_pdf['tanggal_selesai'],
                        'lokasi' => $data_pdf['tempat'],
                    ]);
                    $id_pengabdian = $this->pengabdian->select('pengabdianID')->where('nama_file', $data_pdf['nama_file'])->first()->pengabdianID;
                    $nama_dosen_pengabdian = $this->dosen->select('p2m_dosen.name')
                        ->join('p2m_pelaku_pengabdian', 'p2m_pelaku_pengabdian.id_dosen = p2m_dosen.dosenID', 'left')
                        ->where('p2m_pelaku_pengabdian.id_pengabdian', $id_pengabdian);
                    $nama_departemen_dosen_pengabdian = $this->departemen->select('p2m_departemen.name')->where("daftar_dosen LIKE '%" . $nama_dosen_pengabdian . "%' OR head LIKE '%" . $nama_dosen_pengabdian . "%' OR secretary LIKE '%" . $nama_dosen_pengabdian . "%'")->first();
                    $nama_tendik_pengabdian = $this->acad->select('p2m_tenaga_kependidikan.name')
                        ->join('p2m_pelaku_pengabdian', 'p2m_pelaku_pengabdian.id_tendik = p2m_tenaga_kependidikan.tendik_id', 'left')
                        ->where('p2m_pelaku_pengabdian.id_pengabdian', $id_pengabdian);
                    $nama_departemen_tendik_pengabdian = $this->departemen->select('p2m_departemen.name')->where("academic_staff LIKE '%" . $nama_tendik_pengabdian . "%' OR head LIKE '%" . $nama_tendik_pengabdian . "%' OR secretary LIKE '%" . $nama_tendik_pengabdian . "%'")->first();
                    $nama_prodi_dosen = $this->prodi->select('p2m_prodi.name')->where("members LIKE '%" . $nama_dosen_pengabdian . "%' OR head LIKE '%" . $nama_dosen_pengabdian . "%' OR secretary LIKE '%" . $nama_dosen_pengabdian . "%'")->first();
                    $this->pelaku_pengabdian->insert([
                        'id_pengabdian' => $id_pengabdian ?? null,
                        'id_mahasiswa' => $pelaku_id['id_mahasiswa'] ?? null,
                        'id_dosen' => $pelaku_id['lecturer_id'] ?? null,
                        'id_tendik' => $pelaku_id['tendik_id'] ?? null,
                        'departemen_dosen' => $nama_departemen_dosen_pengabdian->name ?? null,
                        'departemen_tendik' => $nama_departemen_tendik_pengabdian->name ?? null,
                        'prodi_dosen' => $nama_prodi_dosen->name ?? null
                    ]);
                } else if ($data_pdf['jenis'] == 'penelitian') {
                    $this->penelitian->insert([
                        'nama_file' => $data_pdf['nama_file'],
                        'time_start' => $data_pdf['tanggal_mulai'],
                        'time_end' => $data_pdf['tanggal_selesai'],
                        'location' => $data_pdf['tempat'],
                    ]);
                    $id_penelitian = $this->penelitian->select('penelitian_id')->where('nama_file', $data_pdf['nama_file'])->first()->penelitian_id;
                    $nama_dosen_penelitian = $this->dosen->select('p2m_dosen.name')
                        ->join('p2m_pelaku_penelitian', 'p2m_pelaku_penelitian.id_dosen = p2m_dosen.dosenID', 'left')
                        ->where('p2m_pelaku_penelitian.id_penelitian', $id_penelitian);
                    $nama_departemen_dosen_penelitian = $this->departemen->select('p2m_departemen.name')->where("daftar_dosen LIKE '%" . $nama_dosen_penelitian . "%' OR head LIKE '%" . $nama_dosen_penelitian . "%' OR secretary LIKE '%" . $nama_dosen_penelitian . "%'")->first();
                    $nama_tendik_penelitian = $this->acad->select('p2m_tenaga_kependidikan.name')
                        ->join('p2m_pelaku_penelitian', 'p2m_pelaku_penelitian.id_tendik = p2m_tenaga_kependidikan.tendik_id', 'left')
                        ->where('p2m_pelaku_penelitian.id_penelitian', $id_penelitian);
                    $nama_departemen_tendik_penelitian = $this->departemen->select('p2m_departemen.name')->where("academic_staff LIKE '%" . $nama_tendik_penelitian . "%' OR head LIKE '%" . $nama_tendik_penelitian . "%' OR secretary LIKE '%" . $nama_tendik_penelitian . "%'")->first();
                    $nama_prodi_dosen = $this->prodi->select('p2m_prodi.name')->where("members LIKE '%" . $nama_dosen_penelitian . "%' OR head LIKE '%" . $nama_dosen_penelitian . "%' OR secretary LIKE '%" . $nama_dosen_penelitian . "%'")->first();
                    $this->pelaku_penelitian->insert([
                        'id_penelitian' => $id_penelitian ?? null,
                        'id_mahasiswa' => $pelaku_id['id_mahasiswa'] ?? null,
                        'id_dosen' => $pelaku_id['lecturer_id'] ?? null,
                        'id_tendik' => $pelaku_id['tendik_id'] ?? null,
                        'departemen_dosen' => $nama_departemen_dosen_penelitian->name ?? null,
                        'departemen_tendik' => $nama_departemen_tendik_penelitian->name ?? null,
                        'prodi_dosen' => $nama_prodi_dosen->name ?? null
                    ]);
                }
            }
        }

        // Pindah file
        $this->pindah_file($pdf_folder, $researchWords, $communityServiceWords, $kuliahWords);
        session()->setFlashdata('success', 'Data berhasil diupload');
        return $this->response->redirect(site_url('p2m/p2m_pdf'));
    }

    public function upload_folder()
    {
        $uploadedFiles = $this->request->getFiles();

        if (!empty($uploadedFiles['folder'])) {
            foreach ($uploadedFiles['folder'] as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $destination = 'upload/p2m_landing_surat/' . $file->getName() . '.pdf';

                    if ($file->getClientMimeType() === 'application/pdf' && $file->getSize() <= 200 * 1024) {
                        if (!file_exists($destination)) {
                            $file->move('upload/p2m_landing_surat');
                        }
                    } else {
                        // session()->setFlashdata('error', 'File is not a pdf or file size is more than 200 KB');
                        continue;
                    }
                }
            }
        }
    }

    // Function to calculate Jaccard similarity between two sets
    function calculateJaccardSimilarity($set1, $set2)
    {
        $intersection = array_intersect($set1, $set2);
        $union = array_unique(array_merge($set1, $set2));
        $similarity = count($intersection) / count($union);
        return $similarity;
    }

    // Function to determine the category based on text content
    function determineCategory($textContent, $researchWords, $communityServiceWords, $kuliahWords)
    {
        // Tokenize the text content into words
        $words = str_word_count(strtolower($textContent), 1);

        // Calculate Jaccard similarity with research and community service word lists
        $kuliahSimilarity = $this->calculateJaccardSimilarity($words, $kuliahWords);

        if ($kuliahSimilarity > 0) {
            return 'bukan keduanya';
        } else {
            $researchSimilarity = $this->calculateJaccardSimilarity($words, $researchWords);
            // echo $researchSimilarity;
            $communityServiceSimilarity = $this->calculateJaccardSimilarity($words, $communityServiceWords);
            // echo $communityServiceSimilarity;

            // Determine the category with the highest similarity
            if ($researchSimilarity > $communityServiceSimilarity) {
                return 'penelitian';
            } elseif ($communityServiceSimilarity > $researchSimilarity) {
                return 'pengabdian masyarakat';
            } else {
                return 'bukan keduanya'; // Handle cases where similarity is equal or close
            }
        }
    }

    // Tanggal Kegiatan
    function get_tanggal($text)
    {
        // Tanggal Kegiatan
        $pattern = '/(\d{1,2} [A-Za-z]+ \d{4})/u';
        // Month translation array (English to Indonesian)
        if (strpos($text, "Januari")) {
            $remove = 'Januari';
            $text = str_replace($remove, 'January', $text);
        }
        if (strpos($text, "Februari")) {
            $remove = 'Februari';
            $text = str_replace($remove, 'February', $text);
        }
        if (strpos($text, "Maret")) {
            $remove = 'Maret';
            $text = str_replace($remove, 'March', $text);
        }
        if (strpos($text, "April")) {
            $remove = 'April';
            $text = str_replace($remove, 'April', $text);
        }
        if (strpos($text, "Mei")) {
            $remove = 'Mei';
            $text = str_replace($remove, 'May', $text);
        }
        if (strpos($text, "Juni")) {
            $remove = 'Juni';
            $text = str_replace($remove, 'June', $text);
        }
        if (strpos($text, "Juli")) {
            $remove = 'Juli';
            $text = str_replace($remove, 'July', $text);
        }
        if (strpos($text, "Agustus")) {
            $remove = 'Agustus';
            $text = str_replace($remove, 'August', $text);
        }
        if (strpos($text, "September")) {
            $remove = 'September';
            $text = str_replace($remove, 'September', $text);
        }
        if (strpos($text, "Oktober")) {
            $remove = 'Oktober';
            $text = str_replace($remove, 'October', $text);
        }
        if (strpos($text, "November")) {
            $remove = 'November';
            $text = str_replace($remove, 'November', $text);
        }
        if (strpos($text, "Desember")) {
            $remove = 'Desember';
            $text = str_replace($remove, 'December', $text);
        };
        if (strpos($text, "s.d.")) {
            $remove = 's.d.';
            $text = str_replace($remove, 'sampai', $text);
        };
        $startPosition = strpos(strtolower($text), "dengan ini");
        if (strpos($text, 'revisi surat tugas')) {
            $demikian = strpos($text, 'Surat tugas ini');
        } else if (strpos($text, 'Kunjungan dimaksudkan')) {
            $demikian = strpos($text, 'Kunjungan dimaksudkan');
        } else if (strpos($text, 'Kegiatan ini')) {
            $demikian = strpos($text, 'Kegiatan ini');
        } else {
            $demikian = strpos($text, 'Demikian surat tugas');
        }
        $paragraph = substr($text, $startPosition, $demikian - $startPosition);
        preg_match_all($pattern, $paragraph, $matches);
        foreach ($matches[0] as &$date) {
            $timestamp = strtotime($date);
            $formatted = date('Y-m-d', $timestamp);
            $date = $formatted;
        }
        return $matches[0];
    }

    // Tempat Kegiatan
    function get_tempat($text)
    {
        if (strpos($text, "s.d.")) {
            $remove = 's.d.';
            $text = str_replace($remove, 'sampai', $text);
        }

        $startPosition = strpos($text, "dengan ini");
        // $endPosition = strpos($text, ".", $startPosition) + 1;
        if (strpos($text, 'revisi surat tugas')) {
            $demikian = strpos($text, 'Surat tugas ini');
        } else if (strpos($text, 'Kunjungan dimaksudkan')) {
            $demikian = strpos($text, 'Kunjungan dimaksudkan');
        } else {
            $demikian = strpos($text, 'Demikian surat tugas');
        }
        $paragraph = substr($text, $startPosition, $demikian - $startPosition);
        // echo $demikian;
        // echo $paragraph . "\n";
        $tempat = "";
        if (strpos($paragraph, "daring") || strpos($paragraph, "Daring")) {
            $tempat = "Daring";
        } else if (strpos($paragraph, " di ")) {
            $startWord = " di ";
            $endWord = "pada";

            $startPosition = strrpos($paragraph, $startWord) + 3;
            $endPosition = strrpos($paragraph, $endWord);

            $tempat = substr($paragraph, $startPosition, $endPosition - $startPosition);
        } else if (strpos($paragraph, " di\n")) {
            $startWord = " di\n";
            $endWord = "pada";

            $startPosition = strrpos($paragraph, $startWord) + 3;
            $endPosition = strrpos($paragraph, $endWord);

            $tempat = substr($paragraph, $startPosition, $endPosition - $startPosition);
        } else if (strpos($paragraph, "\ndi ")) {
            $startWord = "\ndi ";
            $endWord = "pada";

            $startPosition = strrpos($paragraph, $startWord) + 3;
            $endPosition = strrpos($paragraph, $endWord);

            $tempat = substr($paragraph, $startPosition, $endPosition - $startPosition);
        }
        $tempat = str_replace("\n", " ", $tempat);
        // echo trim($tempat);
        return trim($tempat);
    }

    // Pelaku Kegiatan
    function get_pelaku($text)
    {
        // Pelaku Kegiatan
        if (strpos($text, "No Laboratiorium")) {
            $start_pelaku = strpos($text, "No Laboratorium");
        } else {
            $start_pelaku = strpos($text, "No Nama");
        }
        $end_pelaku = strpos($text, "a.n.", $start_pelaku);
        $paragraph_pelaku = substr($text, $start_pelaku, $end_pelaku - $start_pelaku);
        // echo $paragraph_pelaku;
        $lines = explode("\n", $paragraph_pelaku);
        $pelaku = [];

        if (strpos($lines[0], "NIP") || strpos($lines[0], "NIKA")) {
            // Start from the second line to skip the header
            foreach ($lines as $line) {
                $line = trim($line);

                if (strlen($line) > 15) {
                    if (is_numeric(trim(substr($line, 0, 1))) || is_numeric(trim(substr($line, 0, 2))) || is_numeric(trim(substr($line, 0, 3)))) {
                        // Extract the name using substr based on the position of the columns
                        $name = trim(substr($line, 2));
                        $numericPos = strcspn($name, "1234567890");
                        $nama_pelaku = trim(substr($name, 0, $numericPos));
                        $nama_pelaku = str_replace("\n", " ", $nama_pelaku);
                        $pelaku[] = $nama_pelaku;
                    } else {
                        $numericPos = strcspn($line, "1234567890");
                        $nama_pelaku = trim(substr($line, 0, $numericPos));
                    }
                }
            }
        } else if (strpos($lines[0], "Reviewer")) {
            foreach ($lines as $line) {
                $line = trim($line);
                if (strlen($line) > 15) {
                    if (is_numeric(trim(substr($line, 0, 1))) || is_numeric(trim(substr($line, 0, 2))) || is_numeric(trim(substr($line, 0, 3)))) {
                        // Extract the name using substr based on the position of the columns
                        $name = trim(substr($line, 2));
                        $numericPos = strcspn($name, "1234567890");
                        $nama_pelaku = trim(substr($name, 0, $numericPos));
                        $nama_pelaku = str_replace("\n", " ", $nama_pelaku);
                        $pelaku[] = $nama_pelaku;
                    }
                }
            }
        } else {
            foreach ($lines as $line) {
                $line = trim($line);

                if (strlen($line) > 15 && strpos($line, "Narasumber")) {
                    $line = str_replace(" Narasumber", ",", $line);
                    if (is_numeric(trim(substr($line, 0, 1))) || is_numeric(trim(substr($line, 0, 2)))) {
                        $pattern = '/\d+\s(.+?),/';
                        preg_match($pattern, $line, $matches);
                        if (isset($matches[1])) {
                            $pelaku[] = $matches[1];
                        }
                    }
                } else if (strlen($line) > 15 && strpos($line, "Ketua")) {
                    $line = str_replace(" Ketua", ",", $line);
                    if (is_numeric(trim(substr($line, 0, 1))) || is_numeric(trim(substr($line, 0, 2)))) {
                        $pattern = '/\d+\s(.+?),/';
                        preg_match($pattern, $line, $matches);
                        if (isset($matches[1])) {
                            $pelaku[] = $matches[1];
                        }
                    }
                } else if (strlen($line) > 15 && strpos($line, "Anggota")) {
                    $line = str_replace(" Anggota", ",", $line);
                    if (is_numeric(trim(substr($line, 0, 1))) || is_numeric(trim(substr($line, 0, 2)))) {
                        $pattern = '/\d+\s(.+?),/';
                        preg_match($pattern, $line, $matches);
                        if (isset($matches[1])) {
                            $pelaku[] = $matches[1];
                        }
                    }
                } else if (strlen($line) > 15 && strpos($line, "Peneliti")) {
                    $line = str_replace(" Peneliti", ",", $line);
                    if (is_numeric(trim(substr($line, 0, 1))) || is_numeric(trim(substr($line, 0, 2)))) {
                        $pattern = '/\d+\s(.+?),/';
                        preg_match($pattern, $line, $matches);
                        if (isset($matches[1])) {
                            $pelaku[] = $matches[1];
                        }
                    }
                } else if (strlen($line) > 15 && strpos($line, "-")) {
                    $line = str_replace(" -", ",", $line);
                    if (is_numeric(trim(substr($line, 0, 1))) || is_numeric(trim(substr($line, 0, 2)))) {
                        $pattern = '/\d+\s(.+?),/';
                        preg_match($pattern, $line, $matches);
                        if (isset($matches[1])) {
                            $pelaku[] = $matches[1];
                        }
                    }
                }
            }
        }

        $filtered_pelaku = array_filter($pelaku, function ($name) {
            return strpos($name, ". ") !== 0;
        });

        return $filtered_pelaku;
    }

    public function main_pdf(array $pdf_files, array $research_words, array $community_service_words, array $kuliah_words)
    {
        $parser = new Parser();
        $final_array = [];
        // dump($pdf_files);

        foreach ($pdf_files as $pdf_file) {
            // SURAT TUGAS
            $pdf = $parser->parseFile($pdf_file);

            $text = $pdf->getText();

            if (strpos(strtolower($text), 'surat tugas') !== false && strpos(strtolower($text), 'wakil dekan') !== false) {
                $nama_file =  substr($pdf_file, strrpos($pdf_file, '/') + 1);
                $jenis = $this->determineCategory($text, $research_words, $community_service_words, $kuliah_words);
                if ($jenis == 'penelitian' || $jenis == 'pengabdian' || $jenis == 'bukan keduanya') {
                    $array_hasil = [
                        'nama_file' => $nama_file,
                        'jenis' => $jenis,
                        'tanggal' => $this->get_tanggal($text) ?? null,
                        'tempat' => $this->get_tempat($text) ?? null,
                        'pelaku' => $this->get_pelaku($text) ?? null
                    ];
                    $final_array[] = $array_hasil;
                }
            }
        }
        // dump($final_array);
        foreach ($final_array as &$data) {
            foreach ($data['pelaku'] as &$pelaku) {
                if (strpos($pelaku, "Prof. ") === 0) {
                    $remove = 'Prof. ';
                    $pelaku = trim(str_replace($remove, '', $pelaku));
                    preg_match('/([^,]+)/', $pelaku, $matches);
                    $pelaku = trim($matches[1]);
                }
                if (strpos($pelaku, "Dr.") === 0) {
                    $remove = 'Dr.';
                    $pelaku = trim(str_replace($remove, '', $pelaku));
                    preg_match('/([^,]+)/', $pelaku, $matches);
                    $pelaku = trim($matches[1]);
                }
                if (strpos($pelaku, "Dr..") === 0) {
                    $remove = 'Dr..';
                    $pelaku = trim(str_replace($remove, '', $pelaku));
                    preg_match('/([^,]+)/', $pelaku, $matches);
                    $pelaku = trim($matches[1]);
                }
                if (strpos($pelaku, "rer.") === 0) {
                    $remove = 'rer.';
                    $pelaku = trim(str_replace($remove, '', $pelaku));
                    preg_match('/([^,]+)/', $pelaku, $matches);
                    $pelaku = trim($matches[1]);
                }
                if (strpos($pelaku, "nat.") === 0) {
                    $remove = 'nat.';
                    $pelaku = trim(str_replace($remove, '', $pelaku));
                    preg_match('/([^,]+)/', $pelaku, $matches);
                    $pelaku = trim($matches[1]);
                }
                if (strpos($pelaku, "Drs. ") === 0) {
                    $remove = 'Drs. ';
                    $pelaku = trim(str_replace($remove, '', $pelaku));
                    preg_match('/([^,]+)/', $pelaku, $matches);
                    $pelaku = trim($matches[1]);
                }
                if (strpos($pelaku, "Eng. ") === 0) {
                    $remove = 'Eng. ';
                    $pelaku = trim(str_replace($remove, '', $pelaku));
                    preg_match('/([^,]+)/', $pelaku, $matches);
                    $pelaku = trim($matches[1]);
                }
                if (strpos($pelaku, "Dr-tech.") === 0) {
                    $remove = 'Dr-tech.';
                    $pelaku = trim(str_replace($remove, '', $pelaku));
                    preg_match('/([^,]+)/', $pelaku, $matches);
                    $pelaku = trim($matches[1]);
                }
                if (strpos($pelaku, "Dr-tech.") === 0) {
                    $remove = 'Dr-tech.';
                    $pelaku = trim(str_replace($remove, '', $pelaku));
                    preg_match('/([^,]+)/', $pelaku, $matches);
                    $pelaku = trim($matches[1]);
                }
                if (strpos($pelaku, "-Ing.") === 0) {
                    $remove = '-Ing.';
                    $pelaku = trim(str_replace($remove, '', $pelaku));
                    preg_match('/([^,]+)/', $pelaku, $matches);
                    $pelaku = trim($matches[1]);
                }
                if (strpos($pelaku, "Dra.") === 0) {
                    $remove = 'Dra.';
                    $pelaku = trim(str_replace($remove, '', $pelaku));
                    preg_match('/([^,]+)/', $pelaku, $matches);
                    $pelaku = trim($matches[1]);
                }
            }
        }
        return $final_array;
    }

    public function pindah_file(array $pdf_files, array $research_words, array $community_service_words, array $kuliah_words)
    {
        $parser = new Parser();
        foreach ($pdf_files as $pdf_file) {
            $pdf = $parser->parseFile($pdf_file);

            $text = $pdf->getText();
            $nama_file =  substr($pdf_file, strrpos($pdf_file, '/') + 1);
            $jenis = $this->determineCategory($text, $research_words, $community_service_words, $kuliah_words);
            if (strpos(strtolower($text), 'surat tugas') !== false && strpos(strtolower($text), 'wakil dekan') !== false) {
                if ($jenis == 'penelitian') {
                    $new_location = './upload/p2m_surat_penelitian/' . $nama_file;
                    if (!file_exists($new_location)) {
                        rename($pdf_file, $new_location);
                    } else {
                        unlink($pdf_file);
                    }
                } else if ($jenis == 'pengabdian masyarakat') {
                    $new_location = './upload/p2m_surat_pengabdian/' . $nama_file;
                    if (!file_exists($new_location)) {
                        rename($pdf_file, $new_location);
                    } else {
                        unlink($pdf_file);
                    }
                } else if ($jenis == 'bukan keduanya') {
                    $new_location = './upload/p2m_surat_bukan_keduanya/' . $nama_file;
                    if (!file_exists($new_location)) {
                        rename($pdf_file, $new_location);
                    } else {
                        unlink($pdf_file);
                    }
                }
            } else {
                unlink($pdf_file);
            }
        }
    }
}
