<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRouteNameToCrid extends Migration
{
    public function up()
    {
        $fields = [
            'routename' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
                'null' => false,
            ],
            'rbac' => [
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => false
            ]
        ];
        $this->forge->addColumn('crid', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('crid', ['routename', 'rbac']);
    }
}
