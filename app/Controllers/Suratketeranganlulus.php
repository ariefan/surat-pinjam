<?php

namespace App\Controllers;

use App\Models\SuratKeteranganLulusModel;
use App\Models\DetailNomorSuratModel;
use App\Models\NomorSuratModel;
use App\Models\DepartemenModel;
use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader;
use iio\libmergepdf\Merger;
use chillerlan\QRCode\{QRCode, QROptions};

class Suratketeranganlulus extends BaseController
{
    private $pjs;
    private $st;

    function __construct()
    {
        $this->st = new SuratKeteranganLulusModel();

        $pjs = [];
    }

    //Ketika tampilan awal menu surat keterangan lulus
    public function index($count = false)
    {
        // Dismiss Notifikasi
        $db = \Config\Database::connect();
        $db->query("UPDATE notifications SET status = 1 WHERE notification_type = 'surat_keterangan_lulus' AND user_id = '" . session('id') . "'");

        $data = $this->request->getPost();
        $date = $data['date'] ?? '';
        $q = $data['q'] ?? '';
        $status = $data['status'] ?? '';
        $sort_column = $data['sort_column'] ?? 'created_at';
        $sort_order = $data['sort_order'] ?? 'desc';
        // $date = $_GET['date'];

        // dd($q);

        $jenis_user = session('jenis_user');
        $id = session('id');
        $pegawai_id = session('pegawai_id');
        $gol_verifikator = session('gol_verifikator');
        $this->st->select('surat_keterangan_lulus.*, users.nama, date(NOW())')
            ->join('users', 'surat_keterangan_lulus.user_id = users.id')
            ->join('pegawais', 'surat_keterangan_lulus.penandatangan_pegawai_id = pegawais.user_id', 'left')
            ->join('departemen', 'surat_keterangan_lulus.departemen_pegawai_id = departemen.kepala_pegawai_id', 'left')
            ->where("IF(surat_keterangan_lulus.user_id = $id OR (JSON_CONTAINS(shares, CONCAT('[', $id ,']')) AND status = 3) OR ('$jenis_user' IN('admin')) OR ('$jenis_user' IN('verifikator') AND '$gol_verifikator' = departemen.id) OR ('$jenis_user' IN('dekan', 'wadek') AND status >= 2 AND penandatangan_pegawai_id = $pegawai_id) OR ('$jenis_user' IN('departemen') AND status = 3 AND departemen_pegawai_id = $pegawai_id), true, false )")
            ->where("surat_keterangan_lulus.tanggal_pengajuan LIKE '%$date%'")
            ->where("surat_keterangan_lulus.no_surat LIKE '%$q%'")
            ->where("status LIKE '%$status%'")
            // ->groupBy('tanggal')
            ->orderBy($sort_column, $sort_order);
        // $this->st->select('MAX(surat_keterangan_lulus.id) as id, MAX(surat_keterangan_lulus.user_id) as user_id, MAX(surat_keterangan_lulus.nama) as nama, MAX(surat_keterangan_lulus.nim) as nim, MAX(surat_keterangan_lulus.jurusan) as jurusan, MAX(surat_keterangan_lulus.judul_ta) as judul_ta, MAX(surat_keterangan_lulus.tanggal_pengajuan) as tanggal, MAX(surat_keterangan_lulus.penandatangan_pegawai_id) as penandatangan_pegawai_id, MAX(surat_keterangan_lulus.departemen_pegawai_id) as departemen_pegawai_id, MAX(surat_keterangan_lulus.status) as status, MAX(users.nama) as nama_user, MAX(date(NOW())) as date_now')
        //     ->join('users', 'surat_keterangan_lulus.user_id = users.id')
        //     ->join('pegawais', 'surat_keterangan_lulus.penandatangan_pegawai_id = pegawais.user_id', 'left')
        //     ->join('departemen', 'surat_keterangan_lulus.departemen_pegawai_id = departemen.kepala_pegawai_id', 'left')
        //     ->where("IF(surat_keterangan_lulus.user_id = $id OR (JSON_CONTAINS(shares, CONCAT('[', $id ,']')) AND status = 3) OR ('$jenis_user' IN('admin')) OR ('$jenis_user' IN('verifikator') AND '$gol_verifikator' = departemen.id) OR ('$jenis_user' IN('dekan', 'wadek') AND status >= 2 AND penandatangan_pegawai_id = $pegawai_id) OR ('$jenis_user' IN('departemen') AND status = 3 AND departemen_pegawai_id = $pegawai_id), true, false )")
        //     ->where("CONCAT(nama) LIKE '%$q%'")
        //     ->where("status LIKE '%$status%'")
        //     ->where('surat_keterangan_lulus.tanggal_pengajuan ==', $date)
        //     ->orderBy($sort_column, $sort_order);

        if ($count) {
            echo $this->st->countAllResults();
            exit;
        }

        $rows = $this->st->findAll();

        $data = [
            'rows' => $rows,
            // 'pager' => $this->st->pager, 
            'jenis_user' => $jenis_user,
            'user_id' => $id,
            'pegawai_id' => $pegawai_id,
            'date' => $date,
            'q' => $q,
            'status' => $status,
            'sort_column' => $sort_column,
            'sort_order' => $sort_order,
        ];

        // dd($data);
        return view('surat-keterangan-lulus/index', $data);
    }

    //Proses input data surat baru
    public function create()
    {
        $row = new SuratKeteranganLulusModel();
        $row->tanggal_pengajuan = date('Y-m-d');
        $data = [
            'action' => 'store',
            'row' => $row,
            'details' => [],
            'nama_penandatangan' => null,
            'pjs' => $this->pjs,
            'departemens' => (new DepartemenModel)->join('pegawais', 'pegawais.id = departemen.kepala_pegawai_id')->where('departemen.id', '7')->get()->getResult(),
        ];
        return view('surat-keterangan-lulus/formxlsx', $data);
    }

    //Proses menyimpan hasil inputan data baru ke database
    public function store()
    {
        $form_data = $this->request->getPost();
        // $id = substr(md5(date('YmdHis')), 0, 6);
        // $excel = $this->request->getPost();
        $file_excel = $_FILES['file_excel']['name'];
        $extension = pathinfo($file_excel, PATHINFO_EXTENSION);
        if ($extension == 'xls') {
            $reader = new Reader\Xls();
        } else if ($extension == 'xlsx') {
            $reader = new Reader\Xlsx();
        }
        $spreadsheet = $reader->load($_FILES['file_excel']['tmp_name']);
        $sheetdata = $spreadsheet->getActiveSheet()->toArray();
        $sheetcount = count($sheetdata);
        $userid = session()->get('id');
        $status = $form_data['status'];
        // $departemen_pegawai_id = $form_data['departemen_pegawai_id'];
        // $penandatangan_pegawai_id = $form_data['penandatangan_pegawai_id'] ?? "";
        if ($sheetcount > 1) {
            for ($i = 3; $i < $sheetcount; $i++) {
                $id = (new \Hidehalo\Nanoid\Client())->formattedId(getenv('NANOID_ALPHABET'), 16);
                $nama_mhs = $sheetdata[$i][1];
                $tanggal_pengajuan = date('Y-m-d');
                $nim = $sheetdata[$i][2];
                $prodi_pengaju = $sheetdata[$i][3];
                $departemen_pengaju = $sheetdata[$i][4];
                $no_surat = $sheetdata[$i][5];
                $tanggal_yudisium = $sheetdata[$i][6];
                $periode_wisuda = $sheetdata[$i][7];
                $bulan_wisuda = $sheetdata[$i][8];
                $sks = (int) $sheetdata[$i][9];
                $ipk = (float) $sheetdata[$i][10];
                $predikat = $sheetdata[$i][11];
                $gelar = $sheetdata[$i][12];
                $sebutan_gelar = $sheetdata[$i][13];
                $data = array(
                    'id' => $id,
                    'user_id' => $userid,
                    'nama_mhs' => $nama_mhs,
                    'nim' => $nim,
                    'tanggal_pengajuan' => $tanggal_pengajuan,
                    'prodi_pengaju' => $prodi_pengaju,
                    'departemen_pengaju' => $departemen_pengaju,
                    'no_surat' => $no_surat,
                    'tanggal_yudisium' => $tanggal_yudisium,
                    'periode_wisuda' => $periode_wisuda,
                    'bulan_wisuda' => $bulan_wisuda,
                    'sks_pengaju' => $sks,
                    'ipk_pengaju' => $ipk,
                    'predikat_pengaju' => $predikat,
                    'gelar' => $gelar,
                    'sebutan_gelar' => $sebutan_gelar,
                    'departemen_pegawai_id' => 381,
                    'penandatangan_pegawai_id' => 129,
                    'status' => $status
                );
                $this->st->insert($data);
                $this->upload_dasar_penerbitan($id);
                $db = \Config\Database::connect();
                $db->query("INSERT INTO notifications
                    select null, id, 'surat_keterangan_lulus', 'surat', '0', now(), now() from users 
                    where jenis_user IN('verifikator')
                ");
                $data['angkatan'] = '20' . substr($data['nim'] ?? '', 0, 2);
                $this->topdf($id, true);
                session()->setFlashdata('success', 'Data berhasil disimpan');
            }
        } else {
            session()->setFlashdata('error', 'Spreadsheet kosong! Tidak ada yang dimasukan');
        }
        // dd($rows);




        // //if (isset($data['no_surat'])) $data['no_surat'] = $this->create_no_surat(base64_encode($data['no_surat']));



        // dump($data);

        // if ($data['status'] == 'preview') 
        // session()->setFlashdata('preview', $id);
        return $this->response->redirect(site_url('suratketeranganlulus'));
    }

    //Proses edit data surat baru
    public function edit($id)
    {
        $row = (new SuratKeteranganLulusModel)->where('id', $id)->first();
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
        return view('surat-keterangan-lulus/form', $data);
    }

    //Proses menyimpan hasil inputan data editan ke database
    // public function update($id)
    // {
    //     $data = $this->request->getPost();
    //     if (isset($data['status'])) if ($data['status'] == -1) $this->delete($id);
    //     if (!empty($data['tembusan'])) $data['tembusan'] = json_encode($data['tembusan']);
    //     

    //     $this->create_no_surat($id);
    //     if (isset($data['status'])) if ($data['status'] == 3) $this->topdf($id, true);
    //     $this->upload_dasar_penerbitan($id);
    //     session()->setFlashdata('success', 'Data berhasil disimpan');
    //     return $this->response->redirect(site_url('surattugas'));
    // }

    public function update($id)
    {
        $data = $this->request->getPost();
        if (!empty($data['tembusan']))
            $data['tembusan'] = json_encode($data['tembusan']);
        // if (!empty($data['tanggal_pengajuan'])) {
        //     $data['tanggal_pengajuan'] = $data['tanggal_pengajuan'] ?? [];
        //     $data['tanggal_pengajuan'] = json_encode($data['tanggal_pengajuan']);
        // }
        $this->st->update($id, $data);
        $this->create_no_surat($id);

        $row = $this->st
            ->join('users', 'surat_keterangan_lulus.user_id = users.id')
            ->where('surat_keterangan_lulus.id', $id)->first();
        $db = \Config\Database::connect();
        if (isset($data['status'])) {
            switch ($data['status']) {
                case -1:
                    $this->st->update($id, ['status' => $row->status + $data['status']]);
                    // $this->delete($id);
                    break;
                case 2:
                    $db->query("INSERT INTO notifications
                        select null, users.id, 'surat_keterangan_lulus', 'surat', '0', now(), now() from users 
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
                        'penandatangan' => $arr[2],
                        'pengolah' => $arr[3],
                        'kode_perihal' => $kode_perihal,
                        'klasifikasi_surat' => $kode_klasifikasi,
                        'tanggal_surat' => $row->tanggal_pengajuan,
                        'tujuan_surat' => "Surat Keterangan Lulus",
                        'sifat_surat' => "1",
                        'user_id' => $row->user_id
                    ]);
                    $db->query("INSERT INTO notifications
                        select null, users.id, 'surat_keterangan_lulus', 'surat', '0', now(), now() from users 
                        where users.id = " . ($row->user_id ?? "''"));
                    $email = \Config\Services::email();
                    $email->setFrom('no-reply@fmipaugm.id', 'FMIPAUGM');
                    $email->setTo($row->username);
                    $email->setSubject('Verifikasi Surat Baru');
                    $email->setMessage('Surat dengan nomor: <a href="' . base_url("suratketeranganlulus/topdf/$id") . '">' . $row->no_surat . '</a> sudah terverifikasi.');
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
        return $this->response->redirect(site_url('suratketeranganlulus'));
    }

    //Proses update komentar
    public function updatekomentar($id)
    {
        $model = new SuratKeteranganLulusModel();
        $data = [
            'komentar' => $this->request->getVar('komentar'),
        ];
        $model->update($id, $data);
        return $this->response->redirect(site_url('suratketeranganlulus'));
    }

    //Proses delete data surat
    public function delete($id)
    {
        if (!session('logged_in'))
            return redirect()->to(base_url('auth'));
        (new SuratKeteranganLulusModel)->where('id', $id)->delete();
        if (file_exists(FCPATH . "upload/surat_keterangan_lulus/$id.pdf"))
            unlink(FCPATH . "upload/surat_keterangan_lulus/$id.pdf");
        return $this->response->redirect(site_url('suratketeranganlulus'));
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

    public function preview($tanggal_pengajuan)
    {
        if ($data['status'] == 'preview')
            session()->setFlashdata('preview', $id);
        return $this->response->redirect(site_url('suratketeranganlulus'));
    }

    //Proses set status
    public function status($id, $status)
    {
        $model = new SuratKeteranganLulusModel();
        $model->update($id, ['status' => $status]);
        return $this->response->redirect(site_url('suratketeranganlulus'));
    }

    //Proses set status
    public function verification($id, $jenis_user)
    {
        $row = (new SuratKeteranganLulusModel)->where('id', $id)->first();
        $is_kadep = (bool) (new \App\Models\PegawaiModel())->where('id', $row->departemen_pegawai_id)->where('user_id', session('id'))->countAllResults();
        $is_penandatangan = (bool) (new \App\Models\PegawaiModel())->where('id', $row->penandatangan_pegawai_id)->where('user_id', session('id'))->countAllResults();

        $model = new SuratKeteranganLulusModel();
        if ($jenis_user == 'verifikator') {
            $model->update($id, ['verifikasi_verifikator' => 1, 'status' => 2]);
            $db = \Config\Database::connect();
            $db->query("INSERT INTO notifications
                select null, id, 'surat_keterangan_lulus', 'surat', '0', now(), now() from users 
                where jenis_user IN('dekan', 'wadek', 'wadek1', 'wadek2', 'wadek3', 'wadek4')
            ");
            session()->setFlashdata('success', 'Berkas berhasil disetujui');
        } elseif ($is_penandatangan) {
            $model->update($id, ['status' => 3]);
            return $this->response->redirect(site_url("pdf/generate/$id/0/1"));
        }
        return $this->response->redirect(site_url('suratketeranganlulus'));
    }

    //Proses cetak data surat
    public function print()
    {
        $db = \Config\Database::connect();
        $data['surat_keterangan_lulus'] = $db->query("SELECT
        nama, nomor, pangkat
        FROM users
        JOIN surat_keterangan_lulus ON user_id=users.id")->getResult();
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
        return view('surat-keterangan-lulus/upload', ['id' => $id]);
    }

    public function uploads($id)
    {
        if (!empty($this->request->getFile('berkas')->getFilename())) {
            if (file_exists("upload/pertanggungjawaban_surat_keterangan_lulus/$id.pdf"))
                unlink("upload/pertanggungjawaban_surat_keterangan_lulus/$id.pdf");
            $file = $this->request->getFile('berkas');
            $file->move('upload/pertanggungjawaban_surat_keterangan_lulus', $id . '.pdf');
        }
        session()->setFlashdata('success', 'Berkas berhasil diupload');
        return $this->response->redirect(site_url('suratketeranganlulus'));
    }

    // public function uploads($id)
    // {
    //     $db = \Config\Database::connect();
    //     $result = $db->query("SELECT file_pertanggungjawaban, date(NOW()) BETWEEN tanggal_kegiatan_mulai AND tanggal_kegiatan_selesai as dalam_periode FROM surat_tugas WHERE id = '$id'")->getResult();

    //     $old_name = $result[0]->file_pertanggungjawaban;
    //     if (file_exists("upload/pertanggungjawaban_surat_tugas/$old_name.pdf")) unlink("upload/pertanggungjawaban_surat_tugas/$old_name.pdf");

    //     $file = $this->request->getFile('berkas');
    //     $name = hash_file('ripemd160', $file);
    //     $file->move('upload/pertanggungjawaban_surat_tugas',  $name . '.pdf');

    //     (new SuratTugasModel())->update($id, ['file_pertanggungjawaban' => $name . '.pdf']);
    //     session()->setFlashdata('success', 'Berkas berhasil diupload');
    //     return $this->response->redirect(site_url('surattugas'));
    // }

    //Proses bagikan surat tugas
    public function share($id)
    {
        $db = \Config\Database::connect();
        $users = $db->query("SELECT id, nama as name FROM users ORDER BY nama")->getResult();
        for ($i = 0; $i < count($users); $i++)
            $users[$i]->id = (int) $users[$i]->id;
        $row = (new SuratKeteranganLulusModel)->where('id', $id)->first();
        $data = [
            'row' => $row,
            'users' => json_encode($users),
        ];
        return view('surat-keterangan-lulus/share', $data);
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
                        select null, users.id, 'surat_keterangan_lulus', 'surat', '0', now(), now() from users 
                        where users.id = " . ($share ?? "''"));
        }
        $model = new SuratKeteranganLulusModel();
        $model->update($id, ['shares' => json_encode($shares)]);
        session()->setFlashdata('success', 'Alhamdulillah.. Surat berhasil dibagikan!');
        return $this->response->redirect(site_url('suratketeranganlulus'));
    }

    //Proses upload dasar penerbitan
    public function upload_dasar_penerbitan($id)
    {
        if (!empty($this->request->getFile('berkas')) && !empty($this->request->getFile('berkas')->getFileName())) {
            if (file_exists("upload/dasar_penerbitan_surat_keterangan_lulus/$id.pdf"))
                unlink("upload/dasar_penerbitan_surat_keterangan_lulus/$id.pdf");
            $file = $this->request->getFile('berkas');
            $file->move('upload/dasar_penerbitan_surat_keterangan_lulus', $id . '.pdf');
        }
    }

    public function topdf($id, $save = false)
    {
        $filename = date('y-m-d_H.i.s') . '-surat_keterangan_lulus';

        // instantiate and use the dompdf class
        $dompdf = new Dompdf();
        $d = "Untuk cek validasi surat, silakah buka alamat berikut:\nhttps://mipa.ugm.ac.id/validasi-surat/\n\nKode surat: " . $id;
        $qr = (new QRCode)->render($d);
        $qr_note = "<br><i><b>Dokumen ini telah ditandatangani secara elektronik. Verifikasi keabsahan dokumen dapat dilakukan dengan scan QR code berikut.</b></i>";

        // load HTML content
        $db = \Config\Database::connect();

        $data['row'] = $db->query("SELECT
        no_surat, nama_mhs, nim, prodi_pengaju, gelar, sebutan_gelar, departemen_pengaju, tanggal_pengajuan, bulan_wisuda,tanggal_yudisium, ipk_pengaju, sks_pengaju, predikat_pengaju, periode_wisuda, status
        FROM users
        JOIN pegawais ON users.id = pegawais.user_id
        JOIN surat_keterangan_lulus ON surat_keterangan_lulus.user_id=pegawais.user_id
        WHERE surat_keterangan_lulus.id = '$id'")->getResult()[0];

        $data['penandatangan'] = $db->query("SELECT 
        pegawais.id id, nama_publikasi nama, pegawais.nip nip, prodi, pegawais.departemen, pegawais.pangkat pangkat, golongan, jabatan, penandatangan.nama_penandatangan label
        FROM surat_keterangan_lulus
        JOIN pegawais ON surat_keterangan_lulus.penandatangan_pegawai_id = pegawais.id
        JOIN penandatangan ON pegawais.id = penandatangan.pegawai_id
        WHERE surat_keterangan_lulus.id = '$id'")->getResult();


        if (count($data['penandatangan']) > 0) {
            $data['penandatangan'] = $data['penandatangan'][0];
        } else {
            $data['penandatangan'] = [];
        }

        $data['qr'] = $data['row']->status == 3 ? $qr : '';
        $data['qr_note'] = $data['row']->status == 3 ? $qr_note : '';

        $dompdf->getOptions()->setChroot(FCPATH);


        // return view('surat-tugas/print', $data);
        // exit;

        $dompdf->loadHtml(view('surat-keterangan-lulus/print', $data));
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        if ($save) {
            file_put_contents("upload/surat_keterangan_lulus/$id.pdf", $dompdf->output());
            file_put_contents("validasi/$id.pdf", $dompdf->output());
        } else {
            $dompdf->stream("$filename.pdf", ['Attachment' => 0]);
        }
        return $dompdf->output();
        // $output = $dompdf->output();
        // file_put_contents("upload/surat_tugas/" . $id . ".pdf", $output);          
        // return $this->response->redirect(site_url('surattugas'));
    }

    public function deletemultiple()
    {
        $db = \Config\Database::connect();
        $data = $this->request->getPost();


        if (isset($data['checkbox_value'])) {
            $checkbox_values = $data['checkbox_value'];

            if (!empty($checkbox_values)) {
                foreach ($checkbox_values as $checkbox_value) {
                    $row = $db->query("SELECT
                no_surat, nama_mhs, nim, prodi_pengaju, departemen_pengaju, tanggal_pengajuan, bulan_wisuda,tanggal_yudisium, ipk_pengaju, sks_pengaju, predikat_pengaju, periode_wisuda, status
                FROM users
                JOIN pegawais ON users.id = pegawais.user_id
                JOIN surat_keterangan_lulus ON surat_keterangan_lulus.user_id=pegawais.user_id
                WHERE surat_keterangan_lulus.id = '$checkbox_value'")->getResult()[0];

                    if ($row->status <= 1) {
                        $this->st->delete($checkbox_value);
                        session()->setFlashdata('success', 'Berhasil menghapus data');
                    } else {
                        session()->setFlashdata('error', 'Mohon maaf, ada data yang sudah disetujui');
                    }
                }
                return $this->response->redirect(site_url('suratketeranganlulus/index?date=' . date("Y-m-d")));
            } else {
                session()->setFlashdata('status', 'At least select 1 row');
            }
        } else {
            session()->setFlashdata('status', 'No checkboxes selected');
        }

        return $this->response->redirect(site_url('suratketeranganlulus/index?date=' . date("Y-m-d")));
    }


    public function approvedeclinemultiple()
    {
        $db = \Config\Database::connect();
        $data = $this->request->getPost();
        $checkbox_values = $data['checkbox_value'] ?? '';
        if (empty($checkbox_values)) {
            session()->setFlashdata('status', 'At least select 1 row');
            return $this->response->redirect(site_url('suratketeranganlulus/index?date=' . date("Y-m-d")));
        }

        if (!empty($data['approveOrDecline'])) {
            $checkbox_values = $data['checkbox_value'];

            $status = $data['approveOrDecline'];
            if (!empty($checkbox_values)) {


                try {
                    foreach ($checkbox_values as $checkbox_value) {
                        $row = $db->query("SELECT
                        no_surat, nama_mhs, nim, prodi_pengaju, departemen_pengaju, tanggal_pengajuan, bulan_wisuda,tanggal_yudisium, ipk_pengaju, sks_pengaju, predikat_pengaju, periode_wisuda, status
                        FROM users
                        JOIN pegawais ON users.id = pegawais.user_id
                        JOIN surat_keterangan_lulus ON surat_keterangan_lulus.user_id=pegawais.user_id
                        WHERE surat_keterangan_lulus.id = '$checkbox_value'")->getResult()[0];

                        if ($row->status > 0) {
                            if ($row->status == 3) {
                                continue;
                            }
                            $this->st->update($checkbox_value, ['status' => $status]);
                        }
                    }

                    session()->setFlashdata('success', 'Berhasil approve data');
                    return $this->response->redirect(site_url('suratketeranganlulus/index?date=' . date("Y-m-d")));
                } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
                    // Catch any exceptions that may occur during the query execution

                    // Print the error message
                    echo "Error executing the query: " . $e->getMessage();
                }
            } else {
                session()->setFlashdata('status', 'Pilih paling sedikit 1 data');
            }
        } else {
            session()->setFlashdata('status', 'No checkboxes selected');
        }

        return $this->response->redirect(site_url('suratketeranganlulus/index?date=' . date("Y-m-d")));
    }

    public function topdfbatch()
    {
        $filename = date('y-m-d_H.i.s') . '-surat_keterangan_lulus';
        $db = \Config\Database::connect();
        $data = $this->request->getPost();
        $checkbox_values = $data['checkbox_value'] ?? '';
        if (empty($checkbox_values)) {
            session()->setFlashdata('status', 'At least select 1 row');
            return $this->response->redirect(site_url('suratketeranganlulus/index?date=' . date("Y-m-d")));
        }
        $merger = new Merger;

        $filename = date('y-m-d_H.i.s') . '-surat_keterangan_lulus';



        foreach ($checkbox_values as $checkbox_value) {
            // instantiate and use the dompdf class
            // $options = new \Dompdf\Options();
            // $options->setIsRemoteEnabled(true);
            // $dompdf = new Dompdf($options);
            $dompdf = new Dompdf();
            $d = "Untuk cek validasi surat, silakah buka alamat berikut:\nhttps://mipa.ugm.ac.id/validasi-surat/\n\nKode surat: " . $checkbox_value;
            $qr = (new QRCode)->render($d);
            $qr_note = "<br><i><b>Dokumen ini telah ditandatangani secara elektronik. Verifikasi keabsahan dokumen dapat dilakukan dengan scan QR code berikut.</b></i>";

            $data['penandatangan'] = $db->query("SELECT 
                pegawais.id id, nama_publikasi nama, pegawais.nip nip, prodi, pegawais.departemen, pegawais.pangkat pangkat, golongan, jabatan, penandatangan.nama_penandatangan label
                FROM surat_keterangan_lulus
                JOIN pegawais ON surat_keterangan_lulus.penandatangan_pegawai_id = pegawais.id
                JOIN penandatangan ON pegawais.id = penandatangan.pegawai_id
                WHERE surat_keterangan_lulus.id = '$checkbox_value'")->getResult();

            if (count($data['penandatangan']) > 0) {
                $data['penandatangan'] = $data['penandatangan'][0];
            } else {
                $data['penandatangan'] = [];
            }


            $data['row'] = $db->query("SELECT
                no_surat, nama_mhs, nim, prodi_pengaju, departemen_pengaju, tanggal_pengajuan, bulan_wisuda,tanggal_yudisium, ipk_pengaju, sks_pengaju, predikat_pengaju, periode_wisuda, status
                FROM users
                JOIN pegawais ON users.id = pegawais.user_id
                JOIN surat_keterangan_lulus ON surat_keterangan_lulus.user_id=pegawais.user_id
                WHERE surat_keterangan_lulus.id = '$checkbox_value'")->getResult()[0];


            $data['qr'] = $data['row']->status == 3 ? $qr : '';
            $data['qr_note'] = $data['row']->status == 3 ? $qr_note : '';
            // load HTML content
            $db = \Config\Database::connect();
            if ($data['preview'] == 'id') {
                $dompdf->loadHtml(view('surat-keterangan-lulus/print', $data));
            } else if ($data['preview'] == 'en') {
                $dompdf->loadHtml(view('surat-keterangan-lulus/printen', $data));
            }

            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $merger->addRaw($dompdf->output());
            unset($dompdf);
        }


        $createdPdf = $merger->merge();
        $this->response->setHeader('Content-Type', 'application/pdf')->appendHeader('Content-Disposition', 'inline; filename=$filename')->appendHeader('Content-Transfer-Encoding', 'binary')->appendHeader('Accept-Ranges', 'bytes')->setBody($createdPdf)->send();


        // initialize merger
        // $merger = new Merger;

        // // append selected files
        // // $filenames = array();
        // // foreach ($id as $filename) {
        // //     array_push($filenames, "../../public/upload/surat_keterangan_lulus/$filename" . ".pdf");
        // // }

        // // if ($save) {
        // //     file_put_contents("upload/surat_keterangan_lulus/$id.pdf",);
        // // } else {
        // //     $dompdf->stream("$filename.pdf", ['Attachment' => 0]);
        // // }
        // // return $dompdf->output();

        // // merge selected pdfs
        // // $merger->addIterator($filenames);

        // $filename = date('y-m-d_H.i.s') . '-surat_keterangan_lulus';

        // // instantiate and use the dompdf class
        // $dompdf = new Dompdf();
        // $d = "Untuk cek validasi surat, silakah buka alamat berikut:\nhttps://mipa.ugm.ac.id/validasi-surat/\n\nKode surat: " . $id;
        // $qr = (new QRCode)->render($d);
        // $qr_note = "<br><i><b>Dokumen ini telah ditandatangani secara elektronik. Verifikasi keabsahan dokumen dapat dilakukan dengan scan QR code berikut.</b></i>";

        // // load HTML content
        // $db = \Config\Database::connect();

        // $data['row'] = $db->query("SELECT
        // no_surat, nama_mhs, nim, prodi_pengaju, departemen_pengaju, tanggal_pengajuan, bulan_wisuda,tanggal_yudisium, ipk_pengaju, sks_pengaju, predikat_pengaju, periode_wisuda, status
        // FROM users
        // JOIN pegawais ON users.id = pegawais.user_id
        // JOIN surat_keterangan_lulus ON surat_keterangan_lulus.user_id=pegawais.user_id
        // WHERE surat_keterangan_lulus.id = '$no_surat'")->getResult();

        // dd($data['row']);

        // $data['penandatangan'] = $db->query("SELECT 
        // pegawais.id id, nama_publikasi nama, pegawais.nip nip, prodi, pegawais.departemen, pegawais.pangkat pangkat, golongan, jabatan, penandatangan.nama_penandatangan label
        // FROM surat_keterangan_lulus
        // JOIN pegawais ON surat_keterangan_lulus.penandatangan_pegawai_id = pegawais.id
        // JOIN penandatangan ON pegawais.id = penandatangan.pegawai_id
        // WHERE surat_keterangan_lulus.no_surat = '$id'")->getResult();


        // if (count($data['penandatangan']) > 0) {
        //     $data['penandatangan'] = $data['penandatangan'][0];
        // } else {
        //     $data['penandatangan'] = [];
        // }

        // $data['qr'] = $data['row']->status  == 3 ? $qr : '';
        // $data['qr_note'] = $data['row']->status == 3 ? $qr_note : '';

        // $dompdf->getOptions()->setChroot(FCPATH);


        // // return view('surat-tugas/print', $data);
        // // exit;

        // $dompdf->loadHtml(view('surat-keterangan-lulus/print', $data));
        // $dompdf->setPaper('A4', 'portrait');
        // $dompdf->render();
        // if ($save) {
        //     file_put_contents("upload/surat_keterangan_lulus/$id.pdf", $dompdf->output());
        //     file_put_contents("validasi/$id.pdf", $dompdf->output());
        // } else {
        //     $dompdf->stream("$filename.pdf", ['Attachment' => 0]);
        // }
        // return $dompdf->output();
        // // $output = $dompdf->output();
        // // file_put_contents("upload/surat_tugas/" . $id . ".pdf", $output);          
        // // return $this->response->redirect(site_url('surattugas'));

        // $createdPdf = $merger->merge();
    }
}