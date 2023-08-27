<?php


namespace App\Controllers;

use App\Models\DepartemenModel;
use App\Models\GDocsTemplateModel;
use App\Models\SuratGDocsModel;
use App\Models\SuratTemplateModel;
use Google\Client;
use Google\Service\Drive;

class SuratGDocs extends BaseController
{
    private $bs;
    private $driveService;
    private $pjs;

    function __construct()
    {
        putenv('GOOGLE_APPLICATION_CREDENTIALS=/Users/abdan/Downloads/poetic-nature-342712-8d5ce08a3136.json');

        $this->bs = new SuratGDocsModel();
        $this->pjs = [];

        $client = new Client();
        $client->useApplicationDefaultCredentials();
        $client->addScope(Drive::DRIVE);
        $this->driveService = new Drive($client);

    }

    public function index($count = false)
    {
        $db = \Config\Database::connect();
        $db->query("UPDATE notifications SET status = 1 WHERE notification_type = 'surat_gdocs' AND user_id = '" . session('id') . "'");

        $data = $this->request->getGet();
        $q = $data['q'] ?? '';
        $status = $data['status'] ?? '';
        $sort_column = $data['sort_column'] ?? 'created_at';
        $sort_order = $data['sort_order'] ?? 'desc';

        $jenis_user = session('jenis_user');
        $id = session('id');
        $pegawai_id = session('pegawai_id');
        $gol_verifikator = session('gol_verifikator');
        $this->bs->select('surat_gdocs.*, users.nama, date(NOW()) <= tanggal_kegiatan_mulai OR file_pertanggungjawaban IS NOT NULL as dalam_periode')
            ->join('users', 'surat_gdocs.user_id = users.id')
            ->join('pegawais', 'surat_gdocs.penandatangan_pegawai_id = pegawais.user_id', 'left')
            ->join('departemen', 'surat_gdocs.departemen_pegawai_id = departemen.kepala_pegawai_id', 'left')
            ->where("IF(surat_gdocs.user_id = $id OR (JSON_CONTAINS(shares, CONCAT('[', $id ,']')) AND status = 3) OR ('$jenis_user' IN('admin')) OR ('$jenis_user' IN('verifikator') AND '$gol_verifikator' = departemen.id) OR ('$jenis_user' IN('dekan', 'wadek') AND status >= 2 AND penandatangan_pegawai_id = $pegawai_id) OR ('$jenis_user' IN('departemen') AND status = 3 AND departemen_pegawai_id = $pegawai_id), true, false )")
            //->where("CONCAT(nama_surat, nama, tabel) LIKE '%$q%'")
            ->where("status LIKE '%$status%'")
            ->orderBy($sort_column, $sort_order);



        // $db = \Config\Database::connect();
        // $db->query("UPDATE notifications SET status = 1 WHERE notification_type = 'surat' AND user_id = '" . session('id') . "'");

        // $data = $this->request->getGet();
        // $q = $data['q'] ?? '';
        // $status = $data['status'] ?? '';
        // $sort_column = $data['sort_column'] ?? 'created_at';
        // $sort_order = $data['sort_order'] ?? 'desc';

        // $jenis_user = session('jenis_user');
        // $id = session('id');
        // $pegawai_id = session('pegawai_id');
        // $gol_verifikator = session('gol_verifikator');
        // $this->bs->select('surat_gdocs.*, users.nama, date(NOW()) <= tanggal_kegiatan_mulai OR file_pertanggungjawaban IS NOT NULL as dalam_periode')
        //     ->join('users', 'surat_gdocs.user_id = users.id')
        //     ->join('pegawais', 'surat_gdocs.penandatangan_pegawai_id = pegawais.user_id', 'left')
        //     ->orderBy($sort_column, $sort_order);

        if ($count) {
            echo $this->bs->countAllResults();
            exit;
        }

        $rows = $this->bs->paginate(10);

        $data = [
            'rows' => $rows,
            'pager' => $this->bs->pager,
            'jenis_user' => $jenis_user,
            'user_id' => $id,
            'pegawai_id' => $pegawai_id,
            'q' => $q,
            'status' => $status,
            'sort_column' => $sort_column,
            'sort_order' => $sort_order,
            'templates' => (new GDocsTemplateModel())->get()->getResult(),
        ];
        return view('surat-gdocs/index', $data);

    }

    public function create($templateId)
    {
        $id = (new \Hidehalo\Nanoid\Client())->formattedId(getenv('NANOID_ALPHABET'), 16);

        $file = $this->copyTemplate($templateId, $id);

        $data = [
            'id' => $id,
            'user_id' => session('id'),
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'gdocs_id' => $file->id,
        ];

        $this->bs->insert($data);

        return redirect()->to('/suratgdocs/edit/' . $id);

    }

    public function update($id)
    {
        $data = $this->request->getPost();
        $data['status'] = $data['status'] == 'preview' ? 1 : $data['status'];
        if (!empty($data['tembusan']))
            $data['tembusan'] = json_encode($data['tembusan']);
        if (!empty($data['tanggal_kegiatan'])) {
            $data['tanggal_kegiatan'] = $data['tanggal_kegiatan'] ?? [];
            sort($data['tanggal_kegiatan']);
            $data['tanggal_kegiatan'] = json_encode($data['tanggal_kegiatan']);
        }
        $this->bs->update($id, $data);
        // dump($data);
        $this->create_no_surat($id);

        $row = $this->bs
            ->join('users', 'surat_gdocs.user_id = users.id')
            ->where('surat_gdocs.id', $id)->first();
        $db = \Config\Database::connect();
        if (isset($data['status'])) {
            switch ($data['status']) {
                case -1:
                    $this->delete($id);
                    break;
                case 2:
                    $db->query("INSERT INTO notifications
                        select null, users.id, 'surat', 'surat', '0', now(), now() from users 
                        join pegawais on pegawais.user_id = users.id 
                        where pegawais.id = " . ($row->penandatangan_pegawai_id ?? "''"));
                    break;
                case 3:
                    $arr = explode('/', $row->no_surat);
                    $arr_klasifikasi = explode('.', $arr[4]);
                    $kode_perihal = $arr_klasifikasi[0];
                    array_shift($arr_klasifikasi);
                    $kode_klasifikasi = implode('.', $arr_klasifikasi);
                    $id_detail = date('ymdHis'); //substr(md5(date('YmdHis')), 0, 6);
                    (new DetailNomorSuratModel())->insert([
                        'id' => $id_detail,
                        'no_surat' => $row->no_surat,
                        'perihal' => $row->nama_surat,
                        'penandatangan' => $arr[2],
                        'pengolah' => $arr[3],
                        'kode_perihal' => $kode_perihal,
                        'klasifikasi_surat' => $kode_klasifikasi,
                        'tanggal_surat' => $row->tanggal_pengajuan,
                        'tujuan_surat' => "Buat Surat",
                        'sifat_surat' => "1",
                        'user_id' => $row->user_id
                    ]);
                    $db->query("INSERT INTO notifications
                        select null, users.id, 'surat', 'surat', '0', now(), now() from users 
                        where users.id = " . ($row->user_id ?? "''"));
                    $email = \Config\Services::email();
                    $email->setFrom('noreply-mipa@ugm.ac.id', 'FMIPAUGM');
                    $email->setTo($row->username);
                    $email->setSubject('Verifikasi Surat Baru');
                    $email->setMessage('Surat dengan nomor: <a href="' . base_url("suratgdocs/topdf/$id") . '">' . $row->no_surat . '</a> sudah terverifikasi.');
                    if (!$email->send())
                        echo $email->printDebugger(['headers']);
                    // IMPLEMENT PDF CONVERSION HERE!
                    // $this->topdf($id, true);
                    break;
                default:
                    break;
            }
        }
        $this->upload_dasar_penerbitan($id);
        session()->setFlashdata('success', 'Data berhasil disimpan');
        if ($this->request->getPost('status') == 'preview') {
            return $this->response->redirect(site_url('suratgdocs/preview/' . $id));
        } else {
            return $this->response->redirect(site_url('suratgdocs'));
        }
    }

    public function edit($id)
    {
        $row = $this->bs->find($id);
        $shares = [];
        $data = [
            'action' => 'update',
            'row' => $row,
            'details' => [],
            'pjs' => $this->pjs,
            'shares' => $shares,
            'departemens' => (new DepartemenModel)->join('pegawais', 'pegawais.id = departemen.kepala_pegawai_id')->where('departemen.id', '6')->get()->getResult(),
        ];
        return view('surat-gdocs/form', $data);
    }

    public function preview($id)
    {
        $row = $this->bs->find($id);
        $db = \Config\Database::connect();
        $shares = implode(", ", json_decode($row->shares ?? '[]'));
        if (!empty($shares))
            $shares = $db->query("SELECT * FROM users WHERE id IN (" . $shares . ")")->getResult();

        $chats = $db->query("SELECT * from `buat_surat_thread_chat` JOIN users ON user_id = users.id WHERE `buat_surat_id` = '$id' ORDER BY tanggal_kirim ASC")->getResult();
        $data = [
            'action' => 'update',
            'row' => $row,
            'shares' => !empty($shares) ? $shares : [],
            'chats' => $chats,
            // 'nama_penandatangan' => $nama_penandatangan,
            // 'tembusans' => json_decode($row->tembusan),
            'pjs' => $this->pjs,
            'departemens' => (new DepartemenModel)->join('pegawais', 'pegawais.id = departemen.kepala_pegawai_id')->get()->getResult(),
        ];

        return view('surat-gdocs/preview', $data);
    }
    
    function create_no_surat($id)
    {
        $row = $this->bs->where('id', $id)->first();
        if ($row->status == 3) {
            $arr = explode('/', $row->no_surat);
            $kode_klasifikasi = $arr[count($arr) - 2];
            $r = (new NomorSuratModel())->where('kode_klasifikasi', $kode_klasifikasi)->first();
            $increment = $r->nomor;
            $no_surat = $increment . $row->no_surat;
            $this->bs->update($id, ['no_surat' => $no_surat]);
            (new NomorSuratModel())->update($r->id, ['nomor' => ++$increment]);
        }
    }

    function upload_dasar_penerbitan($id)
    {
        if (!empty($this->request->getFile('berkas')) && !empty($this->request->getFile('berkas')->getFileName())) {
            if (file_exists("upload/dasar_penerbitan_buat_surat/$id.pdf"))
                unlink("upload/dasar_penerbitan_buat_surat/$id.pdf");
            $file = $this->request->getFile('berkas');
            $file->move('upload/dasar_penerbitan_buat_surat', $id . '.pdf');
        }
    }


    function copyTemplate($templateId, $id)
    {
        $gdocs_id = (new GDocsTemplateModel())->find($templateId)->gdocs_id;
        $fileMetadata = new Drive\DriveFile(
            array(
                'name' => $id,
                'parents' => array(getenv('DOCS_PARENT_FOLDER')),
                'mime_type' => 'application/vnd.google-apps.document',
            )
        );
        $file = $this->driveService->files->copy($gdocs_id, $fileMetadata);

        $writePermission = new Drive\Permission(
            array(
                'type' => 'anyone',
                'role' => 'writer',    
            )
        );

        $this->driveService->permissions->create(
            $file->id,
            $writePermission,
            array('fields' => 'id')
        );

        $publishRevision = new Drive\Revision(
            array(
                'published' => true,
                'publishedOutsideDomain' => true,
                'publishAuto' => true,
            )
        );

        $this->driveService->revisions->update(
            $file->id,
            1,
            $publishRevision,
        );
            

        return $file;

    }

    public function revisions($id) {
        $revisions = $this->driveService->revisions->listRevisions($id);
        $revisions = $revisions->getRevisions();
        
        return json_encode($revisions);
    }

}