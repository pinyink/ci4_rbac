<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\Tema;
use CodeIgniter\Database\RawSql;
use App\Models\ProductModel;

class ProductController extends BaseController
{
    private $tema;
    private $productModel;

    function __construct()
    {
        helper(['form', 'Permission_helper']);
        $this->tema = new Tema();
        $this->productModel = new ProductModel();
    }

    public function index()
    {
        $this->tema->setJudul('Product');
        $this->tema->loadTema('product/index');
    }
}