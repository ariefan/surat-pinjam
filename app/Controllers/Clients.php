<?php

namespace App\Controllers;

use App\Models\PegawaiModel;
use App\Models\StatsModel;

class Clients extends BaseController
{
    public function index()
    {
        return view('client/index');
    }

}