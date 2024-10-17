<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\Tema;
use App\Models\Admin\MenuAksesModel;
use CodeIgniter\Database\RawSql;
use App\Models\Admin\MenuModel;


class MenuController extends BaseController
{
    private $tema;

    function __construct()
    {
        helper(['form']);
        $this->tema = new Tema();
    }

    public function index()
    {
        $this->tema->setJudul('Menu');
        $this->tema->loadTema('/admin/menu');
    }

    public function ajaxList()
    {
        $menuModel = new MenuModel();
        $menuAksesModel = new MenuAksesModel();

        $menuModel->setRequest($this->request);
        $lists = $menuModel->getDatatables();
        $data = [];
        $no = $this->request->getPost("start");
        foreach ($lists as $list) {
            $no++;
            $row = [];
            $id = $list->menu_id;
            $action = '<a href="javascript:;" class="" data-toggle="tooltip" data-placement="top" title="Edit Data" onclick="edit_data('.$id.')"><i class="fa fa-edit"></i></a>
            <a href="javascript:;" class="text-danger ml-2" data-toggle="tooltip" data-placement="top" title="Delete Data" onclick="delete_data('.$id.')"><i class="fa fa-trash"></i></a>
            <a href="javascript:;" class="text-primary ml-2" data-toggle="tooltip" data-placement="top" title="Tambah Menu Akses" onclick="tambah_data_akses('.$id.')"><i class="fa fa-plus"></i></a>';
            
            $row[] = $action;
            $row[] = $no;
			$row[] = $list->menu_id.' - '.$list->menu_desc;

            $menuAkses = '';
            $queryMenuAksesModel = $menuAksesModel->where(['menu_id' => $id])->findAll();
            foreach ($queryMenuAksesModel as $key => $value) {
                $menuAkses .= "<div class=\"d-flex justify-content-between\">
                                            <div>".$value['menu_akses_id'].' - '.$value['menu_akses_desc']."</div>
                                            <div>
                                                <a href=\"javascript:;\" class=\"\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit Data\" onclick=\"edit_data_akses(".$value['akses_id'].")\"><i class=\"fa fa-edit\"></i></a>
                                                <a href=\"javascript:;\" class=\"text-danger ml-1\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete Data\" onclick=\"delete_data_akses(".$value['akses_id'].")\"><i class=\"fa fa-trash\"></i></a></div>
                                        </div>";
            }
			$row[] = $menuAkses;
            $data[] = $row;
        }
        $output = [
                "draw" => $this->request->getPost('draw'),
                "recordsTotal" => $menuModel->countAll(),
                "recordsFiltered" => $menuModel->countFiltered(),
                "data" => $data
            ];
        echo json_encode($output);
    }

    public function saveData()
    {
        $menuModel = new MenuModel();

        $method = $this->request->getPost('method');
        

        $validation = [
            'val_menu_desc' => 'required',
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

        $id = $this->request->getPost('menu_id');
		$data['menu_desc'] = $this->request->getPost('val_menu_desc');

        if ($method == 'save') {
            $id = $menuModel->insert($data);
            $dataMenuAkses = [
                [
                    'menu_akses_id' => '1', 
                    'menu_akses_desc' => 'Lihat', 
                    'menu_id' => $id,
                ],
                [
                    'menu_akses_id' => '2', 
                    'menu_akses_desc' => 'Tambah', 
                    'menu_id' => $id,
                ],
                [
                    'menu_akses_id' => '3', 
                    'menu_akses_desc' => 'Edit', 
                    'menu_id' => $id,
                ],
                [
                    'menu_akses_id' => '4', 
                    'menu_akses_desc' => 'Hapus', 
                    'menu_id' => $id,
                ]
            ];
            $menuAksesModel = new MenuAksesModel();
            $menuAksesModel->insertBatch($dataMenuAkses);
            $log['errorCode'] = 1;
            $log['errorMessage'] = 'Simpan Data Berhasil';
            $log['errorType'] = 'success';
            return $this->response->setJSON($log);
        } else {
            $menuModel->update($id, $data);
            $log['errorCode'] = 1;
            $log['errorMessage'] = 'Update Data Berhasil';
            $log['errorType'] = 'success';
            return $this->response->setJSON($log);
        }
    }

    public function getData($id)
    {
        $menuModel = new MenuModel();
        $query = $menuModel->select('menu_id, menu_desc')->find($id);
        return $this->response->setJSON($query);
    }

    public function deleteData($id)
    {
        $menuModel = new MenuModel();
        $query = $menuModel->delete($id);
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

    public function menudescExist()
    {
        $menuModel = new MenuModel();
        $menu_id = $this->request->getPost('menu_id');
        $menu_desc = $this->request->getPost('menu_desc');
        $query = $menuModel->where(['menu_id !=' => $menu_id, 'menu_desc' => $menu_desc])->first();
        if (!empty($query)) {
            return $this->response->setJSON(false);
        }
        return $this->response->setJSON(true);
    }
}
