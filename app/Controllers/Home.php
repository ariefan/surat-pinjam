<?php

namespace App\Controllers;

use App\Models\PegawaiModel;
use App\Models\StatsModel;

class Home extends BaseController
{
    // public function index()
    // {
    //     $content = file_get_contents(FCPATH . 'client/index.html');
    //     return ($content);
    // }

    public function index()
    {
        $stats    = new StatsModel();
        $last     = $stats->select('tahun, bulan')->orderBy('tahun', 'DESC')->orderBy('bulan', 'DESC')->first();
        $periodes = $stats->select('tahun, bulan')->groupBy(['tahun', 'bulan'])->orderBy('tahun', 'DESC')->get()->getResult();

        $q_tahun = empty($this->request->getGet('q_tahun')) || !is_numeric($this->request->getGet('q_tahun')) ? $last->tahun : $this->request->getGet('q_tahun');
        $q_bulan = empty($this->request->getGet('q_bulan')) || !is_numeric($this->request->getGet('q_bulan')) ? $last->bulan : $this->request->getGet('q_bulan');

        $rows = $stats
            ->where('tahun', $q_tahun)
            ->where('bulan', $q_bulan)
            ->get()->getResult();
        $data = [
            'q_tahun'  => $q_tahun,
            'q_bulan'  => $q_bulan,
            'rows'     => $rows,
            'periodes' => $periodes,
            'label'    => ['IKE', 'FIS', 'MAT', 'KIM'],
        ];
        return view('home/index', $data);
    }

    public function autocomplete()
    {
        $model = new PegawaiModel();
        if (empty($this->request->getGet('users'))) {
            $dosens = $model->select('id, nama_publikasi, nip, prodi, departemen, pangkat, golongan')
                ->like('nama_publikasi', $this->request->getGet('term'))->findAll(3);
            $data   = $dosens;
            return $this->response->setJSON($data);
        } else {
            $dosens = $model->like('nama', $this->request->getGet('term'))->where('jenis_user', $this->request->getGet('users'))->findAll();
            ;

            $data = [];
            foreach ($dosens as $dosen) {
                $data[] = [
                    'id'    => $dosen->id,
                    'value' => $dosen->nama,
                    'label' => $dosen->nama
                ];
            }
            return $this->response->setJSON($data);
        }
    }



    public function download($base64url)
    {
        return $this->response->download(base64_decode($base64url), null);
    }

    public function getview($view, $data = [])
    {
        return view(base64_decode($view), $data);
    }

    public function test()
    {
        return view('home/test');
    }

    // Untuk tampilan hutang verifikasi departemen dan approve penandatangan
    // public function hutang()
    // {
    //     $db = \Config\Database::connect();
    //     $data['rows'] = $db->query("select
    //     user_penandatangan.nama_publikasi nama, COUNT(1) jumlah
    //     from surat_tugas
    //     LEFT JOIN 
    //     (SELECT nama_publikasi, departemen_id, user_id, pegawais.id pegawai_id 
    //     FROM pegawais JOIN users ON pegawais.user_id = users.id) user_penandatangan ON penandatangan_pegawai_id = user_penandatangan.pegawai_id
    //     WHERE status = 2
    //     GROUP BY user_penandatangan.user_id;")->getResult();
    //     return view('home/hutang', $data);
    // }
    public function hutang()
    {
        $db            = \Config\Database::connect();
        $data['rows']  = $db->query("SELECT user_id, MIN(nama) nama, SUM(jumlah_approval) jumlah_approval, SUM(jumlah_verifikasi) jumlah_verifikasi FROM 
        (SELECT
        user_penandatangan.user_id, MIN(user_penandatangan.nama_publikasi) nama, 0 jumlah_approval, COUNT(1) jumlah_verifikasi
        FROM surat_tugas
        LEFT JOIN 
        (SELECT nama_publikasi, departemen_id, user_id, pegawais.id pegawai_id 
        FROM pegawais JOIN users ON pegawais.user_id = users.id) user_penandatangan ON penandatangan_pegawai_id = user_penandatangan.pegawai_id
        WHERE status = 2
        GROUP BY user_penandatangan.user_id
        UNION
        SELECT p1.user_id, MIN(p1.nama_publikasi) nama, COUNT(1) jumlah_approval, 0 jumlah_verifikasi
        from 
        pegawais p1
        JOIN users ON users.id = p1.user_id
        JOIN (SELECT surat_tugas.id, departemen_id FROM surat_tugas JOIN pegawais p2 ON p2.id = departemen_pegawai_id AND status = 1) st ON p1.departemen_id = st.departemen_id 
        WHERE jenis_user IN('dekan', 'wadek')
        GROUP BY user_id) t
        GROUP BY user_id;")->getResult();
        $data['drows'] = $db->query("SELECT
        -- surat_tugas.id id_surat,
        -- p.id,
        -- p2.id,
        MIN(p2.nama_publikasi) nama_ketua_departemen,
        MIN(p3.nama_publikasi) nama_sekretaris_departemen,
        COUNT(1) jml
        FROM surat_tugas 
        JOIN pegawais p on p.user_id = surat_tugas.user_id 
        JOIN departemen d on d.id = p.departemen_id 
        JOIN pegawais p2 on d.kepala_pegawai_id = p2.id
        JOIN pegawais p3 on d.sekretaris_pegawai_id = p3.id
        WHERE status = 1 AND surat_tugas.verifikasi_departemen is null
        GROUP BY p2.id;")->getResult();
        return view('home/hutang', $data);
    }
}