<?php

namespace App\Controllers;

class Validate extends BaseController
{
    //Ketika tampilan awal validasi
    public function index()
    {
        $data = $this->request->getGet();
        $data['id'] = $data['id'] ?? '';
        if (file_exists('validasi/' . $data['id'] . '.pdf')) {
            return $this->response->redirect(base_url('validasi/' . $data['id'] . '.pdf'));
        }
        return view('validasi/index', $data);
    }
}
