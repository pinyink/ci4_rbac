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
        $this->productModel->setRequest($this->request);
        $lists = $this->productModel->getDatatables();
        $data = [];
        $no = $this->request->getPost("start");
        foreach ($lists as $list) {
            $no++;
            $row = [];
            $id = $list->id;
            $aksi = '<a href="'.base_url('product/'.$id.'/detail').'" class="" data-toggle="tooltip" data-placement="top" title="Lihat Data"><i class="fa fa-search"></i></a>';
            if(enforce(4, 3)) {
                $aksi .= '<a href="'.base_url('product/'.$id.'/edit').'" class="ml-2" data-toggle="tooltip" data-placement="top" title="Edit Data" onclick="edit_data('.$id.')"><i class="fa fa-edit"></i></a>';
            }

            if(enforce(4, 4)) {
                $aksi .= '<a href="javascript:;" class="text-danger ml-2" data-toggle="tooltip" data-placement="top" title="Delete Data" onclick="delete_data('.$id.')"><i class="fa fa-trash"></i></a>';
            }
            $action = $aksi;
            
            $row[] = $action;
            $row[] = $no;
            $row[] = $list->nama;
			$row[] = 'Rp. '.number_format($list->harga, 0, ',', '.');
			$row[] = date('d-m-Y', strtotime($list->tanggal));
			$row[] = $list->deskripsi;
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
                'rules' => 'required|max_length[128]|alpha_numeric_space',
                'errors' => [
                    'required' => '{field} Harus di isi',
					'max_length' => '{field} Maksimal 128 Huruf',
					'alpha_numeric_space' => '{field} Hanya berupa huruf, angka dan spasi'
                ]
            ],
			'harga' => [
                'label' => 'Harga',
                'rules' => 'required|max_length[11]|numeric',
                'errors' => [
                    'required' => '{field} Harus di isi',
					'max_length' => '{field} Maksimal 11 Huruf',
					'numeric' => '{field} Hanya berupa angka'
                ]
            ],
			'tanggal' => [
                'label' => 'Tanggal',
                'rules' => 'required|valid_date[d-m-Y]',
                'errors' => [
                    'required' => '{field} Harus di isi',
					'valid_date' => '{field} Harus berupa tanggal dd-mm-yyyy'
                ]
            ],
			'deskripsi' => [
                'label' => 'Deskripsi Product',
                'rules' => 'required|alpha_numeric_punct',
                'errors' => [
                    'required' => '{field} Harus di isi',
					'alpha_numeric_punct' => '{field} Hanya berupa huruf, angka dan karakter tertentu'
                ]
            ],
			'dokumen' => [
                'label' => 'Dokumen Pdf',
                'rules' => '',
                'errors' => [
                    
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
        if (!empty($_FILES['foto']['name'])) {
            $rules['foto'] = [
                'label' => 'Foto Product',
                'rules' => 'uploaded[foto]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/gif,image/png,image/webp]|max_size[foto,2048]',
                'errors' => [
                    'max_size' => '{field} maksimal 2mb',
                    'mime_in' => '{field} hanya upload file png, jpeg, jpg',
                    'uploaded' => '{field} Tidak Sesuai',
                    'is_image' => '{field} hanya upload file png, jpeg, jpg'
                ]
            ];
        }
        $validation->setRules($rules);

        if ($validation->withRequest($request)->run()) {
            $validData = $validation->getValidated();
            $validData['harga'] = str_replace('.', '', $validData['harga']);
			$validData['tanggal'] = date('Y-m-d', strtotime($validData['tanggal']));
			$foto = $request->getFile('foto');
            if (!empty($_FILES['foto']['name'])) {
                $th = date('Y/m/d');
                $path = 'uploads/product/';
                $_dir = $path . $th;
                $dir = UPLOADPATH . $path . $th;
                if (!file_exists($dir)) {
                    mkdir($dir, 0777, true);
                }
                $newName = $foto->getRandomName();;
                $foto->move($dir, $newName);
                $validData['foto'] = $_dir.'/'.$newName;
            }
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