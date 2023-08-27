<?php

namespace App\Controllers;

use App\Models\BuatSuratModel;
use App\Models\SuratTemplateModel;
use App\Models\DetailNomorSuratModel;
use App\Models\NomorSuratModel;
use App\Models\DepartemenModel;
use Dompdf\Dompdf;
use chillerlan\QRCode\{QRCode, QROptions};

class Buatsurat extends BaseController
{
    private $pjs;
    private $st;

    function __construct()
    {
        $this->bs = new BuatSuratModel();

        $pjs = [];
    }

    //Ketika tampilan awal menu buat surat
    public function index($count = false)
    {
        // Dismiss Notifikasi
        $db = \Config\Database::connect();
        $db->query("UPDATE notifications SET status = 1 WHERE notification_type = 'buat_surat' AND user_id = '" . session('id') . "'");

        $data = $this->request->getGet();
        $q = $db->escapeLikeString(htmlspecialchars($data['q'] ?? ''));
        $status = $data['status'] ?? '';
        $sort_column = $data['sort_column'] ?? 'created_at';
        $sort_order = $data['sort_order'] ?? 'desc';

        $jenis_user = session('jenis_user');
        $id = session('id');
        $pegawai_id = session('pegawai_id');
        $gol_verifikator = session('gol_verifikator');
        $this->bs->select('buat_surat.*, users.nama, date(NOW()) <= tanggal_kegiatan_mulai OR file_pertanggungjawaban IS NOT NULL as dalam_periode')
            ->join('users', 'buat_surat.user_id = users.id')
            ->join('pegawais', 'buat_surat.penandatangan_pegawai_id = pegawais.user_id', 'left')
            ->join('departemen', 'buat_surat.departemen_pegawai_id = departemen.kepala_pegawai_id', 'left')
            ->where("IF(buat_surat.user_id = $id OR (JSON_CONTAINS(shares, CONCAT('[', $id ,']')) AND status = 3) OR ('$jenis_user' IN('admin')) OR ('$jenis_user' IN('verifikator') AND '$gol_verifikator' = departemen.id) OR ('$jenis_user' IN('dekan', 'wadek') AND status >= 2 AND penandatangan_pegawai_id = $pegawai_id) OR ('$jenis_user' IN('departemen') AND status = 3 AND departemen_pegawai_id = $pegawai_id), true, false )")
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
        // $this->bs->select('buat_surat.*, users.nama, date(NOW()) <= tanggal_kegiatan_mulai OR file_pertanggungjawaban IS NOT NULL as dalam_periode')
        //     ->join('users', 'buat_surat.user_id = users.id')
        //     ->join('pegawais', 'buat_surat.penandatangan_pegawai_id = pegawais.user_id', 'left')
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
            'templates' => (new SuratTemplateModel())->get()->getResult(),
        ];
        return view('surat/index', $data);
    }

    //Proses input data surat baru
    public function create($template_id)
    {
        $template = (new SuratTemplateModel())->where('id', $template_id)->first();
        $row = new BuatSuratModel();
        $row->no_surat = $template->no_surat;
        $row->nama_surat = $template->nama_surat;
        $row->lokasi_kegiatan = $template->lokasi_kegiatan;
        $row->isi_surat = $template->isi_surat;
        $row->isi_lampiran = $template->isi_lampiran;

        $row->tanggal_pengajuan = date('Y-m-d');
        $row->tembusan = json_decode($template->tembusan);
        $row->tanggal_kegiatan = [];
        $shares = [];
        $data = [
            'action' => 'store',
            'row' => $row,
            'details' => [],
            'nama_penandatangan' => null,
            'pjs' => $this->pjs,
            'shares' => $shares,
            'departemens' => (new DepartemenModel)->join('pegawais', 'pegawais.id = departemen.kepala_pegawai_id')->where('departemen.id', '6')->get()->getResult(),
        ];
        return view('surat/form', $data);
    }

    //Proses copy template surat baru
    public function copy($template_id)
    {
        $template = (new BuatSuratModel())->where('id', $template_id)->first();
        $row = new BuatSuratModel();
        $row->no_surat = $template->no_surat;
        $row->nama_surat = $template->nama_surat;
        $row->lokasi_kegiatan = $template->lokasi_kegiatan;
        $row->isi_surat = $template->isi_surat;
        $row->isi_lampiran = $template->isi_lampiran;

        $row->tanggal_pengajuan = date('Y-m-d');
        $row->tembusan = json_decode($template->tembusan);
        $row->tanggal_kegiatan = [];
        $shares = [];
        $data = [
            'action' => 'store',
            'row' => $row,
            'details' => [],
            'nama_penandatangan' => null,
            'pjs' => $this->pjs,
            'shares' => $shares,
            'departemens' => (new DepartemenModel)->join('pegawais', 'pegawais.id = departemen.kepala_pegawai_id')->where('departemen.id', '6')->get()->getResult(),
        ];
        return view('surat/form', $data);
    }

    //Proses menyimpan hasil inputan data baru ke database
    public function store()
    {
        // $id = substr(md5(date('YmdHis')), 0, 6);
        $id = (new \Hidehalo\Nanoid\Client())->formattedId(getenv('NANOID_ALPHABET'), 16);
        $data = $this->request->getPost();
        $data['status'] = $data['status'] == 'preview' ? 1 : $data['status'];
        $data['id'] = $id;
        //if (isset($data['no_surat'])) $data['no_surat'] = $this->create_no_surat(base64_encode($data['no_surat']));
        $data['tembusan'] = json_encode($data['tembusan'] ?? []);
        $data['tanggal_kegiatan'] = $data['tanggal_kegiatan'] ?? [];
        sort($data['tanggal_kegiatan']);
        $data['tanggal_kegiatan'] = json_encode($data['tanggal_kegiatan'] ?? []);
        $data['user_id'] = session()->get('id');
        $data['shares'] = '[3, 12, 303, 380, 388, 402, 403]';
        $this->bs->insert($data);
        $this->upload_dasar_penerbitan($id);

        $db = \Config\Database::connect();
        $db->query("INSERT INTO notifications
            select null, id, 'surat', 'surat', '0', now(), now() from users 
            where jenis_user IN('verifikator') OR id = " . session('id') . "
        ");

        session()->setFlashdata('success', 'Data berhasil disimpan');
        // dump($this->request->getPost('status'));
        if ($this->request->getPost('status') == 'preview') {
            return $this->response->redirect(site_url('buatsurat/preview/' . $id));
        } else {
            return $this->response->redirect(site_url('buatsurat'));
        }
    }

    //Proses edit data surat baru
    public function edit($id)
    {
        $row = (new BuatSuratModel())->where('id', $id)->first();
        $row->tembusan = json_decode($row->tembusan);
        $row->tanggal_kegiatan = json_decode($row->tanggal_kegiatan);

        $db = \Config\Database::connect();
        $shares = implode(", ", json_decode($row->shares ?? '[]'));
        if (!empty($shares))
            $shares = $db->query("SELECT * FROM users WHERE id IN (" . $shares . ")")->getResult();
        $db = \Config\Database::connect();
        // $nama_penandatangan = $db->query("SELECT nama_publikasi nama FROM pegawais WHERE id=$row->penandatangan_pegawai_id")->getResult()[0]->nama;
        $data = [
            'action' => 'update',
            'row' => $row,
            'shares' => !empty($shares) ? $shares : [],
            // 'nama_penandatangan' => $nama_penandatangan,
            // 'tembusans' => json_decode($row->tembusan),
            'pjs' => $this->pjs,
            'departemens' => (new DepartemenModel)->join('pegawais', 'pegawais.id = departemen.kepala_pegawai_id')->where('departemen.id', '6')->get()->getResult(),
        ];
        return view('surat/form', $data);
    }

    //Proses edit data surat baru
    public function preview($id)
    {
        $row = (new BuatSuratModel())->where('id', $id)->first();
        $row->tembusan = json_decode($row->tembusan);
        $row->tanggal_kegiatan = json_decode($row->tanggal_kegiatan);

        $db = \Config\Database::connect();
        $shares = implode(", ", json_decode($row->shares ?? '[]'));
        if (!empty($shares))
            $shares = $db->query("SELECT * FROM users WHERE id IN (" . $shares . ")")->getResult();
        $db = \Config\Database::connect();
        // $nama_penandatangan = $db->query("SELECT nama_publikasi nama FROM pegawais WHERE id=$row->penandatangan_pegawai_id")->getResult()[0]->nama;
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
        // dump($shares);
        return view('surat/preview', $data);
    }

    public function send()
    {
        $data = $this->request->getPost();
        $db = \Config\Database::connect();
        $db->query("INSERT INTO `buat_surat_thread_chat` (buat_surat_id, user_id, isi_chat, tanggal_kirim) VALUES('" . $data['buat_surat_id'] . "', '" . session('id') . "', '" . $data['isi_chat'] . "', NOW())");
        return $this->response->redirect(site_url('buatsurat/preview/' . $data['buat_surat_id']));
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
            ->join('users', 'buat_surat.user_id = users.id')
            ->where('buat_surat.id', $id)->first();
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
                    $email->setMessage('Surat dengan nomor: <a href="' . base_url("buatsurat/topdf/$id") . '">' . $row->no_surat . '</a> sudah terverifikasi.');
                    if (!$email->send())
                        echo $email->printDebugger(['headers']);
                    $this->topdf($id, true);
                    break;
                default:
                    break;
            }
        }
        $this->upload_dasar_penerbitan($id);
        session()->setFlashdata('success', 'Data berhasil disimpan');
        if ($this->request->getPost('status') == 'preview') {
            return $this->response->redirect(site_url('buatsurat/preview/' . $id));
        } else {
            return $this->response->redirect(site_url('buatsurat'));
        }
    }

    //Proses update komentar
    public function updatekomentar($id)
    {
        $model = new BuatSuratModel();
        $data = [
            'komentar' => $this->request->getVar('komentar'),
        ];
        $model->update($id, $data);
        return $this->response->redirect(site_url('buatsurat'));
    }

    //Proses delete data surat
    public function delete($id)
    {
        if (!session('logged_in'))
            return redirect()->to(base_url('auth'));
        (new BuatSuratModel())->where('id', $id)->delete();
        if (file_exists(FCPATH . "upload/buat_surat/$id.pdf"))
            unlink(FCPATH . "upload/buat_surat/$id.pdf");
        return $this->response->redirect(site_url('buatsurat'));
    }

    //Proses buat nomor surat
    public function create_no_surat($id)
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

    //Proses set status
    public function status($id, $status)
    {
        $model = new BuatSuratModel();
        $model->update($id, ['status' => $status]);
        return $this->response->redirect(site_url('buatsurat'));
    }

    //Proses set status
    public function verification($id, $jenis_user)
    {
        $row = (new BuatSuratModel())->where('id', $id)->first();
        $is_kadep = (bool) (new \App\Models\PegawaiModel())->where('id', $row->departemen_pegawai_id)->where('user_id', session('id'))->countAllResults();
        $is_penandatangan = (bool) (new \App\Models\PegawaiModel())->where('id', $row->penandatangan_pegawai_id)->where('user_id', session('id'))->countAllResults();

        $model = new BuatSuratModel();
        if ($jenis_user == 'verifikator') {
            $model->update($id, ['verifikasi_verifikator' => 1, 'status' => 2]);
            $db = \Config\Database::connect();
            $db->query("INSERT INTO notifications
                select null, id, 'buat_surat', 'surat', '0', now(), now() from users 
                where jenis_user IN('dekan', 'wadek', 'wadek1', 'wadek2', 'wadek3', 'wadek4')
            ");
            session()->setFlashdata('success', 'Berkas berhasil disetujui');
        } elseif ($is_penandatangan) {
            $model->update($id, ['status' => 3]);
            return $this->response->redirect(site_url("pdf/generate/$id/0/1"));
        }
        return $this->response->redirect(site_url('buatsurat'));
    }

    //Proses cetak data surat
    public function print()
    {
        $db = \Config\Database::connect();
        $data['buat_surat'] = $db->query("SELECT
        nama, nomor, pangkat
        FROM users
        JOIN buat_surat ON user_id=users.id")->getResult();
        return view('pdf/generate', $data);
    }

    //Proses kirim email
    public function send_email($to)
    {
        $email = \Config\Services::email();

        $email->setFrom('noreply-mipa@ugm.ac.id', 'Your Name');
        $email->setTo($to);
        $email->setCC('another@another-example.com');
        $email->setBCC('them@their-example.com');

        $email->setSubject('Email Test');
        $email->setMessage('Testing the email class.');

        $email->send();
        // var_dump($email);
    }

    //Proses upload data surat
    public function upload($id)
    {
        return view('surat/upload', ['id' => $id]);
    }

    public function uploads($id)
    {
        if (!empty($this->request->getFile('berkas')->getFilename())) {
            if (file_exists("upload/pertanggungjawaban_buat_surat/$id.pdf"))
                unlink("upload/pertanggungjawaban_buat_surat/$id.pdf");
            $file = $this->request->getFile('berkas');
            $file->move('upload/pertanggungjawaban_buat_surat', $id . '.pdf');
        }
        session()->setFlashdata('success', 'Berkas berhasil diupload');
        return $this->response->redirect(site_url('buatsurat'));
    }

    //Proses bagikan surat
    public function share($id)
    {
        $db = \Config\Database::connect();
        $users = $db->query("SELECT id, nama as name FROM users ORDER BY nama")->getResult();
        for ($i = 0; $i < count($users); $i++)
            $users[$i]->id = (int) $users[$i]->id;
        $row = (new BuatSuratModel())->where('id', $id)->first();
        $data = [
            'row' => $row,
            'users' => json_encode($users),
        ];
        return view('surat/share', $data);
    }

    //Proses simpan bagikan surat
    public function saveshare($id)
    {
        $shares = $this->request->getVar('shares');
        for ($i = 0; $i < count($shares); $i++)
            $shares[$i] = (int) $shares[$i];
        foreach ($shares as $share) {
            $db = \Config\Database::connect();
            $db->query("INSERT INTO notifications
                        select null, users.id, 'buat_surat', 'surat', '0', now(), now() from users 
                        where users.id = " . ($share ?? "''"));
        }
        $model = new BuatSuratModel();
        $model->update($id, ['shares' => json_encode($shares)]);
        session()->setFlashdata('success', 'Alhamdulillah.. Surat berhasil dibagikan!');
        return $this->response->redirect(site_url('buatsurat'));
    }

    //Proses upload dasar penerbitan
    public function upload_dasar_penerbitan($id)
    {
        if (!empty($this->request->getFile('berkas')) && !empty($this->request->getFile('berkas')->getFileName())) {
            if (file_exists("upload/dasar_penerbitan_buat_surat/$id.pdf"))
                unlink("upload/dasar_penerbitan_buat_surat/$id.pdf");
            $file = $this->request->getFile('berkas');
            $file->move('upload/dasar_penerbitan_buat_surat', $id . '.pdf');
        }
    }

    public function topdf($id, $save = false)
    {
        $filename = date('y-m-d_H.i.s') . '-buat_surat';

        // instantiate and use the dompdf class
        $dompdf = new Dompdf();
        $d = "Untuk cek validasi surat, silakah buka alamat berikut:\nhttps://mipa.ugm.ac.id/validasi-surat/\n\nKode surat: " . $id;
        $qr = (new QRCode)->render($d);
        $qr_note = "<br><i><b>Dokumen ini telah ditandatangani secara elektronik. Verifikasi keabsahan dokumen dapat dilakukan dengan scan QR code berikut.</b></i>";

        // load HTML content
        $db = \Config\Database::connect();

        $data['row'] = $db->query("SELECT
        no_surat, nama, nomor, pegawais.pangkat, tanggal_pengajuan,tanggal_kegiatan_mulai, tanggal_kegiatan_selesai, tanggal_kegiatan,
        nama_surat, lokasi_kegiatan, status,
        pegawais.nip nip, tabel, isi_surat, isi_lampiran, tembusan
        FROM users
        JOIN pegawais ON users.id = pegawais.user_id
        JOIN buat_surat ON buat_surat.user_id=pegawais.user_id
        WHERE buat_surat.id = '$id'")->getResult()[0];

        $data['penandatangan'] = $db->query("SELECT 
        pegawais.id id, nama_publikasi nama, pegawais.nip nip, prodi, departemen, pegawais.pangkat pangkat, golongan, jabatan, penandatangan.nama_penandatangan label
        FROM buat_surat 
        JOIN pegawais ON buat_surat.penandatangan_pegawai_id = pegawais.id
        JOIN penandatangan ON pegawais.id = penandatangan.pegawai_id
        WHERE buat_surat.id = '$id'")->getResult()[0];

        $data['row']->tanggal_kegiatan = json_decode($data['row']->tanggal_kegiatan) ?? [];

        $data['qr'] = $data['row']->status == 3 ? $qr : '';
        $data['qr_note'] = $data['row']->status == 3 ? $qr_note : '';
        $data['anggotas'] = [];

        $dompdf->getOptions()->setChroot(FCPATH);


        // return view('surat/print', $data);
        // exit;

        $dompdf->loadHtml(view('surat/print', $data));
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        // $output = $dompdf->output();
        // file_put_contents("upload/buat_surat/" . $id . ".pdf", $output);          
        // return $this->response->redirect(site_url('buatsurat'));
        if ($save) {
            file_put_contents("upload/buat_surat/$id.pdf", $dompdf->output());
            file_put_contents("validasi/$id.pdf", $dompdf->output());
        } else {
            $dompdf->stream("$filename.pdf", ['Attachment' => 0]);
            exit;
        }
    }
}