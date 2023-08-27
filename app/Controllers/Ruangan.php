<?php

namespace App\Controllers;

use App\Models\RuanganModel;

class Ruangan extends BaseController
{
  protected $ruanganModel;
  public function __construct()
  {
    $this->ruanganModel = new RuanganModel();
  }

  public function index()
  {
    $data = [
      'title' => 'Tambah Ruangan'
    ];

    return view('/ruangan/index', $data);
  }

  public function tambah()
  {
    $list_ruangan = $this->ruanganModel->findAll();
    d($list_ruangan);

    $akses = ['umum', 'fakultas', 'dike', 'kimia', 'fisika', 'matematika'];
    $aksesDipilih = '';

    foreach ($akses as $a) {
      if ($this->request->getVar($a) == 'on') {
        $aksesDipilih = $aksesDipilih == '' ? $aksesDipilih . $a :
          $aksesDipilih . ','  . $a;
      }
    }

    d($aksesDipilih);

    $this->ruanganModel->save([
      'nama' => $this->request->getVar('nama'),
      'lokasi' => $this->request->getVar('lokasi'),
      'akses' => $aksesDipilih,
    ]);

    return redirect()->to('/ruangan/test');
  }

  public function test()
  {
    $data = [
      'title' => 'test',
      'ruangan' => $this->ruanganModel->findAll()
    ];

    // d($data['ruangan']);

    return view('/ruangan/test', $data);
  }
}
