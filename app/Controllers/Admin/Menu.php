<?php
namespace App\Controllers\Admin;

use App\Libraries\Tema;
use CodeIgniter\Controller;
use App\Models\Admin\MenuAksesModel;
use App\Models\Admin\MenuModel;

class Menu extends Controller
{
    public function __construct()
    {
        helper(['Permission_helper']);
    }

    public function index()
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
                    array_push($data, $array);
                }
            }
        }

        $tema = new Tema();
        $tema->loadTema('admin/menu/view', ['data' => $data, 'menu' => $allMenu]);
    }

    public function saveMenu()
    {
        $request = \Config\Services::request();
        $db = \Config\Database::connect();
        $namaMenu = $request->getPost('namaMenu');
        $menuModel = new MenuModel();
        $queryInsert = $menuModel->insert(['menu_desc' => $namaMenu]);
        if ($queryInsert) {
            $log['errorCode'] = 1;
            $log['errorMessage'] = 'Simpan Menu Berhasil';
            $log['errorType'] = 'success';
            $log['menu_id'] = $db->insertID();
            $log['menu_desc'] = $namaMenu;
        } else {
            $log['errorCode'] = 2;
            $log['errorMessage'] = 'Simpan Menu Gagal';
            $log['errorType'] = 'error';
        }

        $response = \Config\Services::response();
        return $response->setJSON($log);
    }

    public function getMenu($id)
    {
        $menuModel = new MenuModel();
        $query = $menuModel->find($id);
        $response = \Config\Services::response();
        return $response->setJSON(['data' => $query]);
    }

    public function updateMenu() 
    {
        $request = \Config\Services::request();
        $namaMenu = $request->getPost('namaMenu');
        $idMenu = $request->getPost('idMenu');
        $menuModel = new MenuModel();
        $queryInsert = $menuModel->update($idMenu, ['menu_desc' => $namaMenu]);
        if ($queryInsert) {
            $log['errorCode'] = 1;
            $log['errorMessage'] = 'Update Menu Berhasil';
            $log['errorType'] = 'success';
            $log['menu_id'] = $idMenu;
            $log['menu_desc'] = $namaMenu;
        } else {
            $log['errorCode'] = 2;
            $log['errorMessage'] = 'Update Menu Gagal';
            $log['errorType'] = 'error';
        }

        $response = \Config\Services::response();
        return $response->setJSON($log);
    }

    public function getMenuAkses($id)
    {
        $menuAksesModel = new MenuAksesModel();
        $query = $menuAksesModel->find($id);
        $response = \Config\Services::response();
        return $response->setJSON(['data' => $query]);
    }

    public function tambahMenuAkses()
    {
        $request = \Config\Services::request();
        $db = \Config\Database::connect();
        $namaMenu = $request->getPost('namaMenu');
        $idMenu = $request->getPost('idMenu');
        $kodeMenuAkses = $request->getPost('kodeMenuAkses');
        $menuAksesModel = new MenuAksesModel();
        $queryInsert = $menuAksesModel->insert(['menu_akses_desc' => $namaMenu, 'menu_akses_id' => $kodeMenuAkses, 'menu_id' => $idMenu]);
        if ($queryInsert) {
            $log['errorCode'] = 1;
            $log['errorMessage'] = 'Tambah Menu Akses Berhasil';
            $log['errorType'] = 'success';
            $log['akses_id'] = $db->insertID();
            $log['menu_akses_desc'] = $namaMenu;
            $log['menu_akses_id'] = $kodeMenuAkses;
            $log['menu_id'] = $idMenu;
        } else {
            $log['errorCode'] = 2;
            $log['errorMessage'] = 'Tambah Menu Akses Gagal';
            $log['errorType'] = 'error';
        }

        $response = \Config\Services::response();
        return $response->setJSON($log);
    }

    public function updateMenuAkses() 
    {
        $request = \Config\Services::request();
        $namaMenu = $request->getPost('namaMenu');
        $idMenu = $request->getPost('idMenu');
        $kodeMenuAkses = $request->getPost('kodeMenuAkses');
        $menuAksesModel = new MenuAksesModel();
        $queryInsert = $menuAksesModel->update($idMenu, ['menu_akses_desc' => $namaMenu, 'menu_akses_id' => $kodeMenuAkses]);
        if ($queryInsert) {
            $log['errorCode'] = 1;
            $log['errorMessage'] = 'Update Menu Akses Berhasil';
            $log['errorType'] = 'success';
            $log['akses_id'] = $idMenu;
            $log['menu_akses_desc'] = $namaMenu;
            $log['menu_akses_id'] = $kodeMenuAkses;
        } else {
            $log['errorCode'] = 2;
            $log['errorMessage'] = 'Update Menu Akses Gagal';
            $log['errorType'] = 'error';
        }

        $response = \Config\Services::response();
        return $response->setJSON($log);
    }
}