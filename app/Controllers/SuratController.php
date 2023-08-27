<?php

namespace App\Controllers;

use App\Models\SuratTugasModel;
use App\Models\DetailNomorSuratModel;
use App\Models\NomorSuratModel;
use App\Models\DepartemenModel;
use Dompdf\Dompdf;
use chillerlan\QRCode\{QRCode, QROptions};
use App\Models\TodoModel;
use App\Models\UserModel;
use App\Models\PegawaiModel;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

abstract class StatusSurat
{
    const Draft = 0;
    const Baru = 1;
    const Terverifikasi = 2;
    const Tertandatangan = 3;
}

class SuratController extends BaseController
{
}
