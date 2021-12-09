<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Admin\UserModel;
use App\Libraries\Tema;

class User extends BaseController
{
    public function __construct()
    {
        $this->tema = new Tema();
        helper(['Clean_string_helper']);
    }

    public function index()
    {
        $this->tema->loadTema('admin/user/view');
    }

    public function ajaxList()
    {
        $this->userModel = new UserModel($this->req);
        $lists = $this->userModel->get_datatables();
        $data = [];
        $no = $this->req->getPost("start");
        foreach ($lists as $list) {
            $no++;
            $row = [];
            $id = $list->user_id;
            $action = '<a href="javascript:;" title="reset password" onclick="reset_password(' . $id . ', \'' . $list->user_username . '\')"><i class="fa fa-key"></i></a>&nbsp;';
            $aktif = '';
            if (is_null($list->user_deleted_at)) {
                $aktif = '<span class="badge badge-primary">AKTIF</span>';
                $action .= '<a href="javascript:;" title="non aktifkan" class="text-danger" onclick="aktif_nonaktif(' . $id . ', 2, \'' . $list->user_username . '\')"><i class="fa fa-ban"></i></a>';
            } else {
                $aktif = '<span class="badge badge-danger">NON-AKTIF</span>';
                $action .= '<a href="javascript:;" title="aktifkan" class="text-primary" onclick="aktif_nonaktif(' . $id . ', 1, \'' . $list->user_username . '\')"><i class="fa fa-ban"></i></a>';
            }
            $row[] = $action;
            $row[] = $no;
            $row[] = $list->user_username;
            $row[] = !empty($list->user_updated_at) ? date('d-m-Y H:i:s', strtotime($list->user_updated_at)) : '-';
            $row[] = $aktif;
            $data[] = $row;
        }
        $output = [
                "draw" => $this->req->getPost('draw'),
                "recordsTotal" => $this->userModel->count_all(),
                "recordsFiltered" => $this->userModel->count_filtered(),
                "data" => $data
            ];
        echo json_encode($output);
    }

    public function resetPassword()
    {
        $log = [];
        
        $id = $this->req->getPost('id');
        $data = [
            'user_password' => password_hash('12345', PASSWORD_BCRYPT),
            'user_updated_at' => date('Y-m-d H:i:s')
        ];
        $this->userModel = new UserModel($this->req);
        $this->userModel->update($id, $data);
        $log['errorCode'] = 1;
        $log['errorMessage'] = 'Update Data Berhasil';
        $log['errorType'] = 'success';
        return $this->response->setJSON($log);
    }

    public function nonAktifUser()
    {
        $log = [];
        $id = $this->req->getPost('id');
        $aktif = $this->req->getPost('aktif');
        $data = [
            'user_aktif' => $aktif,
            'user_deleted_at' => null
        ];
        $this->userModel = new UserModel($this->req);
        if ($aktif == 2) {
            $this->userModel->delete($id);
        } else {
            $this->userModel->update($id, $data);
        }
        
        $log['errorCode'] = 1;
        $log['errorMessage'] = 'Update Data Berhasil';
        $log['errorType'] = 'success';
        
        return $this->response->setJSON($log);
    }

    public function saveData()
    {
        $log = [];
        $username = $this->req->getPost('username');
        $data = [
            'user_username' => clean($username),
            'user_password' => password_hash('12345', PASSWORD_BCRYPT)
        ];
        $this->userModel = new UserModel($this->req);
        $this->userModel->insert($data);
        $log['errorCode'] = 1;
        $log['errorMessage'] = 'Simpan Data Berhasil';
        $log['errorType'] = 'success';
        
        return $this->response->setJSON($log);
    }
}
