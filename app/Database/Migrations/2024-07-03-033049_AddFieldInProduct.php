<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFieldInProduct extends Migration
{
    public function up()
    {
        $fields = [
            'foto' => [
                'type' => 'VARCHAR',
                'constraint' => '128',
                'default' => null
            ],
            'dokumen' => [
                'type' => 'VARCHAR',
                'constraint' => '128',
                'default' => null
            ]
        ];

        $this->forge->addColumn('product', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('product', ['foto', 'dokumen']);
    }
}
