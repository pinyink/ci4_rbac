<?php

namespace App\Controllers\Setting;

use App\Controllers\BaseController;
use App\Libraries\Tema;
use App\Models\Admin\UserModel;
use App\Models\LoginModel;
use App\Models\Setting\ProfilModel;
use App\Models\LoginModels;

class Profil extends BaseController
{
    private $session;

    public function __construct()
    {
        $this->session = session();
    }

    public function index()
    {
        $tema = new Tema();
        $tema->loadTema('setting/profil');
    }

    public function getData()
    {
        $log = [];
        $profilModel = new ProfilModel();
        $data = $profilModel->getData(['a.user_id' => session('user_id')])->getRowArray();
        if (!empty($data)) {
            $log['errorCode'] = 1;
            $log['errorMessage'] = 'data ada';
            $log['data'] = [
                'profil_firstname' => $data['profil_firstname'],
                'profil_lastname' => $data['profil_lastname'],
                'profil_email' => $data['profil_email'],
                'profil_bio' => $data['profil_bio']
            ];
            if (empty($data['profil_image'])) {
                $log['data']['profil_image'] = base_url() . '/assets/admincast/dist/assets/img/image.jpg';
            } else {
                $log['data']['profil_image'] = base_url() . '/' . $data['profil_image'];
            }
            $dataSession = [
                'profil_image' => $log['data']['profil_image']
            ];
            $this->session->set($dataSession);
        } else {
            $log['errorCode'] = 2;
            $log['errorMessage'] = 'data kosong';
            $log['data'] = [
                'profil_firstname' => '',
                'profil_lastname' => '',
                'profil_email' => '',
                'profil_bio' => '',
                'profil_image' => ''
            ];
        }

        return $this->response->setJSON($log);
    }

    public function update()
    {
        $log = [];
        $firstName = $this->request->getPost('firstName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $lastName = $this->request->getPost('lastName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = $this->request->getPost('email');
        $bio = $this->request->getPost('bio', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $id = session('user_id');
        $profilModel = new ProfilModel();
        $cek = $profilModel->getData(['a.user_id' => $id])->getRowArray();
        $data = [
            'profil_firstname' => $firstName,
            'profil_lastname' => $lastName,
            'profil_email' => $email,
            'profil_bio' => $bio
        ];
        if (empty($cek)) {
            $data['user_id'] = $id;
            $profilModel->insertData($data);
        } else {
            $profilModel->updateData($id, $data);
        }
        $log['errorCode'] = 1;
        $log['errorMessage'] = 'Update Data Berhasil';
        $log['errorType'] = 'success';

        return $this->response->setJSON($log);
    }

    public function updatePassword()
    {
        $log = [];
        $oldPassword = $this->request->getPost('oldPassword');
        $newPassword = $this->request->getPost('newPassword');
        $retypePassword = $this->request->getPost('retypePassword');

        $validation = \Config\Services::validation();
        $validation->setRules([
            'newPassword' => [
                'label'  => 'Password',
                'rules'  => 'required|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[#$@!%&*?])[A-Za-z\d#$@!%&*?]{8,30}$/]|min_length[8]',
                'errors' => [
                    'min_length' => '{field} Minilam 8 Digit',
                    'regex_match' => '{field} Terdiri dari huruf kapital, huruf kecil, angka dan karakter serta Minimal 8 Digit'
                ],
            ],
        ]);

        $request    = \Config\Services::request();
        if ($validation->withRequest($request)->run()) {
            $loginModel = new LoginModel($this->request);
            $id = session('user_id');
            $query = $loginModel->getData(['a.user_id' => $id, 'user_deleted_at' => null])->getRow();
            if (password_verify($oldPassword, $query->user_password)) {
                if ($newPassword == $retypePassword) {
                    $dataUpdate = [
                        'user_password' => password_hash($newPassword, PASSWORD_BCRYPT)
                    ];
                    $userModel = new UserModel();
                    $queryUpdatePassword = $userModel->update($id, $dataUpdate);
                    $log['errorCode'] = 1;
                    $log['errorMessage'] = 'Password Baru Berhasil Disimpan';
                    $log['errorType'] = 'success';
                } else {
                    $log['errorCode'] = 2;
                    $log['errorMessage'] = 'Password Baru Salah';
                    $log['errorType'] = 'error';
                }
            } else {
                $log['errorCode'] = 2;
                $log['errorMessage'] = 'Password Lama Salah';
                $log['errorType'] = 'error';
            }
            return $this->response->setJSON($log);
        } else {
            $log['errorCode'] = 2;
            $log['errorMessage'] = 'Password Terdiri dari huruf kapital, huruf kecil, angka dan karakter serta Minimal 8 Digit';
            $log['errorType'] = 'error';
            return $this->response->setJSON($log);
        }
    }

    public function updateFoto()
    {
        $log = [];
        $img = $this->request->getFile('inputFile');
        $validated = $this->validate([
            'inputFile' => 'uploaded[inputFile]|mime_in[inputFile,image/jpg,image/jpeg,image/gif,image/png]|max_size[inputFile,4096]'
        ]);
        if ($validated === false) {
            $type = $img->getClientMimeType();
            $log['errorCode'] = 2;
            $log['errorMessage'] = $img->getErrorString() . '(' . $img->getError() . ' Type File ' . $type . ' )';
        } else {
            $th = date('Y') . '/' . date('m');
            $_dir = 'upload/img/profil/' . $th;
            $dir = ROOTPATH . '/public/upload/img/profil/' . $th;
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
            $newName = $img->getRandomName();
            $img->move($dir, $newName);

            $profilModel = new ProfilModel();
            $cek = $profilModel->getData(['a.user_id' => session('user_id')])->getRowArray();
            $data = [
                'profil_image' => $_dir . '/' . $img->getName()
            ];
            if (empty($cek)) {
                $data['user_id'] = session('user_id');
                $profilModel->insertData($data);
            } else {
                $profilModel->updateData(session('user_id'), $data);
            }
            $log['errorCode'] = 1;
            $log['errorMessage'] = 'Update Data Berhasil';
            $log['errorType'] = 'success';
        }

        return $this->response->setJSON($log);
    }

    public function coba()
    {
        echo session('user_id');
    }
}
