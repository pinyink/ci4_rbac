<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\CreateController;
use App\Libraries\CreateControllerLib;
use App\Libraries\CreateModelLib;
use App\Libraries\CreateRouteLib;
use App\Libraries\CreateViewLib;
use App\Libraries\Tema;
use App\Models\CridDetailModel;
use CodeIgniter\Database\RawSql;
use App\Models\CridModel;


class CridController extends BaseController
{
    private $tema;
    private $cridModel;
    private $cridDetailModel;
    private $createRouteLib;
    private $createModelLib;
    private $createControllerLib;
    private $createViewLib;

    function __construct()
    {
        helper(['form', 'Permission']);
        $this->tema = new Tema();
        $this->cridModel = new CridModel();
        $this->cridDetailModel = new CridDetailModel();
        $this->createRouteLib = new CreateRouteLib();
        $this->createModelLib = new CreateModelLib();
        $this->createControllerLib = new CreateControllerLib();
        $this->createViewLib = new CreateViewLib();
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
            $aksi = '';
            if(enforce(1, 3)) {
                $aksi .= '<a href="javascript:;" class="ml-2" data-toggle="tooltip" data-placement="top" title="Edit Data" onclick="edit_data('.$id.')"><i class="fa fa-edit"></i></a>';
            }

            if(enforce(1, 4)) {
                $aksi .= '<a href="javascript:;" class="text-danger ml-2" data-toggle="tooltip" data-placement="top" title="Delete Data" onclick="delete_data('.$id.')"><i class="fa fa-trash"></i></a>';
            }
            $aksi .= '<a href='.base_url('criddetail?id='.$id).' class="text-success ml-2" data-toggle="tooltip" data-placement="top" title="Detail Crid"><i class="fa fa-list"></i></a>';

            $action = $aksi;
            
            $row[] = $action;
            $row[] = $no;
			$row[] = $list->table;
            $row[] = $list->routename;
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
            'val_table' => 'required','val_title' => 'required','val_primary_key' => 'required','val_v_created_at' => 'required','val_v_updated_at' => 'required','val_v_deleted_at' => 'required',
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
		$data['routename'] = $this->request->getPost('val_routename');
		$data['rbac'] = $this->request->getPost('val_rbac');

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
        $query = $this->cridModel->select("id, table, namespace, title, primary_key, v_created_at, v_updated_at, v_deleted_at, routename, rbac")->find($id);
        return $this->response->setJSON($query);
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

    public function generateCrud($id)
    {
        $table = $this->cridModel->find($id);
        $fields = $this->cridDetailModel->where(['crid_id' => $id])->findAll();

        $this->createRouteLib->setTable($table);
        $this->createRouteLib->setFields($fields);
        $route = $this->createRouteLib->generate();
        echo "<pre>$route</pre>";

        $this->createModelLib->setTable($table);
        $this->createModelLib->setFields($fields);
        $model = $this->createModelLib->generate();
        echo "<pre>$model</pre>";

        $this->createControllerLib->setTable($table);
        $this->createControllerLib->setFields($fields);
        $controller = $this->createControllerLib->generate();
        echo "<pre>$controller</pre>";

        $this->createViewLib->setTable($table);
        $this->createViewLib->setFields($fields);
        $viewIndex = $this->createViewLib->index();
        echo "<pre>$viewIndex</pre>";
    }
}
