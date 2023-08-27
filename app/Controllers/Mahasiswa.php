<?php

namespace App\Controllers;

use App\Models\MahasiswaModel;
use App\Models\UserModel;
use App\Models\MahasiswaAtasanBawahanModel;

class Mahasiswa extends BaseController
{
    //Ketika tampilan awal menu surat
    public function index()
    {
        $q = $this->request->getVar('q');
        $sort_column = $this->request->getVar('sort_column');
        $sort_order = $this->request->getVar('sort_order');
        $sort_column = empty($sort_column) ? 'id' : $sort_column;
        $sort_order = empty($sort_order) ? 'desc' : $sort_order;
        $data['sort_column'] = $sort_column;
        $data['sort_order'] = $sort_order;

        $db = \Config\Database::connect();
        $data['rows'] = $db->query("
            SELECT users.id, users.username, mahasiswa.nama, jenis_user, aktif 
            FROM users JOIN mahasiswa ON mahasiswa.user_id = users.id
            WHERE 
                username LIKE '%$q%' OR
                mahasiswa. nama LIKE '%$q%'
            ORDER BY $sort_column $sort_order
        ")->getResult();

        $data['q'] = $q;
        return view('user-mahasiswa/index', $data);
    }

    //Ketika input data surat baru
    public function create()
    {
        $row = new MahasiswaModel();
        $data = [
            'action' => 'store',
            'row' => $row,
            'details' => [],
            'users' => json_encode((new MahasiswaModel())->select('id, nama name')->get()->getResult()),
        ];
        return view('user/form', $data);
    }

    //Proses menyimpan hasil inputan data baru ke database
    public function store()
    {
        $data = $this->request->getPost();
        $model = new MahasiswaModel();
        $data['password'] = password_hash('password', PASSWORD_BCRYPT);
        $model->insert($data);

        return $this->response->redirect(site_url('user'));
    }

    //Ketika edit data surat baru
    public function edit($id)
    {
        $row = (new MahasiswaModel)->where('user_id', $id)->first();
        $db = \Config\Database::connect();
        $row = $db->query("
            SELECT users.id, users.username, mahasiswa.nama, jenis_user, aktif 
            FROM users JOIN mahasiswa ON mahasiswa.user_id = users.id
            WHERE 
                user_id = $id
        ")->getResult()[0];
        $data = [
            'action' => 'update',
            'row' => $row,
        ];

        return view('user-mahasiswa/form', $data);
    }

    //Proses menyimpan hasil inputan data editan ke database
    public function update($id)
    {
        $m = (new MahasiswaModel())->where('user_id', $id)->first();
        $model = new MahasiswaModel();
        $data = $this->request->getPost();
        $pwd = password_hash($this->request->getVar('password') ?? "", PASSWORD_BCRYPT);
        if (!empty($this->request->getVar('password'))) $data['password'] = $pwd;
        $model->update($m->id, $data);
        session()->setFlashdata('success', 'Data berhasil disimpan');
        return session('jenis_user') == 'admin' ? $this->response->redirect(site_url('mahasiswa')) : $this->response->redirect(site_url('home'));
    }

    //Proses delete data surat
    public function delete($id)
    {
        if (!session('logged_in')) return redirect()->to(base_url('auth'));
        (new MahasiswaModel)->where('user_id', $id)->delete();
        (new UserModel)->where('id', $id)->delete();
        session()->setFlashdata('success', 'Data berhasil dihapus');
        return $this->response->redirect(site_url('mahasiswa'));
    }
}
