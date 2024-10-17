<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CridDetail extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true,
            ],
            'crid_id' => [
                'type' => 'INT',
                'constraint' => '11',
            ],
            'name_field' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
                'default' => null
            ],
            'name_alias' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
                'default' => null,
            ],
            'name_type' => [
                'type' => 'VARCHAR',
                'constraint' => '32',
                'default' => null,
            ],
            'field_form' => [
                'type' => 'INT',
                'default' => null,
            ],
            'field_database' => [
                'type' => 'INT',
                'default' => null,
            ],
            'field_required' => [
                'type' => 'INT',
                'default' => null,
            ],
            'field_min' => [
                'type' => 'INT',
                'constraint' => '11',
                'null' => null,
            ],
            'field_max' => [
                'type' => 'INT',
                'constraint' => '11',
                'null' => null,
            ],
            'field_settings' => [
                'type' => 'TEXT',
                'default' => null,
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
        $this->forge->createTable('crid_detail');
    }

    public function down()
    {
        $this->forge->dropTable('crid_detail');
    }
}
