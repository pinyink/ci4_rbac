<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class StatisticDetail extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'constraint' => '16',
                'auto_increment' => true,
                'unsigned' => true
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => '16',
                'default' => null
            ],
            'agent' => [
                'type' => 'INT',
                'constaint' => 11,
                'default' => null
            ],
            'agent_detail' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'default' => null
            ],
            'platform' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
                'default' => null,
            ],
            'url' => [
                'type' => 'VARCHAR',
                'constraint' => '128',
                'default' => null,
            ],
            'created_at' => [
                'type' => 'DATETIME'
            ]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('statistic_detail');
    }

    public function down()
    {
        $this->forge->dropTable('statistic_detail');
    }
}
