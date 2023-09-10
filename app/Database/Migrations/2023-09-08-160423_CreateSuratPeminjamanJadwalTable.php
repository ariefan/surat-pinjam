<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSuratPeminjamanJadwalTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                     => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'surat_peminjaman_id'    => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'ruang_id'               => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'tanggal_mulai_pinjam'   => [
                'type' => 'DATE',
                'null' => true,
            ],
            'tanggal_selesai_pinjam' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'jam_mulai_pinjam'       => [
                'type' => 'TIME',
                'null' => true,
            ],
            'jam_selesai_pinjam'     => [
                'type' => 'TIME',
                'null' => true,
            ],
            'hari_pinjam'            => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'created_at'             => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at'             => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('surat_peminjaman_jadwal');
    }

    public function down()
    {
        $this->forge->dropTable('surat_peminjaman_jadwal');
    }
}