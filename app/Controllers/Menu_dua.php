<?php

namespace App\Controllers;

use App\Libraries\Tema;
use CodeIgniter\Controller;

class Menu_dua extends Controller
{
    public function __construct()
    {
        helper('Permission_helper');
        $this->tema = new Tema();
    }

    public function index()
    {
        if (enforce(2, 1) == FALSE) {
            return redirect()->to('/login')->with('messageLogin', '<div class="alert alert-danger">Anda Tidak Mempunyai Akses</div>');
            die;
        }
        $this->tema->loadTema('menu/menu_dua');
    }
}
