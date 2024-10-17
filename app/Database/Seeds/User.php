<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class User extends Seeder
{
    public function run()
    {
        //
        $data['user_username'] = 'admin';
        $data['user_password'] = password_hash('admin', PASSWORD_BCRYPT);
		$data['user_superadmin'] = 1;
		$data['user_aktif'] = 1;
        $this->db->table('user')->insert($data);
    }
}
