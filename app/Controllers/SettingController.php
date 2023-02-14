<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\Tema;

class SettingController extends BaseController
{

    public function index()
    {
        $tema = new Tema();
        $tema->setJudul('Setting');
        $tema->loadTema('setting');
    }
}
