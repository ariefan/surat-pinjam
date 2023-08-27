<?php

namespace App\Controllers;

use Hashids\Hashids;
use App\Models\SettingModel;
use App\Models\PegawaiModel;

class Setting extends BaseController
{
    public $peraturan;

    function __construct()
    {
    }

    public function index()
    {
        $data = [
            'pegawais' => (new PegawaiModel())->orderBy('nama_publikasi', 'ASC')->get()->getResult(),
        ];
        return view('setting/index', $data);
    }

    public function update()
    {
        $data = $this->request->getPost();
        foreach ($data as $key => $value) {
            (new SettingModel())->update($key, ['value' => $value]);
        }
        $setting = [];
        $rows = (new SettingModel())->where('scope', 'user')->get()->getResult();
        foreach ($rows as $r) {
            $setting[$r->parameter] = ['label' => $r->label, 'value' => $r->value];
        }
        session()->set(['setting' => $setting]);
        session()->setFlashdata('success', 'Data berhasil disimpan');
        return $this->response->redirect(site_url('setting'));
    }
}
