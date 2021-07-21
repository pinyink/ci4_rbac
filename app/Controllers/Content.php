<?php
namespace App\Controllers;
use CodeIgniter\Controller;

class Content extends Controller
{
    public function tambah()
    {
        $enforcer = \Config\Services::enforcer();
        if ($enforcer->enforce("joni", "content", "tambah")) {
            // permit eve to edit articles
            echo "Anda Mempunyai Akses";
        } else {
            // deny the request, show an error
            echo "Anda tidak mempunyai akses";
        }
    }
}
