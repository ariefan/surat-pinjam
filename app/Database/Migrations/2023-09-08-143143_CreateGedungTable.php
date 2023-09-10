<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateGedungTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_gedung' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'lokasi'      => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'created_at'  => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at'  => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('gedung');
    }

    public function down()
    {
        $this->forge->dropTable('gedung');
    }
}