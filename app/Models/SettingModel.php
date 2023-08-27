<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table = "settings";
    protected $primaryKey = "parameter";
    protected $returnType = "object";
    protected $useTimestamps = true;
    protected $allowedFields = ['label', 'value'];
}
