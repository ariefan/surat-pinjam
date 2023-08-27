<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\UserAtasanBawahanModel;

class User extends BaseController
{
    //Ketika tampilan awal menu surat
    public function index()
    {
        $db = db_connect();
        $q = $db->escapeLikeString(htmlspecialchars($this->request->getVar('q') ?? ''));
        $sort_column = $db->escapeString(htmlspecialchars($this->request->getVar('sort_column') ?? ''));
        $sort_order = $db->escapeString(htmlspecialchars($this->request->getVar('sort_order') ?? ''));
        $sort_column = empty($sort_column) ? 'username' : $sort_column;
        $sort_order = empty($sort_order) ? 'asc' : $sort_order;
        $data['sort_column'] = $sort_column;
        $data['sort_order'] = $sort_order;

        $db = \Config\Database::connect();
        $data['rows'] = $db->query("
            SELECT * FROM users 
            WHERE 
                username LIKE '%$q%' OR
                nama LIKE '%$q%'
            ORDER BY $sort_column $sort_order
        ")->getResult();

        $data['q'] = $q;
        return view('user/index', $data);
    }

    //Ketika input data surat baru
    public function create()
    {
        $row = new UserModel();
        $data = [
            'action' => 'store',
            'row' => $row,
            'details' => [],
            'users' => json_encode((new UserModel())->select('id, nama name')->get()->getResult()),
        ];
        return view('user/form', $data);
    }

    //Proses menyimpan hasil inputan data baru ke database
    public function store()
    {
        $data = $this->request->getPost();
        $model = new UserModel();
        $data['password'] = password_hash('password', PASSWORD_BCRYPT);
        $model->insert($data);

        (new UserAtasanBawahanModel())->where('user_id_atasan', $model->getInsertID())->delete();
        foreach ($data['bawahan'] as $user_id_bawahan) {
            (new UserAtasanBawahanModel())->insert(['user_id_atasan' => $model->getInsertID(), 'user_id_bawahan' => $user_id_bawahan]);
        }

        return $this->response->redirect(site_url('user'));
    }

    //Ketika edit data surat baru
    public function edit($id)
    {
        $row = (new UserModel)->where('id', $id)->first();
        $data = [
            'action' => 'update',
            'row' => $row,
            'users' => json_encode((new UserModel())->select('id, nama name')->get()->getResult()),
        ];

        $data['bawahans'] = [];
        $bawahans = (new UserAtasanBawahanModel())
            ->select('user_id_bawahan id')
            ->join('users', 'users.id = user_id_atasan')
            ->where('user_id_atasan', $id)->get()->getResult();
        foreach ($bawahans as $bawahan)
            $data['bawahans'][] = (int) $bawahan->id;
        $data['bawahans'] = json_encode($data['bawahans'], JSON_NUMERIC_CHECK);

        return view('user/form', $data);
    }

    //Proses menyimpan hasil inputan data editan ke database
    public function update($id)
    {
        $model = new UserModel();
        $data = $this->request->getPost();
        $pwd = password_hash($this->request->getVar('password'), PASSWORD_BCRYPT);
        if (!empty($this->request->getVar('password')))
            $data['password'] = $pwd;
        $model->update($id, $data);
        session()->setFlashdata('success', 'Data berhasil disimpan');
        return session('jenis_user') == 'admin' ? $this->response->redirect(site_url('user')) : $this->response->redirect(site_url('home'));
    }

    //Proses delete data surat
    public function delete($id)
    {
        if (!session('logged_in'))
            return redirect()->to(base_url('auth'));
        (new UserModel)->where('id', $id)->delete();
        return $this->response->redirect(site_url('user'));
    }
}