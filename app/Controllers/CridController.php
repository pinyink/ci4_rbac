<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\Tema;
use CodeIgniter\Database\RawSql;
use App\Models\CridModel;


class CridController extends BaseController
{
    private $tema;
    private $cridModel;

    function __construct()
    {
        helper(['form', ]);
        $this->tema = new Tema();
        $this->cridModel = new CridModel();
    }

    public function index()
    {
        $this->tema->setJudul('Crid');
        $this->tema->loadTema('/crid');
    }

    public function ajaxList()
    {
        $this->cridModel->setRequest($this->request);
        $lists = $this->cridModel->getDatatables();
        $data = [];
        $no = $this->request->getPost("start");
        foreach ($lists as $list) {
            $no++;
            $row = [];
            $id = $list->id;
            $aksi = '<a href="javascript:;" class="" data-toggle="tooltip" data-placement="top" title="Lihat Data" onclick="lihat_data('.$id.')"><i class="fa fa-search"></i></a>';
            if(enforce(1, 3)) {
                $aksi .= '<a href="javascript:;" class="ml-2" data-toggle="tooltip" data-placement="top" title="Edit Data" onclick="edit_data('.$id.')"><i class="fa fa-edit"></i></a>';
            }

            if(enforce(1, 4)) {
                $aksi .= '<a href="javascript:;" class="text-danger ml-2" data-toggle="tooltip" data-placement="top" title="Delete Data" onclick="delete_data('.$id.')"><i class="fa fa-trash"></i></a>';
            }
            $action = $aksi;
            
            $row[] = $action;
            $row[] = $no;
			$row[] = $list->table;
			$row[] = $list->namespace;
			$row[] = $list->title;
			$row[] = $list->primary_key;
			$row[] = $list->v_created_at;
			$row[] = $list->v_updated_at;
			$row[] = $list->v_deleted_at;
            $data[] = $row;
        }
        $output = [
                "draw" => $this->request->getPost('draw'),
                "recordsTotal" => $this->cridModel->countAll(),
                "recordsFiltered" => $this->cridModel->countFiltered(),
                "data" => $data
            ];
        echo json_encode($output);
    }

    public function saveData()
    {
        $method = $this->request->getPost('method');
        

        $validation = [
            'val_table' => 'required','val_namespace' => 'required','val_title' => 'required','val_primary_key' => 'required','val_v_created_at' => 'required','val_v_updated_at' => 'required','val_v_deleted_at' => 'required',
        ];

        
        $validated = $this->validate($validation);
        if ($validated === false) {
            $errors = $this->validator->getErrors();
            $message = '<ul>';
            foreach ($errors as $key => $value) {
                $message .= '<li>'.$value.'</li>';
            }
            
            $message .= '</ul>';

            $log['errorCode'] = 2;
            $log['errorMessage'] = $message;
            return $this->response->setJSON($log);
        }

        $id = $this->request->getPost('id');
		$data['table'] = $this->request->getPost('val_table');
		$data['namespace'] = $this->request->getPost('val_namespace');
		$data['title'] = $this->request->getPost('val_title');
		$data['primary_key'] = $this->request->getPost('val_primary_key');
		$data['v_created_at'] = $this->request->getPost('val_v_created_at');
		$data['v_updated_at'] = $this->request->getPost('val_v_updated_at');
		$data['v_deleted_at'] = $this->request->getPost('val_v_deleted_at');

        if ($method == 'save') {
            $this->cridModel->insert($data);
            $log['errorCode'] = 1;
            $log['errorMessage'] = 'Simpan Data Berhasil';
            $log['errorType'] = 'success';
            return $this->response->setJSON($log);
        } else {
            $this->cridModel->update($id, $data);
            $log['errorCode'] = 1;
            $log['errorMessage'] = 'Update Data Berhasil';
            $log['errorType'] = 'success';
            return $this->response->setJSON($log);
        }
    }

    public function getData($id)
    {
        $query = $this->cridModel->select("id, table, namespace, title, primary_key, v_created_at, v_updated_at, v_deleted_at")->find($id);
        $data['id'] = $query['id'];
		$data['table'] = $query['table'];
		$data['namespace'] = $query['namespace'];
		$data['title'] = $query['title'];
		$data['primary_key'] = $query['primary_key'];
		$data['v_created_at'] = $query['v_created_at'];
		$data['v_updated_at'] = $query['v_updated_at'];
		$data['v_deleted_at'] = $query['v_deleted_at'];
        return $this->response->setJSON($data);
    }

    public function deleteData($id)
    {
        $query = $this->cridModel->delete($id);
        if ($query) {
            $log['errorCode'] = 1;
            $log['errorMessage'] = 'Delete Data Berhasil';
            $log['errorType'] = 'success';
            return $this->response->setJSON($log);
        } else {
            $log['errorCode'] = 2;
            $log['errorMessage'] = 'Delete Data Gagal';
            $log['errorType'] = 'warning';
            return $this->response->setJSON($log);
        }
    }

	public function tableExist()
    {
        $cridModel = new CridModel();
        $id = $this->request->getPost('id');
        $table = $this->request->getPost('table');
        $query = $cridModel->where(['id !=' => $id, 'table' => $table])->first();
        if (!empty($query)) {
            return $this->response->setJSON(false);
        }
        return $this->response->setJSON(true);
    }
}
