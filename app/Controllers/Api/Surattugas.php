<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\SuratTugasModel;
use App\Models\DetailNomorSuratModel;
use App\Models\NomorSuratModel;
use App\Models\TodoModel;
use chillerlan\QRCode\{QRCode, QROptions};
use Dompdf\Dompdf;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class Surattugas extends ResourceController
{
    protected $modelName = 'App\Models\SuratTugasModel';
    protected $format = 'json';

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $key = getenv('TOKEN_SECRET');
        $header = $this->request->getServer('HTTP_AUTHORIZATION');
        if (!$header)
            return $this->failUnauthorized('Token Required');
        $token = explode(' ', $header)[1];

        $decoded = JWT::decode($token, new Key($key, 'HS256'));
        // return $this->respond($decoded);
        $user = (new \App\Models\UserModel())->where('id', $decoded->id)->first();
        $pegawai = (new \App\Models\PegawaiModel())->where('user_id', $decoded->id)->first();

        $data = $this->request->getGet();
        $q = $data['q'] ?? '';
        $status = $data['status'] ?? '';
        $sort_column = $data['sort_column'] ?? 'created_at';
        $sort_order = $data['sort_order'] ?? 'desc';

        $jenis_user = $decoded->jenisUser;
        $id = $decoded->id;
        $pegawai_id = $pegawai->id;
        $gol_verifikator = $user->gol_verifikator;
        $this->model->select('surat_tugas.*, users.nama, date(NOW()) <= tanggal_kegiatan_mulai OR file_pertanggungjawaban IS NOT NULL as dalam_periode')
            ->join('users', 'surat_tugas.user_id = users.id')
            ->join('pegawais', 'surat_tugas.penandatangan_pegawai_id = pegawais.user_id', 'left')
            ->join('departemen', 'surat_tugas.departemen_pegawai_id = departemen.kepala_pegawai_id', 'left')
            ->where("IF(surat_tugas.user_id = $id OR (JSON_CONTAINS(shares, CONCAT('[', $id ,']')) AND status = 3) OR ('$jenis_user' IN('admin')) OR ('$jenis_user' IN('verifikator') AND '$gol_verifikator' = departemen.id) OR ('$jenis_user' IN('dekan', 'wadek') AND status >= 2 AND penandatangan_pegawai_id = $pegawai_id) OR ('$jenis_user' IN('departemen') AND status = 3 AND departemen_pegawai_id = $pegawai_id), true, false )")
            ->where("CONCAT(nama_surat, nama, tabel) LIKE '%$q%'")
            ->where("status LIKE '%$status%'")
            ->orderBy($sort_column, $sort_order);

        $rows = $this->model->paginate(10);
        return $this->respond($rows);

        $data = [
            'rows' => $rows,
            'pager' => $this->model->pager,
            'jenis_user' => $jenis_user,
            'user_id' => $id,
            'pegawai_id' => $pegawai_id,
            'q' => $q,
            'status' => $status,
            'sort_column' => $sort_column,
            'sort_order' => $sort_order,
        ];
        return view('surat-tugas/index', $data);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $record = $this->model->find($id);
        if (!$record) {
            # code...
            return $this->failNotFound(
                sprintf(
                    'post with id %d not found',
                    $id
                )
            );
        }

        return $this->respond($record);
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new ()
    {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $key = getenv('TOKEN_SECRET');
        $header = $this->request->getServer('HTTP_AUTHORIZATION');
        if (!$header)
            return $this->failUnauthorized('Token Required');
        $token = explode(' ', $header)[1];
        $decoded = JWT::decode($token, new Key($key, 'HS256'));
        $notifTokens = (new \App\Models\NotificationTokenModel())->where('user_id', $decoded->id)->findAll();

        $data = $this->request->getJson();
        if (!$this->model->save($data)) {
            return $this->fail($this->model->errors());
        }
        foreach ($notifTokens as $notifToken) {
            try {
                $factory = (new Factory)->withServiceAccount('/var/www/surat/fmipa-8a1b4-firebase-adminsdk-g8rl3-a81c70c820.json');
                $messaging = $factory->createMessaging();
                $message = CloudMessage::withTarget('token', $notifToken->fcmtoken)
                    ->withNotification(Notification::create('Anda memiliki tugas baru!', $data->tugas))
                    ->withData(['user_id' => $decoded->id]);
                $messaging->send($message);
            } catch (\Throwable $t) {
                (new \App\Models\NotificationTokenModel())->where('fcmtoken', $notifToken->fcmtoken)->delete();
                continue;
            }
        }

        // return $this->respondCreated($message, 'post created');
        return $this->respond($data, 200, 'post created');
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        //
    }

    public function send_notif($user_id, $msg)
    {
        $data['pemberi_tugas_user_id'] = session('id');
        if (empty($data['user_ids']))
            $data['user_ids'][] = $user_id;
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

    public function update($id = null)
    {
        $data = (array) $this->request->getJson();
        //if (array_key_exists('tembusan', $data)) {
        if (!empty($data['tembusan'])) {
            $tembusan = [];
            foreach ($data['tembusan'] as $val) {
                if (!empty($val))
                    $tembusan[] = $val;
            }
            $data['tembusan'] = json_encode($tembusan);
        } else {
            //$data['tembusan'] = '[]';
        }
        //}
        if (!empty($data['tanggal_kegiatan'])) {
            $data['tanggal_kegiatan'] = $data['tanggal_kegiatan'] ?? [];
            sort($data['tanggal_kegiatan']);
            if (count($data['tanggal_kegiatan']) > 0)
                $data['tanggal_kegiatan_mulai'] = $data['tanggal_kegiatan'][0];
            if (count($data['tanggal_kegiatan']) > 0)
                $data['tanggal_kegiatan_selesai'] = $data['tanggal_kegiatan'][count($data['tanggal_kegiatan']) - 1];
            $data['tanggal_kegiatan'] = json_encode($data['tanggal_kegiatan']);
        }
        $this->model->update($id, $data);
        $this->create_no_surat($id);

        $row = $this->model
            ->join('users', 'surat_tugas.user_id = users.id')
            ->where('surat_tugas.id', $id)->first();
        $db = \Config\Database::connect();
        if (isset($data['status'])) {
            switch ($data['status']) {
                case -1:
                    $this->model->update($id, ['status' => $row->status + $data['status']]);
                    // $this->delete($id);
                    break;
                case 2:
                    $db->query("INSERT INTO notifications
                        select null, users.id, 'surat_tugas', 'surat', '0', now(), now() from users 
                        join pegawais on pegawais.user_id = users.id 
                        where pegawais.id = " . ($row->penandatangan_pegawai_id ?? "''"));
                    $pegawai = (new \App\Models\PegawaiModel())->find($row->penandatangan_pegawai_id ?? '');
                    if ($pegawai) {
                        $msg = 'Anda harus menandatangani surat tugas ini';
                        $this->send_notif($pegawai->user_id, $msg);
                        $email = \Config\Services::email();
                        $email->setFrom('noreply-mipa@ugm.ac.id', 'FMIPAUGM');
                        $user = (new \App\Models\UserModel())->find($pegawai->user_id ?? '');
                        $email->setTo($user->username);
                        $email->setSubject('Verifikasi Surat Baru');
                        $email->setMessage('Surat dengan nomor: <a href="' . base_url("surattugas/topdf/$id") . '">' . $row->no_surat . '</a> menunggu verifikasi anda.');
                        if (!$email->send())
                            echo $email->printDebugger(['headers']);
                    }
                    break;
                case 3:
                    $arr = explode('/', $row->no_surat);
                    $arr_klasifikasi = explode('.', $arr[4]);
                    $kode_perihal = $arr_klasifikasi[0];
                    array_shift($arr_klasifikasi);
                    $kode_klasifikasi = implode('.', $arr_klasifikasi);
                    (new DetailNomorSuratModel())->insert([
                        'no_surat' => $row->no_surat,
                        'perihal' => $row->nama_surat,
                        'penandatangan' => $arr[2],
                        'pengolah' => $arr[3],
                        'kode_perihal' => $kode_perihal,
                        'klasifikasi_surat' => $kode_klasifikasi,
                        'tanggal_surat' => $row->tanggal_pengajuan,
                        'tujuan_surat' => "Surat Tugas",
                        'sifat_surat' => "1",
                        'user_id' => $row->user_id
                    ]);
                    $db->query("INSERT INTO notifications
                        select null, users.id, 'surat_tugas', 'surat', '0', now(), now() from users 
                        where users.id = " . ($row->user_id ?? "''"));
                    $email = \Config\Services::email();
                    $email->setFrom('noreply-mipa@ugm.ac.id', 'FMIPAUGM');
                    $email->setTo($row->username);
                    $email->setSubject('Verifikasi Surat Baru');
                    $email->setMessage('Surat dengan nomor: <a href="' . base_url("surattugas/topdf/$id") . '">' . $row->no_surat . '</a> sudah terverifikasi.');
                    if (!$email->send())
                        echo $email->printDebugger(['headers']);
                    break;
                default:
                    break;
            }
        }
        $this->topdf($id, true);
        $this->upload_dasar_penerbitan($id);
        session()->setFlashdata('success', 'Data berhasil disimpan');
        if (isset($data['status'])) {
            if ($data['status'] == 'preview')
                session()->setFlashdata('preview', $id);
        }
        return $this->respond($data, 200, 'surat updated');
    }

    //Proses buat nomor surat
    public function create_no_surat($id)
    {
        $row = $this->model->where('id', $id)->first();
        if ($row->status == 3) {
            $arr = explode('/', $row->no_surat);
            $kode_klasifikasi = $arr[count($arr) - 2];
            $r = (new NomorSuratModel())->where('kode_klasifikasi', $kode_klasifikasi)->first();
            $increment = $r->nomor;
            $no_surat = $increment . $row->no_surat;
            $this->model->update($id, ['no_surat' => $no_surat]);
            (new NomorSuratModel())->update($r->id, ['nomor' => ++$increment]);
        }
    }

    //Proses upload dasar penerbitan
    public function upload_dasar_penerbitan($id)
    {
        if (!empty($this->request->getFile('berkas')) && !empty($this->request->getFile('berkas')->getFileName())) {
            if (file_exists("upload/dasar_penerbitan_surat_tugas/$id.pdf"))
                unlink("upload/dasar_penerbitan_surat_tugas/$id.pdf");
            $file = $this->request->getFile('berkas');
            $file->move('upload/dasar_penerbitan_surat_tugas', $id . '.pdf');
        }
    }

    public function topdf($id, $save = false)
    {
        $filename = date('y-m-d_H.i.s') . '-surat_tugas';

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
        pegawais.nip nip, tabel, tembusan, paragraf_baru
        FROM users
        JOIN pegawais ON users.id = pegawais.user_id
        JOIN surat_tugas ON surat_tugas.user_id=pegawais.user_id
        WHERE surat_tugas.id = '$id'")->getResult()[0];

        $data['penandatangan'] = $db->query("SELECT 
        pegawais.id id, nama_publikasi nama, pegawais.nip nip, prodi, departemen, pegawais.pangkat pangkat, golongan, jabatan, penandatangan.nama_penandatangan label
        FROM surat_tugas 
        JOIN pegawais ON surat_tugas.penandatangan_pegawai_id = pegawais.id
        JOIN penandatangan ON pegawais.id = penandatangan.pegawai_id
        WHERE surat_tugas.id = '$id'")->getResult();

        if (count($data['penandatangan']) > 0) {
            $data['penandatangan'] = $data['penandatangan'][0];
        } else {
            $data['penandatangan'] = [];
        }

        $data['row']->tanggal_kegiatan = json_decode($data['row']->tanggal_kegiatan) ?? [];

        $data['qr'] = $data['row']->status == 3 ? $qr : '';
        $data['qr_note'] = $data['row']->status == 3 ? $qr_note : '';
        $data['anggotas'] = [];

        $dompdf->getOptions()->setChroot(FCPATH);


        // return view('surat-tugas/print', $data);
        // exit;

        $dompdf->loadHtml(view('surat-tugas/print', $data));
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        // $output = $dompdf->output();
        // file_put_contents("upload/surat_tugas/" . $id . ".pdf", $output);          
        // return $this->response->redirect(site_url('surattugas'));
        if ($save) {
            file_put_contents("upload/surat_tugas/$id.pdf", $dompdf->output());
            file_put_contents("validasi/$id.pdf", $dompdf->output());
        } else {
            $dompdf->stream("$filename.pdf", ['Attachment' => 0]);
            exit;
        }
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        $delete = $this->model->delete($id);
        if ($this->model->db->affectedRows() === 0) {
            return $this->failNotFound(
                sprintf(
                    'post with id %id not found or already deleted',
                    $id
                )
            );
        }

        return $this->respondDeleted(['id' => $id], 'post deleted');
    }
}