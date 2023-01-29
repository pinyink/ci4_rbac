<?php

namespace App\Controllers\Master;

use App\Controllers\BaseController;
use App\Libraries\Tema;
use App\Models\Master\MasterOpdModel;

class MasterOpdController extends BaseController
{
    private $tema;

    function __construct()
    {
        helper(['form']);
        $this->tema = new Tema();
    }

    public function index()
    {
        $this->tema->setJudul('Master OPD');
        $this->tema->loadTema('/master/masteropd');
    }

    public function ajaxList()
    {
        $masterOPDModel = new MasterOpdModel();
        $masterOPDModel->setRequest($this->request);
        $lists = $masterOPDModel->getDatatables();
        $data = [];
        $no = $this->request->getPost("start");
        foreach ($lists as $list) {
            $no++;
            $row = [];
            $id = $list->id;
            $action = '<a href="javascript:;" class="" data-toggle="tooltip" data-placement="top" title="Edit Data" onclick="edit_data('.$id.')"><i class="fa fa-edit"></i></a><a href="javascript:;" class="text-red ml-2" data-toggle="tooltip" data-placement="top" title="Delete Data" onclick="delete_data('.$id.')"><i class="fa fa-trash"></i></a>';
            
            $row[] = $action;
            $row[] = $no;
			$row[] = $list->opd;
			$row[] = number_format($list->deleted, 0, '.', ',');
            $data[] = $row;
        }
        $output = [
                "draw" => $this->request->getPost('draw'),
                "recordsTotal" => $masterOPDModel->countAll(),
                "recordsFiltered" => $masterOPDModel->countFiltered(),
                "data" => $data
            ];
        echo json_encode($output);
    }

    public function saveData()
    {
        $masterOPDModel = new MasterOpdModel();

        $method = $this->request->getPost('method');

        $id = $this->request->getPost('id');
		$data['opd'] = $this->request->getPost('val_opd');
		$data['deleted'] = $this->request->getPost('val_deleted') == null ? null : str_replace('.', '', $this->request->getPost('val_deleted'));

        if ($method == 'save') {
            $masterOPDModel->insert($data);
            $log['errorCode'] = 1;
            $log['errorMessage'] = 'Simpan Data Berhasil';
            $log['errorType'] = 'success';
            return $this->response->setJSON($log);
        } else {
            $masterOPDModel->update($id, $data);
            $log['errorCode'] = 1;
            $log['errorMessage'] = 'Update Data Berhasil';
            $log['errorType'] = 'success';
            return $this->response->setJSON($log);
        }
    }

    public function getData($id)
    {
        $masterOPDModel = new MasterOpdModel();
        $query = $masterOPDModel->find($id);
        return $this->response->setJSON($query);
    }

    public function deleteData($id)
    {
        $masterOPDModel = new MasterOpdModel();
        $query = $masterOPDModel->delete($id);
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

    public function opdExist()
    {
        $masterOPDModel = new MasterOpdModel();
        $id = $this->request->getPost('id');
        $opd = $this->request->getPost('opd');
        $query = $masterOPDModel->where(['id !=' => $id, 'opd' => $opd])->first();
        if (!empty($query)) {
            return $this->response->setJSON(false);
        }
        return $this->response->setJSON(true);
    }
}
