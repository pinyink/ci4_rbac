<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CrudConfig extends Migration
{
    public function up()
    {
        $this->forge->addField(
            [
                'crud_table' => [
                    'type' => 'varchar',
                    'constraint' => '128'
                ],
                'crud_config' => [
                    'type' => 'TEXT'
                ],
                'crud_created_at' => [
                    'type' => 'datetime'
                ],
                'crud_updated_at' => [
                    'type' => 'datetime'
                ],
                'crud_deleted_at' => [
                    'type' => 'datetime'
                ],
            ]
        );
        $this->forge->addKey('crud_table', true);
        $this->forge->createTable('crud');
    }

    public function down()
    {
        $this->forge->dropTable('crud');
    }
}
