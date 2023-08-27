<?php

namespace App\Models;

use CodeIgniter\Model;

class Notifications extends Model
{
    protected $table = "notifications";
    protected $primaryKey = "id";
    protected $returnType = "object";
    protected $useTimestamps = true;
    protected $allowedFields = ['id', 'user_id', 'notification_type', 'notification_message', 'status'];
}