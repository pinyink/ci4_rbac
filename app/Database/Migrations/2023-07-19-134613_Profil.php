<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Profil extends Migration
{
    public function up()
    {
        //
        $this->forge->addField([
            'user_id' => [
                'type' => 'INT',
                'constraint' => '11',
                'unsigned' => true
            ],
            'profil_firstname' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
                'default' => null
            ],
            'profil_lastname' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
                'default' => null,
            ],
            'profil_email' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
                'default' => null
            ],
            'profil_bio' => [
                'type' => 'TEXT',
                'default' => null
            ],
            'profil_image' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ]
        ]);

        $this->forge->addKey('user_id', true);
        $this->forge->createTable('profil');
    }

    public function down()
    {
        $this->forge->dropTable('profil');
        //
    }
}
