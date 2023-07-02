<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\LoginModel;
use App\Models\Setting\ProfilModel;

class Login extends Controller
{
    private $session;

    public function __construct()
    {
        $this->session = session();
    }

    public function index()
    {
        return view('vlogin');
    }

    public function loginAksi()
    {
        $request = service('request');
        $username = $request->getPost("username");
        $password = $request->getPost("password");
        $loginModel = new LoginModel($this->request);
        $query = $loginModel->getData(['user_username' => $username, 'user_deleted_at' => null])->getRow();
        // print_r($query);
        if (!empty($query)) {
            if (password_verify($password, $query->user_password)) {
                $profilModel = new ProfilModel();
                $data = $profilModel->getData(['a.user_id' => $query->user_id])->getRowArray();
                $dataSession = [
                    'user_id' => $query->user_id,
                    'user' => $query->user_username,
                    'level' => $query->user_superadmin,
                    'user_level' => $query->user_level
                ];
                if (!empty($data)) {
                    if (empty($data['profil_image'])) {
                        $dataSession['profil_image'] = base_url() . '/assets/admincast/dist/assets/img/image.jpg';
                    } else {
                        $dataSession['profil_image'] = base_url() . '/' . $data['profil_image'];
                    }
                    $dataSession['fullname'] = $data['profil_firstname'];
                } else {
                    $dataSession['profil_image'] = base_url() . '/assets/admincast/dist/assets/img/image.jpg';
                    $dataSession['fullname'] = '';
                }
                $this->session->set($dataSession);
                return redirect()->to('/home');
            } else {
                $this->session->setFlashdata('messageLogin', '<div class="alert alert-danger">Password Tidak Sama</div>');
                return redirect()->to('/login');
            }
        } else {
            $this->session->setFlashdata('messageLogin', '<div class="alert alert-danger">Username Tidak Ditemukan</div>');
            return redirect()->to('/login');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('messageLogin', '<div class="alert alert-success">Logout Success</div>');
    }
}
