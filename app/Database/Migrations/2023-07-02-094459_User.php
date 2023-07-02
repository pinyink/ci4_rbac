<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class User extends Migration
{
    public function up()
    {
        //
        $this->forge->addField([
            'user_id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_username' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'unique' => true
            ],
            'user_password' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'user_superadmin' => [
                'type' => 'ENUM',
                'constraint' => ['1', '2'],
                'default' => '2'
            ],
            'user_level' => [
                'type' => 'VARCHAR',
                'constraint' => '128',
                'null' => false,
            ],
            'user_aktif' => [
                'type' => 'INT',
                'constraint' => 2,
                'default' => '1'
            ],
            'user_created_at' => [
                'type' => 'datetime',
                'null' => true
            ],
            'user_updated_at' => [
                'type' => 'datetime',
                'null' => true
            ],
            'user_deleted_at' => [
                'type' => 'datetime',
                'null' => true
            ],
        ]);
        $this->forge->addKey('user_id', true);
        $this->forge->createTable('user');
    }

    public function down()
    {
        //
        $this->forge->dropTable('user');
    }
}
