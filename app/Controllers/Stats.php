<?php

namespace App\Controllers;

use App\Models\StatsModel;
use Hashids\Hashids;

class Stats extends BaseController
{
    public $stats;

    function __construct()
    {
        $this->stats = new StatsModel();
    }

    public function index()
    {
        $last = $this->stats->select('tahun, periode')->orderBy('tahun', 'DESC')->orderBy('periode', 'DESC')->first();
        // $periodes = $this->stats->select('tahun, bulan')->groupBy(['tahun', 'bulan'])->orderBy('tahun', 'DESC')->get()->getResult();
        $periodes = $this->stats->select('tahun, periode')->groupBy(['tahun', 'periode'])->orderBy('tahun', 'DESC')->get()->getResult();

        $q_tahun = empty($this->request->getGet('q_tahun')) ? $last->tahun : $this->request->getGet('q_tahun');
        // $q_bulan = empty($this->request->getGet('q_bulan')) ? $last->bulan : $this->request->getGet('q_bulan');
        $q_periode = empty($this->request->getGet('q_periode')) ? $last->periode : $this->request->getGet('q_periode');

        $rows = $this->stats
            // ->where('tahun', $q_tahun)
            // ->where('bulan', $q_bulan)
            ->where('periode', $q_periode)
            ->get()->getResult();
        $data = [
            'q_tahun' => $q_tahun,
            // 'q_bulan' => $q_bulan,
            'q_periode' => $q_periode,
            'rows' => $rows,
            'periodes' => $periodes,
        ];
        return view('stats/index', $data);
    }

    public function data()
    {
        $data = $this->stats
            ->where('aktif', 1)
            ->like('stats', $this->request->getGet('q') ?? '')
            ->findAll(10);
        return $this->response->setJSON($data);
    }

    public function create()
    {
        $row = new StatsModel();
        $row->tanggal_berlaku = date('Y-m-d');
        $row->aktif = true;
        $data = [
            'action' => 'store',
            'row' => $row,
        ];
        return view('stats/form', $data);
    }

    public function store()
    {
        $data = $this->request->getPost();
        $db = \Config\Database::connect();
        $db->query("INSERT into stats(
            `no`,
            indikator,
            satuan,
            tahun,
            periode,
            target,
            jumlah_prodi,
            capaian,
            capaian_ike,
            capaian_fis,
            capaian_mat,
            capaian_kim,
            sumber_data,
            keterangan,
            created_at)
            select
            `no`,
            indikator,
            satuan,
            " . $data['tahun'] . ",
            '" . $data['periode'] . "',
            target,
            jumlah_prodi,
            capaian,
            capaian_ike,
            capaian_fis,
            capaian_mat,
            capaian_kim,
            sumber_data,
            keterangan,
            NOW()
            from stats WHERE (tahun, periode) = (SELECT tahun, periode from stats ORDER BY tahun desc, periode desc LIMIT 1)");
        session()->setFlashdata('success', 'Data berhasil dibuat');
        return $this->response->redirect(site_url('stats'));
    }

    public function storeindikator()
    {
        $data = $this->request->getPost();
        $model = new StatsModel();
        $model->insert($data);
        session()->setFlashdata('success', 'Data berhasil disimpan');
        return $this->response->redirect(site_url('stats'));
    }

    public function edit($id)
    {
        $row = $this->stats->where('id', $id)->first();
        $data = [
            'action' => 'update',
            'row' => $row,
        ];
        return view('stats/form', $data);
    }

    public function update()
    {
        $datas = $this->request->getPost();
        foreach ($datas['data'] as $key => $data) {
            $this->stats->update($key, $data);
        }
        session()->setFlashdata('success', 'Data berhasil disimpan');
        return $this->response->redirect(site_url('stats'));
    }

    public function delete($id)
    {
        if (!session('logged_in'))
            return redirect()->to(base_url('auth'));
        $this->stats->where('id', $id)->delete();
        session()->setFlashdata('success', 'Data berhasil terhapus');
        return $this->response->redirect(site_url('stats'));
    }

    public function deleteall($tahun, $periode)
    {
        if (!session('logged_in'))
            return redirect()->to(base_url('auth'));
        $this->stats->where("tahun", $tahun)->where('periode', $periode)->delete();
        session()->setFlashdata('success', 'Data capaian berhasil terhapus');
        return $this->response->redirect(site_url('stats'));
    }
}