<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\Tema;
use App\Models\StatisticModel;

class StatisticController extends BaseController
{
    private $tema;
    private $statisticModel;

    function __construct()
    {
        helper(['form', 'FormCustom']);
        $this->tema = new Tema();
        $this->statisticModel = new StatisticModel();
    }

    public function index()
    {
        $this->tema->setJudul('Statistic Pengunjung');
        $this->tema->loadTema('statistic');
    }
}
