<?php

namespace App\Models;

use CodeIgniter\Model;

class LoginModel extends Model
{
    function __construct($request)
    {
        parent::__construct();
        $this->request = $request;
    }

    public function getData($where = [])
    {
        $tabel = $this->db->table('user a');
        $tabel->select("a.user_id, a.user_username, a.user_password, a.user_superadmin, a.user_aktif");
        $tabel->where($where);
        $tabel->where('a.user_aktif', 1);
        return $tabel->get();
    }
}
