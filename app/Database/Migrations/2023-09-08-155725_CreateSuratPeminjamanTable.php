<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSuratPeminjamanTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'no_surat'   => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'nama_surat' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'tembusan'   => [
                'type' => 'TEXT',
            ],
            'user_id'    => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('surat_peminjaman');
    }

    public function down()
    {
        $this->forge->dropTable('surat_peminjaman');
    }
}