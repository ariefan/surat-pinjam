<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationTokenModel extends Model
{
    protected $table = "notification_token";
    protected $primaryKey = "id";
    protected $returnType = "object";
    protected $useTimestamps = false;
    protected $allowedFields = ['id', 'user_id', 'fcmtoken'];
}
