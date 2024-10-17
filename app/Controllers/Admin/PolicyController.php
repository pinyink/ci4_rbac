<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\Tema;
use App\Models\Admin\MenuAksesModel;
use App\Models\Admin\MenuModel;
use CodeIgniter\Database\RawSql;
use App\Models\Admin\PolicyModel;


class PolicyController extends BaseController
{
    private $tema;

    function __construct()
    {
        helper(['form']);
        helper(['Permission_helper']);
        $this->tema = new Tema();
    }

    public function index()
    {
        $this->tema->setJudul('Policy');
        $this->tema->loadTema('/admin/policy');
    }

    public function ajaxList()
    {
        $policyModel = new PolicyModel();
        $policyModel->setRequest($this->request);
        $lists = $policyModel->getDatatables();
        $data = [];
        $no = $this->request->getPost("start");
        foreach ($lists as $list) {
            $no++;
            $row = [];
            $id = $list->policy_id;
            $url = base_url('admin/policy/menuList/'.$id);

            $aksi = '<a href="javascript:;" class="" data-toggle="tooltip" data-placement="top" title="Lihat Data" onclick="lihat_data(\''.$id.'\')"><i class="fa fa-search"></i></a>';
            $aksi .= '<a href="javascript:;" class="ml-2" data-toggle="tooltip" data-placement="top" title="Edit Data" onclick="edit_data(\''.$id.'\')"><i class="fa fa-edit"></i></a>';
            $aksi .= '<a href="javascript:;" class="text-danger ml-2" data-toggle="tooltip" data-placement="top" title="Delete Data" onclick="delete_data(\''.$id.'\')"><i class="fa fa-trash"></i></a>';
            $aksi .= '<a href="'.$url.'" title="Setting Menu" class="ml-2"><i class="fa fa-list"></i></a>';

            $action = $aksi;
            
            $row[] = $action;
            $row[] = $no;
			$row[] = $list->policy_id;
			$row[] = $list->policy_desc;
            $data[] = $row;
        }
        $output = [
                "draw" => $this->request->getPost('draw'),
                "recordsTotal" => $policyModel->countAll(),
                "recordsFiltered" => $policyModel->countFiltered(),
                "data" => $data
            ];
        echo json_encode($output);
    }

    public function saveData()
    {
        $policyModel = new PolicyModel();

        $method = $this->request->getPost('method');
        

        $validation = [
            'val_policy_id' => 'required','val_policy_desc' => 'required',
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

        $id = $this->request->getPost('policy_id');
		$data['policy_id'] = $this->request->getPost('val_policy_id');
		$data['policy_desc'] = $this->request->getPost('val_policy_desc');

        if ($method == 'save') {
            $policyModel->insert($data);
            $log['errorCode'] = 1;
            $log['errorMessage'] = 'Simpan Data Berhasil';
            $log['errorType'] = 'success';
            return $this->response->setJSON($log);
        } else {
            $policyModel->update($id, $data);
            $log['errorCode'] = 1;
            $log['errorMessage'] = 'Update Data Berhasil';
            $log['errorType'] = 'success';
            return $this->response->setJSON($log);
        }
    }

    public function getData($id)
    {
        $policyModel = new PolicyModel();
        $query = $policyModel->select("policy_id, policy_id, policy_desc")->find($id);
        return $this->response->setJSON($query);
    }

    public function deleteData($id)
    {
        $policyModel = new PolicyModel();
        $query = $policyModel->delete($id);
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

    public function policyidExist()
    {
        $policyModel = new PolicyModel();
        $policy_id = $this->request->getPost('policy_id');
        $policy_id = $this->request->getPost('policy_id');
        $query = $policyModel->where(['policy_id !=' => $policy_id, 'policy_id' => $policy_id])->first();
        if (!empty($query)) {
            return $this->response->setJSON(false);
        }
        return $this->response->setJSON(true);
    }

    public function menuList($policyId)
    {
        $menuModel = new MenuModel();
        $menuAksesModel = new MenuAksesModel();

        $data = array();
        $allMenu = $menuModel->findAll();
        $menu = $menuAksesModel->orderBy('menu_akses_id', 'asc')->findAll();
        foreach ($allMenu as $key => $vAllMenu) {
            foreach ($menu as $key => $vMenu) {
                if ($vAllMenu['menu_id'] == $vMenu['menu_id']) {
                    $array = [
                        'akses_id' => $vMenu['akses_id'],
                        'menu_akses_id' => $vMenu['menu_akses_id'],
                        'menu_akses_desc' => $vMenu['menu_akses_desc'],
                        'menu_id' => $vMenu['menu_id']
                    ];
                    if (hasPolicy($policyId, $vMenu['menu_id'], $vMenu['menu_akses_id'])) {
                        $array['check'] = 'Y';
                    } else {
                        $array['check'] = 'N';
                    }
                    array_push($data, $array);
                }
            }
        }
        $tema = new Tema();
        $tema->loadTema('admin/policy/menu_list', ['data' => $data, 'menu' => $allMenu, 'policyId' => $policyId]);
    }

    public function saveSubMenu()
    {
        $db = \Config\Database::connect();
        $request = \Config\Services::request();
        $menuDesc = $request->getPost('nama_menu');
        $menuId = $request->getPost('menu_id');
        $menuAksesModel = new MenuAksesModel();
        $lastMenuAkses = $menuAksesModel->orderBy('menu_akses_id', 'desc')->where(['menu_id' => $menuId])->limit(1)->first();
        $menuAksesId = $lastMenuAkses->menu_akses_id + 1;
        $dataInsert = [
            'menu_akses_id' => $menuAksesId,
            'menu_id' => $menuId,
            'menu_akses_desc' => $menuDesc
        ];
        $queryInsert = $menuAksesModel->insert($dataInsert);
        $aksesId = $db->insertID();
        $dataInsert['akses_id'] = $aksesId;
        $log['errorCode'] = 1;
        $log['errorMessage'] = 'Tambah Menu Akses Berhasil';
        $log['errorType'] = 'success';
        $log['data'] = $dataInsert;
        $response = \Config\Services::response();
        return $response->setJSON($log);
    }

    public function addPolicy()
    {
        $log = [];
        $aksesId = $this->req->getPost('akses_id');
        $policy_id = $this->req->getPost('policy_id');
        $menuAksesModel = new MenuAksesModel();
        $query = $menuAksesModel->find($aksesId);
        addPolicy($policy_id, $query['menu_id'], $query['menu_akses_id']);
        $log['errorCode'] = 1;
        $log['errorMessage'] = 'Tambah Akses Menu Berhasil';
        $log['errorType'] = 'success';

        return $this->response->setJSON($log);
    }

    public function removePolicy()
    {
        $log = [];
        $aksesId = $this->req->getPost('akses_id');
        $policy_id = $this->req->getPost('policy_id');
        $menuAksesModel = new MenuAksesModel();
        $query = $menuAksesModel->find($aksesId);
        removePolicy($policy_id, $query['menu_id'], $query['menu_akses_id']);
        $log['errorCode'] = 1;
        $log['errorMessage'] = 'Hapus Akses Menu Berhasil';
        $log['errorType'] = 'success';

        return $this->response->setJSON($log);
    }
}
