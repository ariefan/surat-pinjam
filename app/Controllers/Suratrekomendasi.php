<?php

namespace App\Controllers;

use App\Models\SuratRekomendasiModel;
use App\Models\SuratTugasModel;
use App\Models\DetailNomorSuratModel;
use App\Models\NomorSuratModel;
use App\Models\DepartemenModel;
use Dompdf\Dompdf;
use chillerlan\QRCode\{QRCode, QROptions};
use App\Models\TodoModel;
use App\Models\UserModel;
use App\Models\PegawaiModel;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class Suratrekomendasi extends BaseController
{
    private $pjs;
    private $st;

    function __construct()
    {
        $this->st = new SuratRekomendasiModel();

        $pjs = [];
    }

    //Ketika tampilan awal menu surat tugas
    public function index($count = false)
    {
        // Dismiss Notifikasi
        $db = \Config\Database::connect();
        $db->query("UPDATE notifications SET status = 1 WHERE notification_type = 'surat_rekomendasi' AND user_id = '" . session('id') . "'");

        $data = $this->request->getGet();
        $q = $data['q'] ?? '';
        $status = $data['status'] ?? '';
        $sort_column = $data['sort_column'] ?? 'created_at';
        $sort_order = $data['sort_order'] ?? 'desc';

        $jenis_user = session('jenis_user');
        $id = session('id');
        $pegawai_id = session('pegawai_id');
        $gol_verifikator = session('gol_verifikator');
        $this->st->select('surat_rekomendasi.*, users.nama, date(NOW()) <= tanggal_kegiatan_mulai IS NOT NULL as dalam_periode')
            ->join('users', 'surat_rekomendasi.user_id = users.id')
            ->join('pegawais', 'surat_rekomendasi.penandatangan_pegawai_id = pegawais.user_id', 'left')
            ->join('departemen', 'surat_rekomendasi.departemen_pegawai_id = departemen.kepala_pegawai_id', 'left')
            ->where("IF(surat_rekomendasi.user_id = $id OR (JSON_CONTAINS(shares, CONCAT('[', $id ,']')) AND status = 3) OR ('$jenis_user' IN('admin')) OR ('$jenis_user' IN('verifikator') AND '$gol_verifikator' = departemen.id) OR ('$jenis_user' IN('dekan', 'wadek') AND status >= 2 AND penandatangan_pegawai_id = $pegawai_id) OR ('$jenis_user' IN('departemen') AND status = 3 AND departemen_pegawai_id = $pegawai_id), true, false )")
            ->where("CONCAT(nama_surat, nama) LIKE '%$q%'")
            ->where("status LIKE '%$status%'")
            ->orderBy($sort_column, $sort_order);

        if ($count) {
            echo $this->st->countAllResults();
            exit;
        }

        $rows = $this->st->paginate(10);

        $data = [
            'rows' => $rows,
            'pager' => $this->st->pager,
            'jenis_user' => $jenis_user,
            'user_id' => $id,
            'pegawai_id' => $pegawai_id,
            'q' => $q,
            'status' => $status,
            'sort_column' => $sort_column,
            'sort_order' => $sort_order,
        ];
        return view('surat-rekomendasi/index', $data);
    }

    //Proses input data surat baru
    public function create()
    {
        $row = new SuratRekomendasiModel();
        $row->tanggal_pengajuan = date('Y-m-d');
        $row->tembusan = [];
        $row->tanggal_kegiatan = [];
        $row->peserta = [];
        $data = [
            'action' => 'store',
            'row' => $row,
            'details' => [],
            'nama_penandatangan' => null,
            'pjs' => $this->pjs,
            'departemens' => (new DepartemenModel)->join('pegawais', 'pegawais.id = departemen.kepala_pegawai_id')->where('departemen.id', '7')->get()->getResult(),
        ];
        return view('surat-rekomendasi/form', $data);
    }

    //Proses menyimpan hasil inputan data baru ke database
    public function store()
    {
        // $id = substr(md5(date('YmdHis')), 0, 6);
        
        $id = (new \Hidehalo\Nanoid\Client())->formattedId(getenv('NANOID_ALPHABET'), 16);
        $data = $this->request->getPost();
        dump($data);
        $data['id'] = $id;
        //if (isset($data['no_surat'])) $data['no_surat'] = $this->create_no_surat(base64_encode($data['no_surat']));

        if (!empty($data['namaMhs'])) {
            $mhs = [];
            foreach ($data['namaMhs'] as $key => $v) {
                $mhs[$key] = [
                    'nama' => $v,
                    'nim' => $data['nimMhs'][$key],
                    'prodi' => $data['prodiMhs'][$key],
                ];
            }
            $data['peserta'] = json_encode($mhs);
        }


        // if (!empty($data['namaMhs'])) {
        //     $mhs = [
        //         "nama" => NULL,
        //         "nim" => NULL,
        //         "prodi" => NULL
        //     ];
        //     foreach ($data['namaMhs'] as $key => $v) {
        //         if (!empty($v)) {$mhs[$key] = ['nama'=>$v];}
        //         if (!empty($data['nimMhs'][$key])) {$mhs[$key] = ['nim'=>$data['nimMhs'][$key]];}
        //         if (!empty($data['prodiMhs'][$key])) {$mhs[$key] = ['prodi'=>$data['prodiMhs'][$key]];}
        //     }
        //     $data['peserta'] = json_encode($mhs, JSON_NUMERIC_CHECK);}
        // else {
        //     $mhs = [];
        //     $data['peserta'] = json_encode($mhs, JSON_NUMERIC_CHECK); 
        // }

        if (!empty($data['tembusan'])) {
            $shares = [];
            foreach ($data['tembusan'] as $val) {
                $p = (new PegawaiModel())->where('nama_publikasi', $val)->first();
                if (!empty($p)) $shares[] = (int) $p->user_id;
            }
            $data['tembusan'] = json_encode($data['tembusan']);
            $data['shares'] = json_encode($shares, JSON_NUMERIC_CHECK);
        }


        //$data['tembusan'] = json_encode($data['tembusan'] ?? []);
        $data['tanggal_kegiatan'] = $data['tanggal_kegiatan'] ?? [];
        sort($data['tanggal_kegiatan']);
        if (count($data['tanggal_kegiatan']) > 0) $data['tanggal_kegiatan_mulai'] = $data['tanggal_kegiatan'][0];
        if (count($data['tanggal_kegiatan']) > 0) $data['tanggal_kegiatan_selesai'] = $data['tanggal_kegiatan'][count($data['tanggal_kegiatan']) - 1];
        $data['tanggal_kegiatan'] = json_encode($data['tanggal_kegiatan'] ?? []);
        $data['user_id'] = session()->get('id');
        $this->st->insert($data);
        // $this->upload_dasar_penerbitan($id);
        // dump($data);
        $db = \Config\Database::connect();
        $db->query("INSERT INTO notifications
            select null, id, 'surat_rekomendasi', 'surat', '0', now(), now() from users 
            where jenis_user IN('verifikator')
        ");

        $this->topdf($id, true);
        session()->setFlashdata('success', 'Data berhasil disimpan');
        if ($data['status'] == 'preview') session()->setFlashdata('preview', $id);
        return $this->response->redirect(site_url('suratrekomendasi'));
        // return dump($data);
    }

    //Proses edit data surat baru
    public function edit($id)
    {
        $row = (new SuratRekomendasiModel)->where('id', $id)->first();
        $row->tembusan = json_decode($row->tembusan);
        $row->tanggal_kegiatan = json_decode($row->tanggal_kegiatan);
        $row->peserta = json_decode($row->peserta);
        $db = \Config\Database::connect();
        // $nama_penandatangan = $db->query("SELECT nama_publikasi nama FROM pegawais WHERE id=$row->penandatangan_pegawai_id")->getResult()[0]->nama;
        $data = [
            'action' => 'update',
            'row' => $row,
            // 'nama_penandatangan' => $nama_penandatangan,
            // 'tembusans' => json_decode($row->tembusan),
            'pjs' => $this->pjs,
            'departemens' => (new DepartemenModel)->join('pegawais', 'pegawais.id = departemen.kepala_pegawai_id')->where('departemen.id', '7')->get()->getResult(),
        ];
        return view('surat-rekomendasi/form', $data);
    }

    public function send_notif($user_id, $msg)
    {
        $data['pemberi_tugas_user_id'] = session('id');
        if (empty($data['user_ids'])) $data['user_ids'][] = $user_id;
        foreach ($data['user_ids'] as $val) {
            $data['user_id'] = $val;
            $data['tugas'] = $msg;
            (new TodoModel())->insert($data);
        }

        $notifTokens = (new \App\Models\NotificationTokenModel())->where('user_id', $data['user_id'])->findAll();
        foreach ($notifTokens as $notifToken) {
            try {
                $factory = (new Factory)->withServiceAccount('/var/www/surat/fmipa-8a1b4-firebase-adminsdk-g8rl3-a81c70c820.json');
                $messaging = $factory->createMessaging();
                $message = CloudMessage::withTarget('token', $notifToken->fcmtoken)
                    ->withNotification(Notification::create('Anda memiliki tugas baru!', $msg))
                    ->withData(['user_id' => $data['user_id']]);
                $messaging->send($message);
            } catch (\Throwable $t) {
                (new \App\Models\NotificationTokenModel())->where('fcmtoken', $notifToken->fcmtoken)->delete();
                continue;
            }
        }
    }

    public function update($id)
    {
        $data = $this->request->getPost();
        if (!empty($data['tembusan'])) {
            $tembusan = [];
            foreach ($data['tembusan'] as $val) {
                if (!empty($val)) $tembusan[] = $val;
            }
            $data['tembusan'] = json_encode($tembusan);
        } else {
            $data['tembusan'] = '[]';
        }

        if (!empty($data['namaMhs'])) {
            $mhs = [];
            foreach ($data['namaMhs'] as $key => $v) {
                if (!empty($v)) {
                    $mhs[$key] = [
                        'nama' => $v,
                        'nim' => $data['nimMhs'][$key],
                        'prodi' => $data['prodiMhs'][$key],
                    ];
                }
            }
            $data['peserta'] = json_encode($mhs);
        }


        if (!empty($data['tanggal_kegiatan'])) {
            $data['tanggal_kegiatan'] = $data['tanggal_kegiatan'] ?? [];
            sort($data['tanggal_kegiatan']);
            if (count($data['tanggal_kegiatan']) > 0) $data['tanggal_kegiatan_mulai'] = $data['tanggal_kegiatan'][0];
            if (count($data['tanggal_kegiatan']) > 0) $data['tanggal_kegiatan_selesai'] = $data['tanggal_kegiatan'][count($data['tanggal_kegiatan']) - 1];
            $data['tanggal_kegiatan'] = json_encode($data['tanggal_kegiatan']);
        }
        $this->st->update($id, $data);
        $this->create_no_surat($id);

        $row = $this->st
            ->join('users', 'surat_rekomendasi.user_id = users.id')
            ->where('surat_rekomendasi.id', $id)->first();
        $db = \Config\Database::connect();
        if (isset($data['status'])) {
            switch ($data['status']) {
                case -1:
                    $this->st->update($id, ['status' => $row->status + $data['status']]);
                    // $this->delete($id);
                    break;
                case 2:
                    $db->query("INSERT INTO notifications
                        select null, users.id, 'surat_rekomendasi', 'surat', '0', now(), now() from users 
                        join pegawais on pegawais.user_id = users.id 
                        where pegawais.id = " . ($row->penandatangan_pegawai_id ?? "''"));
                    $pegawai = (new \App\Models\PegawaiModel())->find($row->penandatangan_pegawai_id ?? '');
                    if ($pegawai) {
                        $msg = 'Anda harus menandatangani surat tugas ini';
                        $this->send_notif($pegawai->user_id, $msg);
                        $email = \Config\Services::email();
                        $email->setFrom('no-reply@fmipaugm.id', 'FMIPAUGM');
                        $user = (new \App\Models\UserModel())->find($pegawai->user_id ?? '');
                        $email->setTo($user->username);
                        $email->setSubject('Verifikasi Surat Baru');
                        $email->setMessage('Surat dengan nomor: <a href="' . base_url("suratrekomendasi/topdf/$id") . '">' . $row->no_surat . '</a> menunggu verifikasi anda.');
                        if (!$email->send()) echo $email->printDebugger(['headers']);
                    }
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
                        'no_surat' => $row->no_surat, 'perihal' => $row->nama_surat, 'penandatangan' => $arr[2], 'pengolah' => $arr[3], 'kode_perihal' => $kode_perihal,
                        'klasifikasi_surat' => $kode_klasifikasi, 'tanggal_surat' => $row->tanggal_pengajuan, 'tujuan_surat' => "Surat Tugas", 'sifat_surat' => "1", 'user_id' => $row->user_id
                    ]);
                    $db->query("INSERT INTO notifications
                        select null, users.id, 'surat_rekomendasi', 'surat', '0', now(), now() from users 
                        where users.id = " . ($row->user_id ?? "''"));
                    $email = \Config\Services::email();
                    $email->setFrom('no-reply@fmipaugm.id', 'FMIPAUGM');
                    $email->setTo($row->username);
                    $email->setSubject('Verifikasi Surat Baru');
                    $email->setMessage('Surat dengan nomor: <a href="' . base_url("suratrekomendasi/topdf/$id") . '">' . $row->no_surat . '</a> sudah terverifikasi.');
                    if (!$email->send()) echo $email->printDebugger(['headers']);
                    break;
                default:
                    break;
            }
        }
        $this->topdf($id, true);
        $this->upload_dasar_penerbitan($id);
        session()->setFlashdata('success', 'Data berhasil disimpan');
        if (isset($data['status'])) {
            if ($data['status'] == 'preview') session()->setFlashdata('preview', $id);
        }
        return $this->response->redirect(site_url('suratrekomendasi'));
    }

    //Proses update komentar
    public function updatekomentar($id)
    {
        $model = new SuratRekomendasiModel();
        $data = [
            'komentar' => $this->request->getVar('komentar'),
        ];
        $model->update($id, $data);
        return $this->response->redirect(site_url('suratrekomendasi'));
    }

    //Proses delete data surat
    public function delete($id)
    {
        if (!session('logged_in')) return redirect()->to(base_url('auth'));
        (new SuratRekomendasiModel)->where('id', $id)->delete();
        if (file_exists(FCPATH . "upload/surat_rekomendasi/$id.pdf")) unlink(FCPATH . "upload/surat_rekomendasi/$id.pdf");
        return $this->response->redirect(site_url('suratrekomendasi'));
    }

    //Proses buat nomor surat
    public function create_no_surat($id)
    {
        $row = $this->st->where('id', $id)->first();
        if ($row->status == 3) {
            $arr = explode('/', $row->no_surat);
            $kode_klasifikasi = $arr[count($arr) - 2];
            $r = (new NomorSuratModel())->where('kode_klasifikasi', $kode_klasifikasi)->first();
            $increment = $r->nomor;
            $no_surat = $increment . $row->no_surat;
            $this->st->update($id, ['no_surat' => $no_surat]);
            (new NomorSuratModel())->update($r->id, ['nomor' => ++$increment]);
        }
    }

    //Proses set status
    public function status($id, $status)
    {
        $model = new SuratRekomendasiModel();
        $model->update($id, ['status' => $status]);
        return $this->response->redirect(site_url('suratrekomendasi'));
    }

    //Proses set status
    // public function verification($id, $jenis_user)
    // {
    //     $row = (new SuratRekomendasiModel)->where('id', $id)->first();
    //     $is_kadep = (bool)(new \App\Models\PegawaiModel())->where('id', $row->departemen_pegawai_id)->where('user_id', session('id'))->countAllResults();
    //     $is_penandatangan = (bool)(new \App\Models\PegawaiModel())->where('id', $row->penandatangan_pegawai_id)->where('user_id', session('id'))->countAllResults();

    //     $model = new SuratRekomendasiModel();
    //     if ($jenis_user == 'verifikator') {
    //         if ($row->status == 2){
    //             $model->update($id, ['status' => 3]);
    //             return $this->response->redirect(site_url("pdf/generate/$id/0/1"));
    //         }
    //         else{
    //         $model->update($id, ['verifikasi_verifikator' => 1, 'status' => 2]);
    //         $db = \Config\Database::connect();
    //         $db->query("INSERT INTO notifications
    //             select null, id, 'surat_rekomendasi', 'surat', '0', now(), now() from users 
    //             where jenis_user IN('dekan', 'wadek', 'wadek1', 'wadek2', 'wadek3', 'wadek4')
    //         ");
    //         session()->setFlashdata('success', 'Berkas berhasil disetujui');
    //         }
    //     } elseif ($is_penandatangan) {
    //         $model->update($id, ['status' => 3]);
    //         return $this->response->redirect(site_url("pdf/generate/$id/0/1"));
    //     }
    //     return $this->response->redirect(site_url('suratrekomendasi'));
    // }

    //Proses cetak data surat
    public function print()
    {
        $db = \Config\Database::connect();
        $data['surat_rekomendasi'] = $db->query("SELECT
        nama, nomor, pangkat
        FROM users
        JOIN surat_rekomendasi ON user_id=users.id")->getResult();
        return view('pdf/generate', $data);
    }

    //Proses kirim email
    public function send_email($to)
    {
        $email = \Config\Services::email();

        $email->setFrom('your@example.com', 'Your Name');
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
        return view('surat_rekomendasi/upload', ['id' => $id]);
    }

    public function uploads($id)
    {
        if (!empty($this->request->getFile('berkas')->getFilename())) {
            if (file_exists("upload/pertanggungjawaban_surat_rekomendasi/$id.pdf")) unlink("upload/pertanggungjawaban_surat_rekomendasi/$id.pdf");
            $file = $this->request->getFile('berkas');
            $file->move('upload/pertanggungjawaban_surat_rekomendasi',  $id . '.pdf');
        }
        session()->setFlashdata('success', 'Berkas berhasil diupload');
        return $this->response->redirect(site_url('suratrekomendasi'));
    }

    // public function uploads($id)
    // {
    //     $db = \Config\Database::connect();
    //     $result = $db->query("SELECT file_pertanggungjawaban, date(NOW()) BETWEEN tanggal_kegiatan_mulai AND tanggal_kegiatan_selesai as dalam_periode FROM surat_rekomendasi WHERE id = '$id'")->getResult();

    //     $old_name = $result[0]->file_pertanggungjawaban;
    //     if (file_exists("upload/pertanggungjawaban_surat_rekomendasi/$old_name.pdf")) unlink("upload/pertanggungjawaban_surat_rekomendasi/$old_name.pdf");

    //     $file = $this->request->getFile('berkas');
    //     $name = hash_file('ripemd160', $file);
    //     $file->move('upload/pertanggungjawaban_surat_rekomendasi',  $name . '.pdf');

    //     (new SuratRekomendasiModel())->update($id, ['file_pertanggungjawaban' => $name . '.pdf']);
    //     session()->setFlashdata('success', 'Berkas berhasil diupload');
    //     return $this->response->redirect(site_url('suratrekomendasi'));
    // }

    //Proses bagikan surat tugas
    public function share($id)
    {
        $db = \Config\Database::connect();
        $users = $db->query("SELECT id, nama as name FROM users ORDER BY nama")->getResult();
        for ($i = 0; $i < count($users); $i++) $users[$i]->id = (int)$users[$i]->id;
        $row = (new SuratRekomendasiModel)->where('id', $id)->first();
        $data = [
            'row' => $row,
            'users' => json_encode($users),
        ];
        return view('surat-rekomendasi/share', $data);
    }

    //Proses simpan bagikan surat
    public function saveshare($id)
    {
        $shares = $this->request->getVar('shares');
        for ($i = 0; $i < count($shares); $i++) $shares[$i] = (int)$shares[$i];
        foreach ($shares as $share) {
            $db = \Config\Database::connect();
            $db->query("INSERT INTO notifications
                        select null, users.id, 'surat_rekomendasi', 'surat', '0', now(), now() from users 
                        where users.id = " . ($share ?? "''"));
        }
        $model = new SuratRekomendasiModel();
        $model->update($id, ['shares' => json_encode($shares)]);
        session()->setFlashdata('success', 'Alhamdulillah.. Surat berhasil dibagikan!');
        return $this->response->redirect(site_url('suratrekomendasi'));
    }

    //Proses upload dasar penerbitan
    public function upload_dasar_penerbitan($id)
    {
        if (!empty($this->request->getFile('berkas')) && !empty($this->request->getFile('berkas')->getFileName())) {
            if (file_exists("upload/dasar_penerbitan_surat_rekomendasi/$id.pdf")) unlink("upload/dasar_penerbitan_surat_rekomendasi/$id.pdf");
            $file = $this->request->getFile('berkas');
            $file->move('upload/dasar_penerbitan_surat_rekomendasi',  $id . '.pdf');
        }
    }

    public function topdf($id, $save = false)
    {
        $filename = date('y-m-d_H.i.s') . '-surat_rekomendasi';

        // instantiate and use the dompdf class
        $dompdf = new Dompdf();
        $d = "Untuk cek validasi surat, silakah buka alamat berikut:\nhttps://mipa.ugm.ac.id/validasi-surat/\n\nKode surat: " . $id;
        $qr = (new QRCode)->render($d);
        $qr_note = "<br><i><b>Dokumen ini telah ditandatangani secara elektronik. Verifikasi keabsahan dokumen dapat dilakukan dengan scan QR code berikut.</b></i>";

        // load HTML content
        $db = \Config\Database::connect();

        $data['row'] = $db->query("SELECT
        no_surat, nama, no_surat, pegawais.pangkat, tanggal_pengajuan,tanggal_kegiatan_mulai, tanggal_kegiatan_selesai, tanggal_kegiatan,
        nama_surat, lokasi_kegiatan, status,
        pegawais.nip nip, tembusan, kepada_surat, peserta, jenis_kegiatan, alamat_kegiatan, alamat_tambahan_satu, alamat_tambahan_dua
        FROM users
        JOIN pegawais ON users.id = pegawais.user_id
        JOIN surat_rekomendasi ON surat_rekomendasi.user_id=pegawais.user_id
        WHERE surat_rekomendasi.id = '$id'")->getResult()[0];

        $data['penandatangan'] = $db->query("SELECT 
        pegawais.id id, nama_publikasi nama, pegawais.nip nip, prodi, departemen, pegawais.pangkat pangkat, golongan, jabatan, penandatangan.nama_penandatangan label
        FROM surat_rekomendasi 
        JOIN pegawais ON surat_rekomendasi.penandatangan_pegawai_id = pegawais.id
        JOIN penandatangan ON pegawais.id = penandatangan.pegawai_id
        WHERE surat_rekomendasi.id = '$id'")->getResult();

        if (count($data['penandatangan']) > 0) {
            $data['penandatangan'] = $data['penandatangan'][0];
        } else {
            $data['penandatangan'] = [];
        }

        $data['row']->tanggal_kegiatan = json_decode($data['row']->tanggal_kegiatan) ?? [];
        $data['row']->peserta = json_decode($data['row']->peserta) ?? [];


        $data['qr'] = $data['row']->status == 3 ? $qr : '';
        $data['qr_note'] = $data['row']->status == 3 ? $qr_note : '';
        $data['anggotas'] = [];

        $dompdf->getOptions()->setChroot(FCPATH);


        // return view('surat-rekomendasi/print', $data);
        // exit;

        $dompdf->loadHtml(view('surat-rekomendasi/print', $data));
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        // $output = $dompdf->output();
        // file_put_contents("upload/surat_rekomendasi/" . $id . ".pdf", $output);          
        // return $this->response->redirect(site_url('suratrekomendasi'));
        if ($save) {
            file_put_contents("upload/surat_rekomendasi/$id.pdf", $dompdf->output());
            file_put_contents("validasi/$id.pdf", $dompdf->output());
        } else {
            $dompdf->stream("$filename.pdf", ['Attachment' => 0]);
        }
    }
}
