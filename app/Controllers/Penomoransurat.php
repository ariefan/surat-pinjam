<?php

namespace App\Controllers;

use App\Models\DetailNomorSuratModel;
use App\Models\NomorSuratModel;

class Penomoransurat extends BaseController
{
    public function index()
    {
        $db = db_connect();
        $data = $this->request->getGet();
        $q = $db->escapeLikeString(htmlspecialchars($data['q'] ?? ''));
        $model = new DetailNomorSuratModel();
        $data['rows'] = $model->select('detail_no_surat.id, no_surat, perihal, tujuan_surat, sifat_surat, tanggal_surat, detail_no_surat.created_at tanggal_buat, detail_no_surat.user_id user_id_pembuat, nama_publikasi')
            ->join('users', 'detail_no_surat.user_id = users.id')
            ->join('pegawais', 'users.id = pegawais.user_id')
            // ->where("users.jenis_user = 'admin' OR detail_no_surat.user_id = " . session('id'))
            ->where("CONCAT(detail_no_surat.id, no_surat, perihal, tujuan_surat, sifat_surat, tanggal_surat, detail_no_surat.created_at, nama_publikasi) LIKE '%$q%'")
            ->where('sifat_surat <> 3 OR (sifat_surat = 3 AND (detail_no_surat.user_id = ' . session('id') . ') OR ' . session('id') . ' in (3,12))')
            ->orderBy('detail_no_surat.created_at', 'DESC')
            ->paginate(50);
        // dd($data, session('id'));
        $data['pager'] = $model->pager;
        $data['q'] = $q;
        return view('penomoran-surat/penomoran_surat', $data);
    }

    public function store()
    {
        $id = date('ymdHis'); //substr(md5(date('YmdHis')), 0, 6);
        $data = $this->request->getPost();
        $data['id'] = $id;
        $model = new DetailNomorSuratModel();
        if (isset($data['no_surat']))
            $data['no_surat'] = $this->create_no_surat(($data['no_surat']), $data['jml_no_surat']);
        list($no, $uni, $penandatangan, $pengolah, $perihal_klasifikasi, $tahun) = explode('/', $data['no_surat']);
        $arr = explode('.', $perihal_klasifikasi);
        $perihal = $arr[0];
        $klasifikasi = $arr[1] . (empty($arr[2]) ? '' : $arr[2]);
        $model->insert([
            'id' => $id,
            'no_surat' => $data['no_surat'],
            'perihal' => $data['dt']['surat_keluar_perihal'],
            'penandatangan' => $penandatangan,
            //$data['penandatangan_pegawai_id'],
            'pengolah' => $pengolah,
            //$data['dt']['pengolah_surat_id'],
            'kode_perihal' => $perihal,
            //$data['dt']['kode_dokumen_id'],
            'klasifikasi_surat' => $klasifikasi,
            //$data['dt']['klasifikasi_id'],
            'tanggal_surat' => $data['dt']['surat_keluar_tgl'],
            'tujuan_surat' => $data['dt']['surat_keluar_instansi'],
            'sifat_surat' => $data['dt']['sifat_id'],
            'user_id' => session('id'),
        ]);
        session()->setFlashdata('success', 'Data berhasil disimpan');
        session()->setFlashdata('no_surat', $data['no_surat']);
        return $this->response->redirect(site_url('penomoransurat'));
    }

    public function update($id)
    {
        $data = $this->request->getPost();
        $model = new DetailNomorSuratModel();
        $model->update($id, [
            'perihal' => $data['dt']['surat_keluar_perihal'],
            'tujuan_surat' => $data['dt']['surat_keluar_instansi'],
            'sifat_surat' => $data['dt']['sifat_id'],
            'user_id' => session('id'),
        ]);
        session()->setFlashdata('success', 'Data berhasil disimpan');
        return $this->response->redirect(site_url('penomoransurat'));
    }

    public function edit($id)
    {
        $data = [
            'action' => 'update',
            'row' => (new DetailNomorSuratModel())->find($id),
        ];
        return view('penomoran-surat/form', $data);
    }

    public function create_no_surat($no, $jml_no_surat = 1)
    {
        $arr = explode('/', $no);
        $kode_klasifikasi = $arr[count($arr) - 2];
        $r = (new NomorSuratModel())->where('kode_klasifikasi', $kode_klasifikasi)->first();
        $increment = $r->nomor;
        if ($jml_no_surat == 1) {
            $no_surat = $increment . $no;
        } else {
            $no_surat = $increment . ' sampai dengan ' . ($increment + ($jml_no_surat - 1)) . $no;
        }
        $increment += $jml_no_surat;
        (new NomorSuratModel())->update($r->id, ['nomor' => $increment]);
        return $no_surat;
    }

    //Proses upload data surat
    public function upload($id)
    {
        return view('penomoran-surat/upload', ['id' => $id]);
    }

    public function uploads($id)
    {
        $filename = $_FILES['berkas']['name'];
        $fileExt = pathinfo($filename, PATHINFO_EXTENSION);

        if (file_exists("upload/penomoran_surat/$id.pdf"))
            unlink("upload/penomoran_surat/$id.pdf");
        if (file_exists("upload/penomoran_surat/$id.zip"))
            unlink("upload/penomoran_surat/$id.zip");

        if ($fileExt == 'pdf') {
            if (!empty($this->request->getFile('berkas')->getFileName())) {
                $file = $this->request->getFile('berkas');
                $file->move('upload/penomoran_surat', $id . '.' . $fileExt);
            }
        } else {
            $zipFile = $this->request->getFile('berkas');
            if ($zipFile->isValid() && $zipFile->getClientMimeType() == 'application/zip') {
                $zipFile->move('upload/penomoran_surat', $id . '.' . $fileExt);
            } else {
                return 'Invalid file type.';
            }
        }
        session()->setFlashdata('success', 'Berkas berhasil diupload');
        return $this->response->redirect(site_url('penomoransurat'));
    }

    public function download($id)
    {
        $ext = 'pdf';
        if (!file_exists("upload/penomoran_surat/$id.$ext")) {
            $ext = 'zip';
        }
        return $this->response->download("upload/penomoran_surat/$id.$ext", null);
    }
}