<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class Menu extends Migration
{
    public function up()
    {
        //
        $this->forge->addField([
            'menu_id' => [
                'type' => 'INT',
                'constraint' => '11',
                'auto_increment' => true,
            ],
            'menu_desc' => [
                'type' => 'VARCHAR',
                'constraint' => '128'
            ],
            'menu_created_at' => [
                'type' => 'datetime',
                'default' => new RawSql('CURRENT_TIMESTAMP')
            ],
            'menu_updated_at' => [
                'type' => 'datetime',
                'null' => true
            ],
            'menu_deleted_at' => [
                'type' => 'datetime',
                'null' => true
            ],
        ]);
        $this->forge->addKey('menu_id', 'true');
        $this->forge->createTable('menu');
    }

    public function down()
    {
        //
        $this->forge->dropTable('menu');
    }
}
