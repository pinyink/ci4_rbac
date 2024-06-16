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
        helper(['form', 'Permission_helper', 'FormCustom']);
        $this->tema = new Tema();
        $this->productModel = new ProductModel();
    }

    public function index()
    {
        $this->tema->setJudul('Product');
        $this->tema->loadTema('product/index');
    }

	public function ajaxList()
    {
        $this->productModel->setRequest($this->request);
        $lists = $this->productModel->getDatatables();
        $data = [];
        $no = $this->request->getPost("start");
        foreach ($lists as $list) {
            $no++;
            $row = [];
            $id = $list->id;
            $aksi = '<a href="'.base_url('product/'.$id.'/detail').'" class="" data-toggle="tooltip" data-placement="top" title="Lihat Data"><i class="fa fa-search"></i></a>';
            if(enforce(1, 3)) {
                $aksi .= '<a href="'.base_url('product/'.$id.'/edit').'" class="ml-2" data-toggle="tooltip" data-placement="top" title="Edit Data" onclick="edit_data('.$id.')"><i class="fa fa-edit"></i></a>';
            }

            if(enforce(1, 4)) {
                $aksi .= '<a href="javascript:;" class="text-danger ml-2" data-toggle="tooltip" data-placement="top" title="Delete Data" onclick="delete_data('.$id.')"><i class="fa fa-trash"></i></a>';
            }
            $action = $aksi;
            
            $row[] = $action;
            $row[] = $no;
			$row[] = $list->nama;
			$row[] = $list->harga;
            $data[] = $row;
        }
        $output = [
                "draw" => $this->request->getPost('draw'),
                "recordsTotal" => $this->productModel->countAll(),
                "recordsFiltered" => $this->productModel->countFiltered(),
                "data" => $data
            ];
        echo json_encode($output);
    }

	public function rules($id = null)
    {
        $rules = [
			'nama' => [
                'label' => 'Nama Product',
                'rules' => 'required|is_unique[product.nama, id, '.$id.']|max_length[64]',
                'errors' => [
                    'required' => '{field} Harus di isi',
					'is_unique' => '{field} Sudah Ada, harap ketik yang lainnya',
					'max_length' => '{field} Maksimal 64 Huruf'
                ]
            ],
			'harga' => [
                'label' => 'Harga',
                'rules' => 'required|max_length[16]',
                'errors' => [
                    'required' => '{field} Harus di isi',
					'max_length' => '{field} Maksimal 16 Huruf'
                ]
            ],
        ];

        return $rules;
    }

	public function tambahData(){
        $data = [
            'button' => 'Simpan',
            'id' => '',
            'method' => 'save',
            'url' => 'product/save_data'
        ];
        $this->tema->setJudul('Tambah Product');
        $this->tema->loadTema('product/tambah', $data);
    }

	public function editData($id){
        $query = $this->productModel->detail(['a.id' => $id])->getRowArray();
        if(empty($query)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $data = [
            'button' => 'Simpan',
            'id' => $id,
            'method' => 'update',
            'url' => 'product/update_data',
            'product' => $query
        ];
        $this->tema->setJudul('Edit Product');
        $this->tema->loadTema('product/edit', $data);
    }

	public function saveData($id = null)
    {
        $validation = service('validation');
        $request    = service('request');
        //get method form
        $id = $request->getPost('id');
        $method = $request->getPost('method');
        //set rules validation
        $validation->setRules($this->rules($id));

        if ($validation->withRequest($request)->run()) {
            $validData = $validation->getValidated();
            if($method == 'save') {
                $id = $this->productModel->insert($validData);
                return redirect()->to('product/'.$id.'/detail')->with('message', '<div class="alert alert-success">Simpan Data Berhasil</div>');
            } else {
                $this->productModel->update($id, $validData);
                return redirect()->to('product/'.$id.'/edit')->with('message', '<div class="alert alert-success">Update Data Berhasil</div>');
            }
        } else {
            $error = $validation->getErrors();
        }
        
        if($method == 'save') {
            return redirect()->to('product/tambah')->withInput();
        } else {
            return redirect()->to('product/'.$id.'/edit')->withInput();
        }
    }

	public function detailData($id){
        $query = $this->productModel->detail(['a.id' => $id])->getRowArray();
        if(empty($query)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $data = [
            'product' => $query
        ];
        $this->tema->setJudul('Detail Product');
        $this->tema->loadTema('product/detail', $data);
    }
}