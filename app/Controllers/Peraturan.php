<?php

namespace App\Controllers;

use App\Models\PeraturanModel;
use Hashids\Hashids;

class Peraturan extends BaseController
{
    public $peraturan;

    function __construct()
    {
        $this->peraturan = new PeraturanModel();
    }

    public function index()
    {
        $rows = $this->peraturan
            ->like('peraturan', '%' . $this->request->getGet('q') . '%', 'both')
            ->paginate(10);
        $data = [
            'rows' => $rows,
            'pager' => $this->peraturan->pager,
        ];
        return view('peraturan/index', $data);
    }

    public function data()
    {
        $data = $this->peraturan
            ->where('aktif', 1)
            ->like('peraturan', $this->request->getGet('q') ?? '')
            ->findAll(10);
        return $this->response->setJSON($data);
    }

    public function create()
    {
        $row = new PeraturanModel();
        $row->tanggal_berlaku = date('Y-m-d');
        $row->aktif = true;
        $data = [
            'action' => 'store',
            'row' => $row,
        ];
        return view('peraturan/form', $data);
    }

    public function store()
    {
        $data = $this->request->getPost();
        $this->peraturan->insert($data);
        session()->setFlashdata('success', 'Data berhasil disimpan');
        return $this->response->redirect(site_url('peraturan'));
    }

    public function edit($id)
    {
        $row = $this->peraturan->where('id', $id)->first();
        $data = [
            'action' => 'update',
            'row' => $row,
        ];
        return view('peraturan/form', $data);
    }

    public function update($id)
    {
        $data = $this->request->getPost();
        if (empty($data['aktif'])) $data['aktif'] = 0;
        $this->peraturan->update($id, $data);
        session()->setFlashdata('success', 'Data berhasil disimpan');
        return $this->response->redirect(site_url('peraturan'));
    }

    public function delete($id)
    {
        if (!session('logged_in')) return redirect()->to(base_url('auth'));
        $this->peraturan->where('id', $id)->delete();
        session()->setFlashdata('success', 'Data berhasil terhapus');
        return $this->response->redirect(site_url('peraturan'));
    }
}
