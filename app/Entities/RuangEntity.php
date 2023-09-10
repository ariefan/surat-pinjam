<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class RuangEntity extends Entity
{
    protected $datamap = [
        'gedung' => 'gedung_id',
    ];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts = [];

    function getGedungId()
    {
        return (new \App\Models\GedungModel())
            ->select('id, nama_gedung, lokasi')
            ->find($this->attributes['gedung_id']);
    }

    function setGedungId($id)
    {
        return (new \App\Models\GedungModel())->update($this->attributes['gedung_id'], ['id' => $id]);
    }
}