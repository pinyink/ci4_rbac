<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\Tema;
use CodeIgniter\Database\RawSql;
use App\Models\Admin\UserModel;


class UserController extends BaseController
{
    private $tema;

    function __construct()
    {
        helper(['form']);
        $this->tema = new Tema();
    }

    public function index()
    {
        $this->tema->setJudul('User');
        $this->tema->loadTema('/admin/user');
    }

    public function ajaxList()
    {
        $userModel = new UserModel();
        $userModel->setRequest($this->request);
        $lists = $userModel->getDatatables();
        $data = [];
        $no = $this->request->getPost("start");
        foreach ($lists as $list) {
            $no++;
            $row = [];
            $id = $list->user_id;
            $action = '<a href="javascript:;" class="" data-toggle="tooltip" data-placement="top" title="Edit Data" onclick="edit_data('.$id.')"><i class="fa fa-edit"></i></a><a href="javascript:;" class="text-red ml-2" data-toggle="tooltip" data-placement="top" title="Delete Data" onclick="delete_data('.$id.')"><i class="fa fa-trash"></i></a>';
            
            $row[] = $action;
            $row[] = $no;
			$row[] = $list->user_username;
			$row[] = $list->user_superadmin == '1' ? 'Ya' : 'Tidak';
            $row[] = $list->user_aktif == '1' ? 'Ya' : 'Tidak';
			$row[] = $list->user_updated_at;
            $data[] = $row;
        }
        $output = [
                "draw" => $this->request->getPost('draw'),
                "recordsTotal" => $userModel->countAll(),
                "recordsFiltered" => $userModel->countFiltered(),
                "data" => $data
            ];
        echo json_encode($output);
    }

    public function saveData()
    {
        $userModel = new UserModel();

        $method = $this->request->getPost('method');
        $validation = [
            'val_user_username' => 'required','val_user_superadmin' => 'required',
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

        $id = $this->request->getPost('user_id');
		$data['user_username'] = $this->request->getPost('val_user_username');
        $password = $this->request->getPost('val_user_password') != null ? $this->request->getPost('val_user_password') : '';
        if ($password != '') {
            $data['user_password'] = password_hash($password, PASSWORD_BCRYPT);
        }
		$data['user_superadmin'] = $this->request->getPost('val_user_superadmin');
		$data['user_aktif'] = $this->request->getPost('val_user_aktif');

        if ($method == 'save') {
            $userModel->insert($data);
            $log['errorCode'] = 1;
            $log['errorMessage'] = 'Simpan Data Berhasil';
            $log['errorType'] = 'success';
            return $this->response->setJSON($log);
        } else {
            $userModel->update($id, $data);
            $log['errorCode'] = 1;
            $log['errorMessage'] = 'Update Data Berhasil';
            $log['errorType'] = 'success';
            return $this->response->setJSON($log);
        }
    }

    public function getData($id)
    {
        $userModel = new UserModel();
        $query = $userModel->select('user_id, user_username, user_password, user_superadmin, user_aktif')->find($id);
        return $this->response->setJSON($query);
    }

    public function deleteData($id)
    {
        $userModel = new UserModel();
        $query = $userModel->delete($id);
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

    public function userusernameExist()
    {
        $userModel = new UserModel();
        $user_id = $this->request->getPost('user_id');
        $user_username = $this->request->getPost('user_username');
        $query = $userModel->where(['user_id !=' => $user_id, 'user_username' => $user_username])->first();
        if (!empty($query)) {
            return $this->response->setJSON(false);
        }
        return $this->response->setJSON(true);
    }
}
