<?php

namespace App\Libraries;

class Tema
{
    private $judul = 'home';

    public function getJudul()
    {
        return $this->judul;
    }

    public function setJudul($judul)
    {
        $this->judul = $judul;
    }

    public function loadTema($content = '', $data = [])
    {
        helper('Permission_helper');
        $data['judul'] = $this->getJudul();
        echo view($content, $data);
    }
}
