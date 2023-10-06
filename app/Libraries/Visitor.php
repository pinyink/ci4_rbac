<?php

namespace App\Libraries;

use App\Models\StatisticModel;
use CodeIgniter\I18n\Time;

class Visitor
{
    private $statisticModel;
    private $time;

    function __construct()
    {
        $this->statisticModel = new StatisticModel();    
        $this->time = new Time('now');
    }

    public function visit() : bool
    {        
        $this->statisticModel->updateJumlah();
        return true;
    }

    public function visitDetail() : array
    {
        $request = \Config\Services::request();
        $agent = $request->getUserAgent();
        $data['ip_address'] = $request->getIPAddress();
        if ($agent->isBrowser()) {
            $currentAgent = $agent->getBrowser() . ' ' . $agent->getVersion();
            $data['agent'] = 1;
        } elseif ($agent->isRobot()) {
            $currentAgent = $agent->getRobot();
            $data['agent'] = 2;
        } elseif ($agent->isMobile()) {
            $currentAgent = $agent->getMobile();
            $data['agent'] = 3;
        } else {
            $currentAgent = 'Unidentified User Agent';
            $data['agent'] = 4;
        }
        
        $data['agent_detail'] = $currentAgent;
        $data['platform'] = $agent->getPlatform();
        $data['url'] = str_replace(base_url(), '', current_url());
        $data['created_at'] = $this->time->now();
        $this->statisticModel->detailPengunjung($data);
        return $data;
    }
}