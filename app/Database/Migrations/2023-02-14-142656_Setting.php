<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Setting extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'setting_id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'setting_title' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'setting_description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'setting_created_at' => [
                'type' => 'datetime'
            ],
            'setting_updated_at' => [
                'type' => 'datetime'
            ],
            'setting_deleted_at' => [
                'type' => 'datetime'
            ],
        ]);
        $this->forge->addKey('setting_id', true);
        $this->forge->createTable('setting');
    }

    public function down()
    {
        $this->forge->dropTable('setting');
    }
}
