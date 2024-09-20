<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\Tema;
use Hermawan\DataTables\DataTable;

use App\Models\ProductCategoriesModel;

class ProductCategoriesController extends BaseController
{
    private $tema;
    private $productCategoriesModel;

    function __construct()
    {
        helper(['form']);
        $this->tema = new Tema();
        $this->productCategoriesModel = new ProductCategoriesModel();
        
    }

    public function index()
    {
        $this->tema->setJudul('Product Kategories');
        $this->tema->loadTema('product_categories/index');
    }

	public function ajaxList()
    {
        $this->productCategoriesModel->select('id, nama')->where('deleted_at', null);

        return DataTable::of($this->productCategoriesModel)
            ->add('action', function($row){
                $btn = '<a href="'.base_url('product_categories/'.$row->id.'/detail').'" class="" data-toggle="tooltip" data-placement="top" title="Lihat Data"><i class="fa fa-search"></i></a>';
                if(enforce(1, 3)) {
                    $btn .= '<a href="'.base_url('product_categories/'.$row->id.'/edit').'" class="ml-2" data-toggle="tooltip" data-placement="top" title="Edit Data"><i class="fa fa-edit"></i></a>';
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
                'label' => 'Nama Kategories',
                'rules' => 'required|is_unique[product_categories.nama, id, '.$id.']|max_length[64]|alpha_numeric_space',
                'errors' => [
                    'required' => '{field} Harus di isi',
					'is_unique' => '{field} Sudah Ada, harap ketik yang lainnya',
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
            'url' => 'product_categories/save'
        ];
        $this->tema->setJudul('Tambah Product Kategories');
        $this->tema->loadTema('product_categories/tambah', $data);
    }

	public function editData($id){
        $query = $this->productCategoriesModel->detail(['a.id' => $id])->getRowArray();
        if(empty($query)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $data = [
            'button' => 'Simpan',
            'id' => $id,
            'method' => 'update',
            'url' => 'product_categories/update',
            'product_categories' => $query
        ];
        $this->tema->setJudul('Edit Product Kategories');
        $this->tema->loadTema('product_categories/edit', $data);
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
                $id = $this->productCategoriesModel->insert($validData);
                return redirect()->to('product_categories/'.$id.'/detail')->with('message', '<div class="alert alert-success">Simpan Data Berhasil</div>');
            } else {
                $this->productCategoriesModel->update($id, $validData);
                return redirect()->to('product_categories/'.$id.'/edit')->with('message', '<div class="alert alert-success">Update Data Berhasil</div>');
            }
        } else {
            if($method == 'save') {
                return redirect()->to('product_categories/tambah')->withInput();
            } else {
                return redirect()->to('product_categories/'.$id.'/edit')->withInput();
            }
        }
        
    }

	public function detailData($id){
        $query = $this->productCategoriesModel->detail(['a.id' => $id])->getRowArray();
        if(empty($query)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $data = [
            'product_categories' => $query
        ];
        $this->tema->setJudul('Detail Product Kategories');
        $this->tema->loadTema('product_categories/detail', $data);
    }

	public function deleteData($id){
        $query = $this->productCategoriesModel->find($id);
        if(empty($query)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $data = [
            'product_categories' => $query
        ];
        $delete = $this->productCategoriesModel->delete($id);
        if($delete) {
            $log['errorCode'] = 1;
        } else {
            $log['errorCode'] = 2;
        }
        return $this->response->setJSON($log);
    }
}