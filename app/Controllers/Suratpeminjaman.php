<?php

namespace App\Controllers;

use App\Models\SuratPeminjamanModel;

class Suratpeminjaman extends BaseController
{
    public $surat_peminjaman;

    function __construct()
    {
        $this->surat_peminjaman = new SuratPeminjamanModel();
    }

    public function index()
    {
        $rows = $this->surat_peminjaman
            ->like('nama_surat', '%' . $this->request->getGet('q') . '%', 'both')
            ->paginate(10);
        $data = [
            'rows'  => $rows,
            'pager' => $this->surat_peminjaman->pager,
        ];
        return view('surat-peminjaman/index', $data);
    }

    public function data()
    {
        $data = $this->surat_peminjaman
            ->where('aktif', 1)
            ->like('surat_peminjaman', $this->request->getGet('q') ?? '')
            ->findAll(10);
        return $this->response->setJSON($data);
    }

    public function create()
    {
        $row                  = new SuratPeminjamanModel();
        $row->tanggal_berlaku = date('Y-m-d');
        $row->aktif           = true;
        $data                 = [
            'action' => 'store',
            'row'    => $row,
        ];
        return view('surat-peminjaman/form', $data);
    }

    public function store()
    {
        $data = $this->request->getPost();
        $this->surat_peminjaman->insert($data);
        session()->setFlashdata('success', 'Data berhasil disimpan');
        return $this->response->redirect(site_url('surat_peminjaman'));
    }

    public function edit($id)
    {
        $row  = $this->surat_peminjaman->where('id', $id)->first();
        $data = [
            'action' => 'update',
            'row'    => $row,
        ];
        return view('surat-peminjaman/form', $data);
    }

    public function update($id)
    {
        $data = $this->request->getPost();
        if (empty($data['aktif']))
            $data['aktif'] = 0;
        $this->surat_peminjaman->update($id, $data);
        session()->setFlashdata('success', 'Data berhasil disimpan');
        return $this->response->redirect(site_url('surat_peminjaman'));
    }

    public function delete($id)
    {
        if (!session('logged_in'))
            return redirect()->to(base_url('auth'));
        $this->surat_peminjaman->where('id', $id)->delete();
        session()->setFlashdata('success', 'Data berhasil terhapus');
        return $this->response->redirect(site_url('surat_peminjaman'));
    }
}