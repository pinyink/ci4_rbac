<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Rules extends Migration
{
    public function up()
    {
        //
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => '11',
                'unsigned' => true,
                'auto_increment' => true
            ],
            'ptype' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'v0' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'v1' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'v2' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'v3' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'v4' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'v5' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('rules');
    }

    public function down()
    {
        //
        $this->forge->dropTable('rules');
    }
}
