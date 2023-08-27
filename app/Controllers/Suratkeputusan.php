<?php

namespace App\Controllers;

use App\Models\SuratKeputusanModel;
use App\Models\DetailNomorSuratModel;
use App\Models\NomorSuratModel;
use App\Models\DepartemenModel;
use Hashids\Hashids;
use Dompdf\Dompdf;
use chillerlan\QRCode\{QRCode, QROptions};

class Suratkeputusan extends BaseController
{
    public $sk;

    function __construct()
    {
        $this->sk = new SuratKeputusanModel();
    }

    public function index($json = true)
    {
        // Dismiss Notifikasi
        $db = \Config\Database::connect();
        $db->query("UPDATE notifications SET status = 1 WHERE notification_type = 'surat_keputusan' AND user_id = '" . session('id') . "'");

        $data = $this->request->getGet();
        $q = $data['q'] ?? '';
        $status = $data['status'] ?? '';
        $sort_column = $data['sort_column'] ?? 'created_at';
        $sort_order = $data['sort_order'] ?? 'desc';

        $jenis_user = session('jenis_user');
        $id = session('id');
        $pegawai_id = session('pegawai_id');
        $gol_verifikator = session('gol_verifikator');
        $this->sk->select('surat_keputusan.*, users.nama')
            ->join('users', 'surat_keputusan.user_id = users.id')
            ->join('pegawais', 'surat_keputusan.penandatangan_pegawai_id = pegawais.user_id', 'left')
            ->join('departemen', 'surat_keputusan.departemen_pegawai_id = departemen.kepala_pegawai_id', 'left')
            ->where("IF(surat_keputusan.user_id = $id OR (JSON_CONTAINS(shares, CONCAT('[', $id ,']')) AND status = 3) OR ('$jenis_user' IN('admin')) OR ('$jenis_user' IN('verifikator') AND '$gol_verifikator' = departemen.id) OR ('$jenis_user' IN('dekan', 'wadek') AND status >= 2 AND penandatangan_pegawai_id = $pegawai_id) OR ('$jenis_user' IN('departemen') AND status = 3 AND departemen_pegawai_id = $pegawai_id), true, false )")
            ->where("CONCAT(nama_surat, nama) LIKE '%$q%'")
            ->where("status LIKE '%$status%'")
            ->orderBy($sort_column, $sort_order);
        $rows = $this->sk->paginate(10);

        $data = [
            'rows' => $rows,
            'pager' => $this->sk->pager,
            'jenis_user' => $jenis_user,
            'user_id' => $id,
            'pegawai_id' => $pegawai_id,
            'q' => $q,
            'status' => $status,
            'sort_column' => $sort_column,
            'sort_order' => $sort_order,
        ];
        return view('surat-keputusan/index', $data);
    }

    public function create()
    {
        $row_template = $this->sk->where('id', $this->request->getGet('template'))->first();
        $row = new SuratKeputusanModel();
        if (!empty($this->request->getGet('template'))) {
            $row = $row_template;
        }
        $row->no_surat = '';
        $row->tanggal_pengajuan = date('Y-m-d');
        $row->tembusan = [];
        $data = [
            'action' => 'store',
            'row' => $row,
            'departemens' => (new DepartemenModel)->join('pegawais', 'pegawais.id = departemen.kepala_pegawai_id')->get()->getResult(),
        ];
        return view('surat-keputusan/form', $data);
    }

    public function store()
    {
        // $id = substr(md5(date('YmdHis')), 0, 6);
        $id = (new \Hidehalo\Nanoid\Client())->formattedId(getenv('NANOID_ALPHABET'), 16);
        $data = $this->request->getPost();
        $data['id'] = $id;
        //if (isset($data['no_surat'])) $data['no_surat'] = $this->create_no_surat(base64_encode($data['no_surat']));
        $data['tembusan'] = json_encode($data['tembusan'] ?? []);
        $data['user_id'] = session()->get('id');
        $this->sk->insert($data);
        $this->upload_dasar_penerbitan($id);

        $db = \Config\Database::connect();
        $db->query("INSERT INTO notifications
            select null, id, 'surat_keputusan', 'surat', '0', now(), now() from users 
            where jenis_user IN('verifikator')
        ");

        session()->setFlashdata('success', 'Data berhasil disimpan');
        return $this->response->redirect(site_url('suratkeputusan'));
    }

    public function edit($id)
    {
        $row = $this->sk->where('id', $id)->first();
        $row->tembusan = json_decode($row->tembusan);
        $data = [
            'action' => 'update',
            'row' => $row,
            'departemens' => (new DepartemenModel)->join('pegawais', 'pegawais.id = departemen.kepala_pegawai_id')->get()->getResult(),
        ];
        return view('surat-keputusan/form', $data);
    }

    public function update($id)
    {
        $data = $this->request->getPost();
        if (!empty($data['tembusan']))
            $data['tembusan'] = json_encode($data['tembusan']);
        $this->sk->update($id, $data);
        $this->create_no_surat($id);

        $row = $this->sk
            ->join('users', 'surat_keputusan.user_id = users.id')
            ->where('surat_keputusan.id', $id)->first();
        $db = \Config\Database::connect();
        if (isset($data['status'])) {
            switch ($data['status']) {
                case -1:
                    $this->delete($id);
                    break;
                case 2:
                    $db->query("INSERT INTO notifications
                        select null, users.id, 'surat_keputusan', 'surat', '0', now(), now() from users 
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
                        'tujuan_surat' => "Surat Keputusan",
                        'sifat_surat' => "1",
                        'user_id' => $row->user_id
                    ]);
                    $db->query("INSERT INTO notifications
                        select null, users.id, 'surat_keputusan', 'surat', '0', now(), now() from users 
                        where users.id = " . ($row->user_id ?? "''"));
                    $email = \Config\Services::email();
                    $email->setFrom('noreply-mipa@ugm.ac.id', 'FMIPAUGM');
                    $email->setTo($row->username);
                    $email->setSubject('Verifikasi Surat Baru');
                    $email->setMessage('Surat dengan nomor: <a href="' . base_url("suratkeputusan/topdf/$id") . '">' . $row->no_surat . '</a> sudah terverifikasi.');
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
        return $this->response->redirect(site_url('suratkeputusan'));
    }

    public function delete($id)
    {
        if (!session('logged_in'))
            return redirect()->to(base_url('auth'));
        $this->sk->where('id', $id)->delete();
        session()->setFlashdata('success', 'Data berhasil terhapus');
        return $this->response->redirect(site_url('suratkeputusan'));
    }

    public function create_no_surat($id)
    {
        $row = $this->sk->where('id', $id)->first();
        if ($row->status == 3) {
            $arr = explode('/', $row->no_surat);
            $kode_klasifikasi = $arr[count($arr) - 2];
            $r = (new NomorSuratModel())->where('kode_klasifikasi', $kode_klasifikasi)->first();
            $increment = $r->nomor;
            $no_surat = $increment . $row->no_surat;
            $this->sk->update($id, ['no_surat' => $no_surat]);
            (new NomorSuratModel())->update($r->id, ['nomor' => ++$increment]);
        }
    }

    public function topdf($id, $save = false)
    {
        $filename = date('y-m-d_H.i.s') . '-surat_keputusan';
        $row = $this->sk->where('id', $id)->first();
        $row->tembusan = json_decode($row->tembusan);
        $d = "Untuk cek validasi surat, silakah buka alamat berikut:\nhttps://mipa.ugm.ac.id/validasi-surat/\n\nKode surat: " . $id;
        $qr = (new QRCode)->render($d);

        $data = [
            'row' => $row,
            'qr' => $row->status == 3 ? $qr : '',
        ];

        $db = \Config\Database::connect();
        $data['penandatangan'] = $db->query("SELECT 
        pegawais.id id, nama_publikasi nama, pegawais.nip nip, prodi, departemen, pegawais.pangkat pangkat, golongan, jabatan, penandatangan.nama_penandatangan label
        FROM surat_keputusan 
        JOIN pegawais ON surat_keputusan.penandatangan_pegawai_id = pegawais.id
        JOIN penandatangan ON pegawais.id = penandatangan.pegawai_id
        WHERE surat_keputusan.id = '$id'")->getResult()[0];

        //return view('surat-keputusan/print', $data); exit;
        $dompdf = new Dompdf();
        $dompdf->getOptions()->setChroot(FCPATH);
        $dompdf->loadHtml(view('surat-keputusan/print', $data));
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        if ($save) {
            file_put_contents("upload/surat_keputusan/$id.pdf", $dompdf->output());
            file_put_contents("validasi/$id.pdf", $dompdf->output());
        } else {
            $dompdf->stream("$filename.pdf", ['Attachment' => 0]);
            exit;
        }
    }

    //Proses upload dasar penerbitan
    public function upload_dasar_penerbitan($id)
    {
        if (!empty($this->request->getFile('berkas')) && !empty($this->request->getFile('berkas')->getFileName())) {
            if (file_exists("upload/dasar_penerbitan_surat_keputusan/$id.pdf"))
                unlink("upload/dasar_penerbitan_surat_keputusan/$id.pdf");
            $file = $this->request->getFile('berkas');
            $file->move('upload/dasar_penerbitan_surat_keputusan', $id . '.pdf');
        }
    }

    //Proses upload data surat
    public function upload($id)
    {
        return view('surat-keputusan/upload', ['id' => $id]);
    }

    public function uploads($id)
    {
        if (!empty($this->request->getFile('berkas')->getFilename())) {
            if (file_exists("upload/pertanggungjawaban_surat_keputusan/$id.pdf"))
                unlink("upload/pertanggungjawaban_surat_keputusan/$id.pdf");
            $file = $this->request->getFile('berkas');
            $file->move('upload/pertanggungjawaban_surat_keputusan', $id . '.pdf');
        }
        session()->setFlashdata('success', 'Berkas berhasil diupload');
        return $this->response->redirect(site_url('suratkeputusan'));
    }

    //Proses bagikan surat keputusan
    public function share($id)
    {
        $db = \Config\Database::connect();
        $users = $db->query("SELECT id, nama as name FROM users ORDER BY nama")->getResult();
        for ($i = 0; $i < count($users); $i++)
            $users[$i]->id = (int) $users[$i]->id;
        $row = (new SuratKeputusanModel)->where('id', $id)->first();
        $data = [
            'row' => $row,
            'users' => json_encode($users),
        ];
        return view('surat-keputusan/share', $data);
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
                        select null, users.id, 'surat_keputusan', 'surat', '0', now(), now() from users 
                        where users.id = " . ($share ?? "''"));
        }
        $model = new SuratKeputusanModel();
        $model->update($id, ['shares' => json_encode($shares)]);
        session()->setFlashdata('success', 'Surat berhasil dibagikan!');
        return $this->response->redirect(site_url('suratkeputusan'));
    }
}