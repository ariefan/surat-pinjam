<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRuangTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'gedung_id'         => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'nama_ruang'        => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'kapasitas'         => [
                'type'       => 'INT',
                'constraint' => 5,
            ],
            'fasilitas'         => [
                'type' => 'TEXT',
            ],
            'dapat_disewa'      => [
                'type'    => 'BOOLEAN',
                'default' => true,
            ],
            'harga_sewa_bawaan' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
            ],
            'catatan'           => [
                'type' => 'TEXT',
            ],
            'created_at'        => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at'        => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('gedung_id', 'gedung', 'id');
        $this->forge->createTable('ruang');
    }

    public function down()
    {
        $this->forge->dropTable('ruang');
    }
}