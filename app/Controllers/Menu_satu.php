<?php

namespace App\Controllers;

use App\Libraries\Tema;
use App\Models\StatisticModel;
use CodeIgniter\Controller;
use CodeIgniter\I18n\Time;

class Menu_satu extends Controller
{
    private $tema;
    private $statisticModel;

    public function __construct()
    {
        helper(['Permission_helper', 'date']);
        $this->statisticModel = new StatisticModel();
        $this->tema = new Tema();
    }

    public function index()
    {
        if (enforce(1, 1) == FALSE) {
            return redirect()->to('/login')->with('messageLogin', '<div class="alert alert-danger">Anda Tidak Mempunyai Akses</div>');
            die;
        }
        $time = new Time('now');
        echo $time->now()->toLocalizedString('YYYY MM dd');
        // $this->tema->loadTema('menu/menu_satu');
    }
}
