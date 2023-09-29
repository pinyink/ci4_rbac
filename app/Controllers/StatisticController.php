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

    public function permonth()
    {
        $month = $this->request->getPost('month') == null ? date('Y-m') : $this->request->getPost('month');
        $explode = explode('-', $month);
        $bulan = $explode[1].'-'.$explode[0];
        $query = $this->statisticModel->statistik($bulan)->getResultArray();
        $data = [];
        foreach ($query as $key => $value) {
            $array = [
                'tanggal' => $value['tanggal'],
                'total' => $value['total']
            ];
            array_push($data, $array);
        }
        
        return $this->response->setJSON(['data' => $data, 'bulan' => $explode[0], 'tahun' => $explode[1]]);
        // return $this->response->setJSON($query);
    }
}
