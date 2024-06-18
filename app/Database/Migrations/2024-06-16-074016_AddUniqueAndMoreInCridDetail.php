<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUniqueAndMoreInCridDetail extends Migration
{
    public function up()
    {
        $fields = [
            'field_unique' => [
                'type' => 'INT',
                'default' => null,
                'null' => true
            ],
        ];
        $this->forge->addColumn('crid_detail', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('crid_detail', ['field_unique']);
    }
}
