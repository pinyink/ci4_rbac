<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\Tema;
use CodeIgniter\Database\RawSql;
use App\Models\Admin\MenuAksesModel;


class MenuAksesController extends BaseController
{
    private $tema;

    function __construct()
    {
        helper(['form']);
        $this->tema = new Tema();
    }

    public function index()
    {
        $this->tema->setJudul('Menu Akses');
        $this->tema->loadTema('/admin/menuakses');
    }

    public function ajaxList()
    {
        $menuAksesModel = new MenuAksesModel();
        $menuAksesModel->setRequest($this->request);
        $lists = $menuAksesModel->getDatatables();
        $data = [];
        $no = $this->request->getPost("start");
        foreach ($lists as $list) {
            $no++;
            $row = [];
            $id = $list->akses_id;
            $action = '<a href="javascript:;" class="" data-toggle="tooltip" data-placement="top" title="Edit Data" onclick="edit_data('.$id.')"><i class="fa fa-edit"></i></a><a href="javascript:;" class="text-red ml-2" data-toggle="tooltip" data-placement="top" title="Delete Data" onclick="delete_data('.$id.')"><i class="fa fa-trash"></i></a>';
            
            $row[] = $action;
            $row[] = $no;
			$row[] = $list->menu_akses_id;
			$row[] = $list->menu_akses_desc;
			$row[] = $list->menu_id;
            $data[] = $row;
        }
        $output = [
                "draw" => $this->request->getPost('draw'),
                "recordsTotal" => $menuAksesModel->countAll(),
                "recordsFiltered" => $menuAksesModel->countFiltered(),
                "data" => $data
            ];
        echo json_encode($output);
    }

    public function saveData()
    {
        $menuAksesModel = new MenuAksesModel();

        $method = $this->request->getPost('method');
        

        $validation = [
            'val_menu_akses_id' => 'required','val_menu_akses_desc' => 'required','val_menu_id' => 'required',
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

        $id = $this->request->getPost('akses_id');
		$data['menu_akses_id'] = $this->request->getPost('val_menu_akses_id');
		$data['menu_akses_desc'] = $this->request->getPost('val_menu_akses_desc');
		$data['menu_id'] = $this->request->getPost('val_menu_id');

        if ($method == 'save') {
            $menuAksesModel->insert($data);
            $log['errorCode'] = 1;
            $log['errorMessage'] = 'Simpan Data Berhasil';
            $log['errorType'] = 'success';
            return $this->response->setJSON($log);
        } else {
            $menuAksesModel->update($id, $data);
            $log['errorCode'] = 1;
            $log['errorMessage'] = 'Update Data Berhasil';
            $log['errorType'] = 'success';
            return $this->response->setJSON($log);
        }
    }

    public function getData($id)
    {
        $menuAksesModel = new MenuAksesModel();
        $query = $menuAksesModel->select('akses_id, menu_akses_id, menu_akses_desc, menu_id')->find($id);
        return $this->response->setJSON($query);
    }

    public function deleteData($id)
    {
        $menuAksesModel = new MenuAksesModel();
        $query = $menuAksesModel->delete($id);
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

    
}
