<?php

namespace App\Controllers;

use App\Models\RuangModel;

class Ruang extends BaseController
{
    public $ruang;

    function __construct()
    {
        $this->ruang = new RuangModel();
    }

    public function index()
    {
        $rows = $this->ruang
            ->like('nama_ruang', '%' . $this->request->getGet('q') . '%', 'both')
            ->paginate(10);
        $data = [
            'rows'  => $rows,
            'pager' => $this->ruang->pager,
        ];
        return view('ruang/index', $data);
    }

    public function create()
    {
        $row  = new RuangModel();
        $data = [
            'action'  => 'store',
            'row'     => $row,
            'gedungs' => (new \App\Models\GedungModel())->findAll(),
        ];
        return view('ruang/form', $data);
    }

    public function store()
    {
        $data = $this->request->getPost();
        $this->ruang->insert($data);
        session()->setFlashdata('success', 'Data berhasil disimpan');
        return $this->response->redirect(site_url('ruang'));
    }

    public function edit($id)
    {
        $row  = $this->ruang->where('id', $id)->first();
        $data = [
            'action'  => 'update',
            'row'     => $row,
            'gedungs' => (new \App\Models\GedungModel())->findAll(),
        ];
        return view('ruang/form', $data);
    }

    public function update($id)
    {
        $data = $this->request->getPost();
        $this->ruang->update($id, $data);
        session()->setFlashdata('success', 'Data berhasil disimpan');
        return $this->response->redirect(site_url('ruang'));
    }

    public function delete($id)
    {
        if (!session('logged_in'))
            return redirect()->to(base_url('auth'));
        $this->ruang->where('id', $id)->delete();
        session()->setFlashdata('success', 'Data berhasil terhapus');
        return $this->response->redirect(site_url('ruang'));
    }
}