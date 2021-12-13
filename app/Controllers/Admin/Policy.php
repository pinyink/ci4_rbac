<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\Tema;
use App\Models\Admin\MenuAksesModel;
use App\Models\Admin\MenuModel;
use App\Models\Admin\PolicyModel;
use App\Models\Admin\UserModel;

class Policy extends BaseController
{
    public function __construct()
    {
        helper(['Permission_helper']);
    }
    
    public function index()
    {
        $tema = new Tema();
        $tema->loadTema('admin/policy/view');
    }

    public function ajaxList()
    {
        $request = \Config\Services::request();
        $this->policyModel = new PolicyModel($request);
        if ($request->isAJAX()) {
            $lists = $this->policyModel->get_datatables();
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                $no++;
                $row = [];
                $id = $list->policy_id;
                $url = base_url('admin/policy/menuList/'.$id);
                $action = '<a href="javascript:;" onclick="editData(' . $id . ')" title="Edit Data"><i class="fa fa-edit"></i></a>&nbsp;<a href="javascript:;" onclick="user(' . $id . ')" title="setting user"><i class="fa fa-user"></i></a>&nbsp;<a href="'.$url.'" title="Setting Menu"><i class="fa fa-list"></i></a>';
                $row[] = $action;
                $row[] = $no;
                $row[] = $list->policy_desc;
                $data[] = $row;
            }
            $output = [
                "draw" => $request->getPost('draw'),
                "recordsTotal" => $this->policyModel->count_all(),
                "recordsFiltered" => $this->policyModel->count_filtered(),
                "data" => $data
            ];
            echo json_encode($output);
        }
    }

    public function saveData()
    {
        $log = [];
        $request = \Config\Services::request();
        $response = \Config\Services::response();
        $desc = $request->getPost('descPolicy');
        $data = [
            'policy_desc' => $desc,
            'policy_created_at' => date('Y-m-d H:i:s')
        ];
        $this->policyModel = new PolicyModel($request);
        $this->policyModel->saveData($data);
        $log['errorCode'] = 1;
        $log['errorMessage'] = 'Update Data Berhasil';
        $log['errorType'] = 'success';

        return $response->setJSON($log);
    }

    public function getData($id)
    {
        $request = \Config\Services::request();
        $response = \Config\Services::response();
        $policy = new PolicyModel($request);
        $query = $policy->find($id);
        return $response->setJSON($query);
    }

    public function updateData()
    {
        $log = [];
        $request = \Config\Services::request();
        $response = \Config\Services::response();
        $id = $request->getPost('id');
        $desc = $request->getPost('descPolicy');
        $data = [
            'policy_desc' => $desc,
            'policy_updated_at' => date('Y-m-d H:i:s')
        ];
        $this->policyModel = new PolicyModel($request);
        $this->policyModel->update($id, $data);
        $log['errorCode'] = 1;
        $log['errorMessage'] = 'Update Data Berhasil';
        $log['errorType'] = 'success';

        return $response->setJSON($log);
    }

    public function userList()
    {
        $request = \Config\Services::request();
        $this->userModel = new UserModel($request);
        $lists = $this->userModel->get_datatables();
        $data = [];
        $no = $this->req->getPost("start");
        $policy_id = $this->req->getPost('policy');
        foreach ($lists as $list) {
            $no++;
            $row = [];
            $id = $list->user_id;
            $action = '';
            if (hasRoleForUser($id, $policy_id)) {
                $action = '<input type="checkbox" value="1" onclick="remove_role(' . $id . ')" checked>';
            } else {
                $action = '<input type="checkbox" value="1" onclick="add_role(' . $id . ')">';
            }
            $aktif = '';
            if ($list->user_aktif == 1) {
            }
            $row[] = $action;
            $row[] = $no;
            $row[] = $list->user_username;
            $data[] = $row;
        }
        $output = [
            "draw" => $request->getPost('draw'),
            "recordsTotal" => $this->userModel->count_all(),
            "recordsFiltered" => $this->userModel->count_filtered(),
            "data" => $data
        ];
        echo json_encode($output);
    }

    public function addRole()
    {
        $log = [];
        $request = \Config\Services::request();
        $response = \Config\Services::response();
        $user_id = $request->getPost('user_id');
        $policy_id = $request->getPost('policy_id');
        addRoleForUser($user_id, $policy_id);
        $log['errorCode'] = 1;
        $log['errorMessage'] = 'Tambah User Berhasil';
        $log['errorType'] = 'success';

        return $response->setJSON($log);
    }

    public function removeRole()
    {
        $log = [];
        $user_id = $this->req->getPost('user_id');
        $policy_id = $this->req->getPost('policy_id');
        deleteRoleForUser($user_id, $policy_id);
        $log['errorCode'] = 1;
        $log['errorMessage'] = 'Delete User Berhasil';
        $log['errorType'] = 'success';

        return $this->response->setJSON($log);
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
                if ($vAllMenu->menu_id == $vMenu->menu_id) {
                    $array = [
                        'akses_id' => $vMenu->akses_id,
                        'menu_akses_id' => $vMenu->menu_akses_id,
                        'menu_akses_desc' => $vMenu->menu_akses_desc,
                        'menu_id' => $vMenu->menu_id
                    ];
                    if (hasPolicy($policyId, $vMenu->menu_id, $vMenu->menu_akses_id)) {
                        $array['check'] = 'Y';
                    } else {
                        $array['check'] = 'N';
                    }
                    array_push($data, $array);
                }
            }
        }
        $tema = new Tema();
        $tema->loadTema('admin/policy/menu_list', ['data' => $data, 'menu' => $allMenu]);
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
        $menu_id = $this->req->getPost('menu_id');
        $menu_akses_id = $this->req->getPost('menu_akses_id');
        $policy_id = $this->req->getPost('policy_id');
        addPolicy($policy_id, $menu_id, $menu_akses_id);
        $log['errorCode'] = 1;
        $log['errorMessage'] = 'Tambah Akses Menu Berhasil';
        $log['errorType'] = 'success';

        return $this->response->setJSON($log);
    }

    public function removePolicy()
    {
        $log = [];
        $menu_id = $this->req->getPost('menu_id');
        $menu_akses_id = $this->req->getPost('menu_akses_id');
        $policy_id = $this->req->getPost('policy_id');
        removePolicy($policy_id, $menu_id, $menu_akses_id);
        $log['errorCode'] = 1;
        $log['errorMessage'] = 'Hapus Akses Menu Berhasil';
        $log['errorType'] = 'success';

        return $this->response->setJSON($log);
    }
}
