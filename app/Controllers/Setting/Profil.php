<?php

namespace App\Controllers\Setting;

use App\Controllers\BaseController;
use App\Libraries\Tema;
use App\Models\Setting\ProfilModel;

class Profil extends BaseController
{
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
        $firstName = $this->req->getPost('firstName');
        $lastName = $this->req->getPost('lastName');
        $email = $this->req->getPost('email');
        $bio = $this->req->getPost('bio');
        $id = $this->req->getPost('idSetting');
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

    public function updateFoto()
    {
        $log = [];
        $img = $this->req->getFile('inputFile');
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
