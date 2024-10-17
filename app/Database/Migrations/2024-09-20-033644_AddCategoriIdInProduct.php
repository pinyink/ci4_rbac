<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCategoriIdInProduct extends Migration
{
    public function up()
    {
        $fields = [
            'categories_id' => [
                'type' => 'INT'
            ]
        ];
        $this->forge->addColumn('product', $fields);

    }

    public function down()
    {
        $this->forge->dropColumn('product', ['categories_id']);
    }
}
