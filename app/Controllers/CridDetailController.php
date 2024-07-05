<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\Tema;
use CodeIgniter\Database\RawSql;
use App\Models\CridDetailModel;
use App\Models\CridModel;

class CridDetailController extends BaseController
{
    private $tema;
    private $cridDetailModel;

    function __construct()
    {
        helper(['form', 'Permission_helper']);
        $this->tema = new Tema();
        $this->cridDetailModel = new CridDetailModel();
    }

    public function index()
    {
        $cridId = $this->request->getGet('id');
        $cridModel = new CridModel();
        $data['crid'] = $cridModel->find($cridId);

        $cridModel = new CridModel();
        $data['table'] = $cridModel->findAll();

        $this->tema->setJudul('Crid Detail');
        $this->tema->loadTema('/criddetail', $data);
    }

    public function ajaxList()
    {
        $cridId = $this->request->getGet('id');
        $this->cridDetailModel->setWhere(['a.crid_id' =>$cridId]);
        $this->cridDetailModel->setRequest($this->request);
        $lists = $this->cridDetailModel->getDatatables();
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
			$row[] = $list->name_field;
			$row[] = $list->name_alias;
			$row[] = $list->name_type;
            $data[] = $row;
        }
        $output = [
                "draw" => $this->request->getPost('draw'),
                "recordsTotal" => $this->cridDetailModel->countAll(),
                "recordsFiltered" => $this->cridDetailModel->countFiltered(),
                "data" => $data
            ];
        echo json_encode($output);
    }

    public function saveData()
    {
        $method = $this->request->getPost('method');
        

        $validation = [
            'val_crid_id' => 'required','val_name_field' => 'required','val_name_alias' => 'required','val_name_type' => 'required',
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
		$data['crid_id'] = $this->request->getPost('val_crid_id');
		$data['name_field'] = $this->request->getPost('val_name_field');
		$data['name_alias'] = $this->request->getPost('val_name_alias');
		$data['name_type'] = $this->request->getPost('val_name_type');
		$data['field_form'] = $this->request->getPost('val_field_form');
		$data['field_database'] = $this->request->getPost('val_field_database');
		$data['field_required'] = $this->request->getPost('val_field_required');
		$data['field_settings'] = $this->request->getPost('val_field_settings');
		$data['field_min'] = $this->request->getPost('val_field_min');
		$data['field_max'] = $this->request->getPost('val_field_max');
        $data['field_unique'] = $this->request->getPost('val_field_unique');

        $array = [
            'join_table' => $this->request->getPost('val_join_table'),
            'join_field' => $this->request->getPost('val_join_field'),
        ];
        $data['field_settings'] = json_encode($array);

        if ($method == 'save') {
            $this->cridDetailModel->insert($data);
            $log['errorCode'] = 1;
            $log['errorMessage'] = 'Simpan Data Berhasil';
            $log['errorType'] = 'success';
            return $this->response->setJSON($log);
        } else {
            $this->cridDetailModel->update($id, $data);
            $log['errorCode'] = 1;
            $log['errorMessage'] = 'Update Data Berhasil';
            $log['errorType'] = 'success';
            return $this->response->setJSON($log);
        }
    }

    public function getData($id)
    {
        $query = $this->cridDetailModel->find($id);
        $setting = json_decode($query['field_settings']);
        foreach ($setting as $key => $value) {
            $query[$key] = $value;
        }
        return $this->response->setJSON($query);
    }

    public function deleteData($id)
    {
        $query = $this->cridDetailModel->delete($id);
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

	public function namefieldExist()
    {
        $cridDetailModel = new CridDetailModel();
        $id = $this->request->getPost('id');
        $crid_id = $this->request->getPost('crid_id');
        $name_field = $this->request->getPost('name_field');
        $query = $cridDetailModel->where(['id !=' => $id, 'name_field' => $name_field, 'crid_id' => $crid_id])->first();
        if (!empty($query)) {
            return $this->response->setJSON(false);
        }
        return $this->response->setJSON(true);
    }

    public function byCridId($cridId)
    {
        $query = $this->cridDetailModel->where('crid_id', $cridId)->findAll();
        return $this->response->setJSON($query);
    }
}
