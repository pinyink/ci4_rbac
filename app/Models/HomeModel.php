<?php
namespace App\Models;
use CodeIgniter\Model;

class HomeModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getData()
    {
        $data = [
            'id' => 1,
            'nama' => 'pindi'
        ];

        return $data;
    }
}
