<?php

namespace App\Controllers\Master;

use App\Controllers\BaseController;
use App\Libraries\Tema;
use CodeIgniter\Database\RawSql;
use App\Models\Master\SiswaModel;


class SiswaController extends BaseController
{
    private $tema;

    function __construct()
    {
        helper(['form']);
        $this->tema = new Tema();
    }

    public function index()
    {
        $this->tema->setJudul('Siswa');
        $this->tema->loadTema('/master/siswa');
    }

    public function ajaxList()
    {
        $siswaModel = new SiswaModel();
        $siswaModel->setRequest($this->request);
        $lists = $siswaModel->getDatatables();
        $data = [];
        $no = $this->request->getPost("start");
        foreach ($lists as $list) {
            $no++;
            $row = [];
            $id = $list->siswa_id;
            $aksi = '<a href="javascript:;" class="" data-toggle="tooltip" data-placement="top" title="Lihat Data" onclick="lihat_data('.$id.')"><i class="fa fa-search"></i></a>';
            if(enforce(1, 3)) {
                $aksi .= '<a href="javascript:;" class="ml-2" data-toggle="tooltip" data-placement="top" title="Edit Data" onclick="edit_data('.$id.')"><i class="fa fa-edit"></i></a>';
            }

            if(enforce(1, 4)) {
                $aksi .= '<a href="javascript:;" class="text-danger ml-2" data-toggle="tooltip" data-placement="top" title="Delete Data" onclick="delete_data('.$id.')"><i class="fa fa-trash"></i></a>';
            }
            $action = $aksi;
            
            $row[] = $action;
            $row[] = $no;
			$row[] = $list->siswa_nama;
			$row[] = $list->siswa_alamat;
			$row[] = $list->siswa_tempat_lahir;
			$row[] = date('d-m-Y', strtotime($list->siswa_tanggal_lahir));
            $data[] = $row;
        }
        $output = [
                "draw" => $this->request->getPost('draw'),
                "recordsTotal" => $siswaModel->countAll(),
                "recordsFiltered" => $siswaModel->countFiltered(),
                "data" => $data
            ];
        echo json_encode($output);
    }

    public function saveData()
    {
        $siswaModel = new SiswaModel();

        $method = $this->request->getPost('method');
        
		$imgsiswa_photo = $this->request->getFile('val_siswa_photo');

        $validation = [
            'val_siswa_nama' => 'required','val_siswa_alamat' => 'required','val_siswa_tempat_lahir' => 'required','val_siswa_tanggal_lahir' => 'required',
        ];

        
		if (!empty($_FILES['val_siswa_photo']['name'])) {
			$validation['val_siswa_photo'] = 'uploaded[val_siswa_photo]'
			. '|is_image[val_siswa_photo]'
			. '|mime_in[val_siswa_photo,image/jpg,image/jpeg,image/gif,image/png,image/webp]'
			. '|max_size[val_siswa_photo,2048]';
		}
        $validated = $this->validate($validation);
        if ($validated === false) {
            $errors = $this->validator->getErrors();
            $message = '<ul>';
            foreach ($errors as $key => $value) {
                $message .= '<li>'.$value.'</li>';
            }
            
			if (!empty($_FILES['val_siswa_photo']['name'])) {
				$type = $imgsiswa_photo->getClientMimeType();
				$message .= '<li>'.$imgsiswa_photo->getErrorString() . '(' . $imgsiswa_photo->getError() . ' Type File ' . $type . ' )</li>';
			}
            $message .= '</ul>';

            $log['errorCode'] = 2;
            $log['errorMessage'] = $message;
            return $this->response->setJSON($log);
        }

        $id = $this->request->getPost('siswa_id');
		$data['siswa_nama'] = $this->request->getPost('val_siswa_nama');
		$data['siswa_alamat'] = $this->request->getPost('val_siswa_alamat');
		$data['siswa_tempat_lahir'] = $this->request->getPost('val_siswa_tempat_lahir');
		$data['siswa_tanggal_lahir'] = $this->request->getPost('val_siswa_tanggal_lahir') == null ? null : date('Y-m-d', strtotime($this->request->getPost('val_siswa_tanggal_lahir')));
		if (!empty($_FILES['val_siswa_photo']['name'])) {
			$th = date('Y') . '/' . date('m').'/'.date('d');
			$path = 'uploads/master/siswa/';
			$_dir = $path . $th;
			$dir = ROOTPATH.'public/' . $path . $th;
			if (!file_exists($dir)) {
				mkdir($dir, 0777, true);
			}
			$newName = $imgsiswa_photo->getRandomName();
			$imgsiswa_photo->move($dir, $newName);
			$data['siswa_photo'] = $_dir.'/'.$newName;
		}

        if ($method == 'save') {
            $siswaModel->insert($data);
            $log['errorCode'] = 1;
            $log['errorMessage'] = 'Simpan Data Berhasil';
            $log['errorType'] = 'success';
            return $this->response->setJSON($log);
        } else {
            $siswaModel->update($id, $data);
            $log['errorCode'] = 1;
            $log['errorMessage'] = 'Update Data Berhasil';
            $log['errorType'] = 'success';
            return $this->response->setJSON($log);
        }
    }

    public function getData($id)
    {
        $siswaModel = new SiswaModel();
        $query = $siswaModel->select("siswa_id, siswa_nama, siswa_alamat, siswa_tempat_lahir, DATE_FORMAT(siswa_tanggal_lahir, '%d-%m-%Y') as siswa_tanggal_lahir, siswa_photo")->find($id);
        return $this->response->setJSON($query);
    }

    public function deleteData($id)
    {
        $siswaModel = new SiswaModel();
        $query = $siswaModel->delete($id);
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

    
}
