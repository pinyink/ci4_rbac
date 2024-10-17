<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Policy extends Migration
{
    public function up()
    {
        //
        $this->forge->addField([
            'policy_id' => [
                'type' => 'VARCHAR',
                'constraint' => 128,
                'unique' => true,
            ],
            'policy_desc' => [
                'type' => 'VARCHAR',
                'constraint' => '128',
                'null' => true
            ],
            'policy_created_at' => [
                'type' => 'datetime',
                'null' => true
            ],
            'policy_updated_at' => [
                'type' => 'datetime',
                'null' => true
            ],
            'policy_deleted_at' => [
                'type' => 'datetime',
                'null' => true
            ],
        ]);
        $this->forge->addKey('policy_id', true);
        $this->forge->createTable('policy');
    }

    public function down()
    {
        //
        $this->forge->dropTable('policy');
    }
}
