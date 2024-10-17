<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Crid extends Migration
{
    public function up()
    {
        //
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'table' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
                'default' => null
            ],
            'namespace' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
                'default' => null
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
                'default' => null,
            ],
            'primary_key' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
                'default' => null
            ],
            'v_created_at' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
                'default' => null
            ],
            'v_updated_at' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
                'default' => null
            ],
            'v_deleted_at' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
                'default' => null
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'default' => null
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'default' => null
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'default' => null
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('crid');
    }

    public function down()
    {
        $this->forge->dropTable('crid');
    }
}
