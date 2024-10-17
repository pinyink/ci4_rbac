<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Statistic extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'statistic_id' => [
                'type' => 'INT',
                'contraint' => 11,
                'unsigned' => true
            ],
            'statistic_date' => [
                'type' => 'DATE',
                'default' => null
            ],
            'total' => [
                'type' => 'INT',
                'constraint' => '11'
            ]
        ]);
        $this->forge->addKey('statistic_id', true);
        $this->forge->createTable('statistic');
    }

    public function down()
    {
        $this->forge->dropTable('statistic');
    }
}
