<?php

namespace App\Controllers;

use App\Models\ListPICModel;
use App\Models\UserModel;
use App\Models\MahasiswaAtasanBawahanModel;

class Pic extends BaseController
{
    //Ketika tampilan awal menu surat

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        // Call the parent initController method
        parent::initController($request, $response, $logger);
        // Check the value of session('gol_pic_mou')
        if (session('gol_pic_mou') != 2) {
            // Redirect to the root URL
            $response->redirect(base_url('home'));
        }
    }

    public function index()
    {
        $db = db_connect();
        $q = $db->escapeLikeString(htmlspecialchars($this->request->getVar('q') ?? ''));
        $sort_column = $db->escapeString(htmlspecialchars($this->request->getVar('sort_column') ?? ''));
        $sort_order = $db->escapeString(htmlspecialchars($this->request->getVar('sort_order') ?? ''));
        $sort_column = empty($sort_column) ? 'id' : $sort_column;
        $sort_order = empty($sort_order) ? 'desc' : $sort_order;
        $data['sort_column'] = $sort_column;
        $data['sort_order'] = $sort_order;

        $db = \Config\Database::connect();
        $data['rows'] = $db->query("
            SELECT users.id, users.username as email, users.nama as jabatan, id_list_pic, list_pic_ugm.nama_ugm, departemen_ugm, alamat_ugm, no_telp_ugm
            FROM users JOIN list_pic_ugm ON list_pic_ugm.id_user_pic = users.id
            WHERE 
                (username LIKE '%$q%' OR
                nama_ugm LIKE '%$q%' OR
                nama LIKE '%$q%') AND
                tipe_pic = 0
            ORDER BY $sort_column $sort_order
        ")->getResult();

        $data['q'] = $q;
        // dump($data);
        return view('user-pic/index', $data);
    }

    public function piclain()
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
            SELECT users.id, users.username as email, users.nama as jabatan, id_list_pic, list_pic_ugm.nama_ugm, departemen_ugm, alamat_ugm, no_telp_ugm,
            tipe_pic
            FROM users JOIN list_pic_ugm ON list_pic_ugm.id_user_pic = users.id
            WHERE 
                (username LIKE '%$q%' OR
                nama_ugm LIKE '%$q%' OR
                nama LIKE '%$q%' OR
                departemen_ugm LIKE '%$q%') AND
                tipe_pic > 0
            ORDER BY $sort_column $sort_order
        ")->getResult();

        $data['q'] = $q;
        // dump($data);
        return view('user-pic/piclain', $data);
    }

    //Ketika input data surat baru
    public function create()
    {
        $row = new ListPICModel();

        $data = [
            'action' => 'store',
            'row' => $row,
            'details' => [],
        ];
        return view('user-pic/formlain', $data);
    }

    public function usersearch()
    {
        $model = new UserModel();
        if (empty($this->request->getGet('users'))) {
            $dosens = $model->select('users.id as id_user_pic, users.username as email, users.nama as nama')
                ->like('nama', $this->request->getGet('term'))
                ->orLike('username', $this->request->getGet('term'))->findAll(3);
            $data = $dosens;
            return $this->response->setJSON($data);
        } else {
            $dosens = $model->like('nama', $this->request->getGet('term'))->where('jenis_user', $this->request->getGet('users'))->findAll();
            ;

            $data = [];
            foreach ($dosens as $dosen) {
                $data[] = [
                    'id' => $dosen->id,
                    'value' => $dosen->nama,
                    'label' => $dosen->nama
                ];
            }
            return $this->response->setJSON($data);
        }
    }

    //Proses menyimpan hasil inputan data baru ke database
    public function store()
    {
        $data = $this->request->getPost();
        // dump($data);
        $model = new ListPICModel();
        $model->insert($data);
        $userModel = new UserModel();
        $userModel->where('id', $data['id_user_pic'])->set(['gol_pic_mou' => '1'])->update();
        session()->setFlashdata('success', 'Data berhasil disimpan');

        return $this->response->redirect(site_url('pic/piclain'));
    }

    //Ketika edit data surat baru
    public function edit($id)
    {
        $row = (new ListPICModel)->where('id_list_pic', $id)->first();
        $db = \Config\Database::connect();
        $row = $db->query("
        SELECT users.id, users.username as email, users.nama as jabatan, id_list_pic, list_pic_ugm.nama_ugm, departemen_ugm, alamat_ugm, no_telp_ugm
        FROM users JOIN list_pic_ugm ON list_pic_ugm.id_user_pic = users.id
            WHERE 
                id_list_pic = $id
        ")->getResult()[0];
        $data = [
            'action' => 'update',
            'row' => $row,
        ];

        return view('user-pic/form', $data);
    }

    //Proses menyimpan hasil inputan data editan ke database
    public function update($id)
    {
        // $m = (new ListPICModel())->where('id_list_pic', $id)->first();
        // dump($m);
        $model = new ListPICModel();
        $data = $this->request->getPost();
        $model->update($id, $data);
        session()->setFlashdata('success', 'Data berhasil disimpan');
        return session('jenis_user') == 'admin' ? $this->response->redirect(site_url('pic')) : $this->response->redirect(site_url('home'));
    }
    public function delete($id)
    {
        if (!session('logged_in'))
            return redirect()->to(base_url('auth'));
        $id_user_pic = (new ListPICModel)->select('id_user_pic')->where('id_list_pic', $id)->first();
        $id_user = $id_user_pic->id_user_pic;
        $userModel = new UserModel();
        $userModel->where('id', $id_user)->set(['gol_pic_mou' => '0'])->update();
        (new ListPICModel)->where('id_list_pic', $id)->delete();
        session()->setFlashdata('success', 'Data berhasil dihapus');
        return $this->response->redirect(site_url('pic/piclain'));
    }
}