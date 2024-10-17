<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\Tema;
use App\Models\Admin\PolicyModel;
use CodeIgniter\Database\RawSql;
use App\Models\Admin\UserModel;
use App\Models\Setting\ProfilModel;

class UserController extends BaseController
{
    private $tema;

    function __construct()
    {
        helper(['form', 'FormCustom']);
        $this->tema = new Tema();
    }

    public function index()
    {
        $policyModel = new PolicyModel();
        $data['policy'] = $policyModel->findAll();

        $this->tema->setJudul('User');
        $this->tema->loadTema('/admin/user', $data);
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
            $action = '<a href="javascript:;" class="" data-toggle="tooltip" data-placement="top" title="Edit Data" onclick="edit_data('.$id.')"><i class="fa fa-edit"></i></a><a href="javascript:;" class="text-danger ml-2" data-toggle="tooltip" data-placement="top" title="Delete Data" onclick="delete_data('.$id.')"><i class="fa fa-trash text-danger"></i></a> <a href="'.base_url('admin/user/profil/'.$id).'" class="text-red ml-2" data-toggle="tooltip" data-placement="top" title="Profil"><i class="fa fa-user-circle-o"></i></a>';
            
            $row[] = $action;
            $row[] = $no;
			$row[] = $list->user_username;
			$row[] = $list->user_superadmin == '1' ? 'Ya' : 'Tidak';
            $row[] = $list->user_aktif == '1' ? 'Ya' : 'Tidak';
            $row[] = $list->user_level;
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
        $data['user_level'] = $this->request->getPost('val_user_level');

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


        // insert penyuluh
        // $userModel = new UserModel();
        // $profilModel = new ProfilModel();

        // $db = db_connect();
        // $table = $db->table('gapoktan');
        // $table->select('penyuluh, penyuluh_hp');
        // $table->groupBy('penyuluh, penyuluh_hp');
        // $query = $table->get();
        // foreach ($query->getResultArray() as $key => $value) {
        //     $data['user_username'] = $value['penyuluh_hp'];
        //     $data['user_password'] = password_hash('12345', PASSWORD_BCRYPT);
        //     $data['user_superadmin'] = '2';
        //     $data['user_aktif'] = '1';
        //     $data['user_level'] = 'penyuluh';
        //     $queryUser = $userModel->insert($data);

        //     $dataProfil = [
        //         'user_id' => $queryUser,
        //         'profil_firstname' => $value['penyuluh'],
        //         'profil_nomor_hp' => $value['penyuluh_hp'],
        //         'profil_email' => null,
        //         'profil_bio' => null
        //     ];
        //     $dataProfil['profil_prop'] = 33;
        //     $dataProfil['profil_kab'] = 20;
        //     $profilModel->insertData($dataProfil);
        // }
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
