<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class SuratPeminjamanEntity extends Entity
{
    protected $datamap = [
        'user' => 'user_id',
    ];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts = [];

    function getUserId()
    {
        return (new \App\Models\UserModel())
            ->select('id, username, nama')
            ->find($this->attributes['user_id']);
    }

    function setUserId($id)
    {
        return (new \App\Models\UserModel())->update($this->attributes['user_id'], ['id' => $id]);
    }
}