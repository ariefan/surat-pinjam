<?php

namespace App\Controllers;

use App\Models\GedungModel;

class Gedung extends BaseController
{
    public $gedung;

    function __construct()
    {
        $this->gedung = new GedungModel();
    }

    public function index()
    {
        $rows = $this->gedung
            ->like('nama_gedung', '%' . $this->request->getGet('q') . '%', 'both')
            ->paginate(10);
        $data = [
            'rows'  => $rows,
            'pager' => $this->gedung->pager,
        ];
        return view('gedung/index', $data);
    }

    public function create()
    {
        $row  = new GedungModel();
        $data = [
            'action' => 'store',
            'row'    => $row,
        ];
        return view('gedung/form', $data);
    }

    public function store()
    {
        $data = $this->request->getPost();
        $this->gedung->insert($data);
        session()->setFlashdata('success', 'Data berhasil disimpan');
        return $this->response->redirect(site_url('gedung'));
    }

    public function edit($id)
    {
        $row  = $this->gedung->where('id', $id)->first();
        $data = [
            'action' => 'update',
            'row'    => $row,
        ];
        return view('gedung/form', $data);
    }

    public function update($id)
    {
        $data = $this->request->getPost();
        $this->gedung->update($id, $data);
        session()->setFlashdata('success', 'Data berhasil disimpan');
        return $this->response->redirect(site_url('gedung'));
    }

    public function delete($id)
    {
        if (!session('logged_in'))
            return redirect()->to(base_url('auth'));
        $this->gedung->where('id', $id)->delete();
        session()->setFlashdata('success', 'Data berhasil terhapus');
        return $this->response->redirect(site_url('gedung'));
    }
}