<?php

namespace App\Controllers;

use App\Models\UndanganModel;
use App\Models\UserModel;
use Dompdf\Dompdf;

class Undangan extends BaseController
{
  protected $userModel;
  protected $undanganModel;

  public function __construct()
  {
    $this->userModel = new UserModel();
    $this->undanganModel = new UndanganModel();
  }

  public function page1()
  {
    $data = [
      'title' => 'test',
      'undangan' => $this->undanganModel->findAll()
    ];

    return view('undangan/page1', $data);
  }

  public function index()
  {
    $data = [
      'title' => 'Undangan'
    ];
    return view('undangan/index', $data);
  }

  public function baru()
  {

    $data = [
      'title' => 'Buat Baru',
      'users' => $this->userModel->findAll(),
      'action' => 'ajukan',
      'undangan' => null
    ];
    return view('undangan/baru', $data);
  }

  public function ajukan()
  {

    $tanggalUndangan = $this->request->getVar('tanggal_undangan');

    $hal = $this->request->getVar('hal');

    $lampiran = $this->request->getVar('lampiran');

    $pengundang = $this->request->getVar('pengundang');

    $sehubunganDengan = $this->request->getVar('sehubungan_dengan');

    $hari = $this->request->getVar('hari');

    $tanggal = $this->request->getVar('tanggal');

    $pukul = $this->request->getVar('pukul');

    $tempat = $this->request->getVar('tempat');

    $acara = $this->request->getVar('acara');

    $agenda = $this->request->getVar('agenda');

    // combine list of registered penerima into one string separated by ','
    $totalPenerima = $this->request->getVar('total-penerima');
    $penerimas = '';
    for ($i = 0; $i <= $totalPenerima; $i++) {
      $name = $this->request->getVar("penerima$i");
      if (isset($name)) {
        $emailPenerima = $this->userModel->where('nama', $name)->first()['username'];
        $penerimas = $penerimas == '' ? $emailPenerima : $penerimas . ',' . $emailPenerima;
      };
    }

    // search for the petugas absen email and combine them into one string separated by ','
    $totalAbsen = $this->request->getVar('total-absen');
    $absens = '';
    for ($i = 1; $i < $totalAbsen; $i++) {
      $name = $this->request->getVar("absen$i");
      if (isset($name)) {
        $emailAbsen = $this->userModel->where('nama', $name)->first()['username'];
        $absens = $absens == '' ? $emailAbsen : $absens . ',' . $emailAbsen;
      };
    }

    // search for the notulen email
    $notulenName = $this->request->getVar('notulen');
    $emailNotulen = $this->userModel->where('nama', $notulenName)->first()['username'];

    $this->undanganModel->save([
      'tanggal_undangan' => $tanggalUndangan,
      'hal' => $hal,
      'lampiran' => $lampiran,
      'pengundang' => $pengundang,
      'sehubungan_dengan' => $sehubunganDengan,
      'hari' => $hari,
      'tanggal' => $tanggal,
      'pukul' => $pukul,
      'tempat' => $tempat,
      'acara' => $acara,
      'agenda' => $agenda,
      'penerima' => $penerimas,
      'absen' => $absens,
      'notulen' => $emailNotulen
    ]);

    return redirect()->to('undangan/page1');
  }

  public function delete($id)
  {
    $this->undanganModel->delete($id);
    return redirect()->to('undangan/page1');
  }

  public function edit($id)
  {
    $data      = [
      'undangan' => $this->undanganModel->find($id),
      'users' => $this->userModel->findAll(),
      'action' => 'update',
      'title' => 'Edit Data'
    ];
    return view('undangan/baru', $data);
  }

  public function update($id)
  {
    $data      = $this->request->getPost();
    $this->undanganModel->update($id, $data);
    session()->setFlashdata('success', 'Data berhasil disimpan');
    $this->response->redirect(site_url('undangan/page1'));
  }

  public function pdf($id)
  {
    $data      = [
      'undangan' => $this->undanganModel->find($id),
    ];
    $filename  = 'suratbege_' . date('y-m-d_H.i.s') . '.pdf';
    $dompdf    = new Dompdf();
    $dompdf->getOptions()->setChroot(FCPATH);
    $dompdf->loadHtml(view('undangan/lihat', $data));
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream($filename, ['Attachment' => 0]);
  }
}
