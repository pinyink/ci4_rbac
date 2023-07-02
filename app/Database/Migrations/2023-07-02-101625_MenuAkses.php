<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class MenuAkses extends Migration
{
    public function up()
    {
        //
        $this->forge->addField([
            'akses_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'menu_akses_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'menu_akses_desc' => [
                'type' => 'VARCHAR',
                'constraint' => '128'
            ],
            'menu_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'menu_akses_created_at' => [
                'type' => 'datetime',
                'default' => new RawSql('CURRENT_TIMESTAMP')
            ],
            'menu_akses_updated_at' => [
                'type' => 'datetime',
                'null' => true
            ],
            'menu_akses_deleted_at' => [
                'type' => 'datetime',
                'null' => true
            ],
        ]);
        $this->forge->addKey('akses_id', true);
        $this->forge->createTable('menu_akses');
    }

    public function down()
    {
        //
        $this->forge->dropTable('menu_akses');
    }
}
