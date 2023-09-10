<?php

namespace App\Controllers;

use App\Models\PegawaiModel;
use App\Models\StatsModel;

class Client extends BaseController
{
    public function index()
    {
        return view('surat-pinjam/index');
    }

}