<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Siswa extends Migration
{
    public function up()
    {
        $this->forge->addField(
            [
                'siswa_id' => [
                    'type' => 'int',
                    'constraint' => '11',
                    'auto_increment' => true,
                ],
                'siswa_nama' => [
                    'type' => 'varchar',
                    'constraint' => '128'
                ],
                'siswa_alamat' => [
                    'type' => 'text'
                ],
                'siswa_tempat_lahir' => [
                    'type' => 'varchar',
                    'constraint' => '32'
                ],
                'siswa_tanggal_lahir' => [
                    'type' => 'date'
                ],
                'siswa_created_at' => [
                    'type' => 'datetime'
                ],
                'siswa_updated_at' => [
                    'type' => 'datetime'
                ],
                'siswa_deleted_at' => [
                    'type' => 'datetime'
                ],
            ]
        );
        $this->forge->addKey('siswa_id', true);
        $this->forge->createTable('siswa');
    }

    public function down()
    {
        $this->forge->dropTable('siswa');
    }
}
