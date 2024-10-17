<?php

namespace App\Controllers\Master;

use App\Controllers\BaseController;
use App\Libraries\Tema;
use Hermawan\DataTables\DataTable;

use App\Models\ProductModel;

class ProductController extends BaseController
{
    private $tema;
    private $productModel;

    function __construct()
    {
        helper(['form']);
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
        $this->productModel->select('id, nama')->where('deleted_at', null);

        return DataTable::of($this->productModel)
            ->add('action', function($row){
                $btn = '<a href="'.base_url('product/'.$row->id.'/detail').'" class="" data-toggle="tooltip" data-placement="top" title="Lihat Data"><i class="fa fa-search"></i></a>';
                if(enforce(1, 3)) {
                    $btn .= '<a href="'.base_url('product/'.$row->id.'/edit').'" class="ml-2" data-toggle="tooltip" data-placement="top" title="Edit Data"><i class="fa fa-edit"></i></a>';
                }

                if(enforce(1, 4)) {
                    $btn .= '<a href="javascript:;" class="text-danger ml-2" data-toggle="tooltip" data-placement="top" title="Delete Data" onclick="delete_data('.$row->id.')"><i class="fa fa-trash"></i></a>';
                }
                return $btn;
            }, 'first')
            ->setSearchableColumns(['nama'])
            ->toJson();
    }

	public function rules($id = null)
    {
        $rules = [
			'nama' => [
                'label' => 'Nama Product',
                'rules' => 'required|max_length[64]|alpha_numeric_space',
                'errors' => [
                    'required' => '{field} Harus di isi',
					'max_length' => '{field} Maksimal 64 Huruf',
					'alpha_numeric_space' => '{field} Hanya berupa huruf, angka dan spasi'
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
            'url' => 'product/save'
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
            'url' => 'product/update',
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
        $rules = $this->rules($id);
        
        $validation->setRules($rules);

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
            if($method == 'save') {
                return redirect()->to('product/tambah')->withInput();
            } else {
                return redirect()->to('product/'.$id.'/edit')->withInput();
            }
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

	public function deleteData($id){
        $query = $this->productModel->find($id);
        if(empty($query)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $data = [
            'product' => $query
        ];
        $delete = $this->productModel->delete($id);
        if($delete) {
            $log['errorCode'] = 1;
        } else {
            $log['errorCode'] = 2;
        }
        return $this->response->setJSON($log);
    }
}