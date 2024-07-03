<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Product extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true,
            ],
            'nama' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
                'default' => null,
                'null' => true
            ],
            'harga' => [
                'type' => 'BIGINT',
                'constraint' => '64',
                'default' => null,
                'null' => true
            ],
            'tanggal' => [
                'type' => 'DATE',
                'default' => null,
                'null' => true
            ],
            'deskripsi' => [
                'type' => 'TEXT',
                'default' => null,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'default' => null,
                'null' => true
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'default' => null,
                'null' => true
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'default' => null,
                'null' => true
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('product');
    }

    public function down()
    {
        $this->forge->dropTable('product');
    }
}
